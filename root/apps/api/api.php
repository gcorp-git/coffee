<?
require_once __DIR__ . '/core/error.php';
require_once __DIR__ . '/core/method.php';
require_once __DIR__ . '/core/response.php';
require_once __DIR__ . '/core/types.php';

class API extends RootApp {
	private static array $methods = [];

	protected static function onInit(): void {
		self::$methods = [];

		foreach (SETTINGS['api']['methods'] as $name => $method) {
			if (!is_array($method)) continue;

			self::$methods[$name] = [
				'type' => $method['type'] ?? 'default',
				'class' => $method['class'],
				'file' => $method['file'],
			];
		}
	}

	protected static function onProcess(RootRequest &$request): void {
		$apiResponse = self::_autoProcess($request);

		$request->response->code = $apiResponse->code;
		$request->response->body = $apiResponse->body;
	}

	static function call(string $method, mixed $args=null): APIResponse {
		self::_prepareMethod($method);

		if (!isset(self::$methods[$method])) {
			return self::error(501, 'Not Implemented');
		}

		if (!is_array(self::$methods[$method])) {
			return self::error(405, 'Method Not Allowed');
		}

		return self::$methods[$method]['instance']->process($args);
	}

	static function success(mixed $data=null): APIResponse {
		return new APIResponse(200, '', $data);
	}

	static function error(int $code=500, string $message='', mixed $data=null): APIResponse {
		return new APIResponse($code, $message, $data);
	}

	static function assert(bool|array $conditionOrError): void {
		if ($conditionOrError === true) return;

		$code = 500;
		$message = 'Internal Server Error';
		$data = null;

		if (is_array($conditionOrError)) {
			$code = $conditionOrError['code'] ?? $conditionOrError[0] ?? $code;
			$message = $conditionOrError['message'] ?? $conditionOrError[1] ?? $message;
			$data = $conditionOrError['data'] ?? $conditionOrError[2] ?? null;
		}

		throw new APIError($code, $message, $data);
	}

	private static function _autoProcess(RootRequest $request): APIResponse {
		$route_trimmed = trim($request->route['name'], '/');
		$method = trim(str_replace($route_trimmed, '', $request->uri), '/');

		if (empty($method)) {
			return self::error(400, 'Bad Request');
		}

		self::_prepareMethod($method);

		if (!isset(self::$methods[$method])) {
			return self::error(501, 'Not Implemented');
		}

		if (!is_array(self::$methods[$method])) {
			return self::error(405, 'Method Not Allowed');
		}

		$type = self::$methods[$method]['type'];

		if (method_exists('APITypes', $type)) {
			$args = forward_static_call(['APITypes', $type], $request);

			if ($args instanceof APIResponse) return $args;
		} else {
			$args = $_REQUEST;
		}

		return self::call($method, $args);
	}

	private static function _prepareMethod(string $method): void {
		if (!isset(self::$methods[$method])) return;
		if (!is_array(self::$methods[$method])) return;
		if (is_object(self::$methods[$method]['instance'])) return;
		
		$file = self::$methods[$method]['file'];

		if (file_exists($file)) {
			require_once $file;

			$class = self::$methods[$method]['class'];
			$instance = new $class();
			$implements = class_implements($instance);
			$correct_interface = isset($implements['APIMethod']);

			root::assert( $correct_interface ?: ['Incorrect API method class', [
				'class' => $class,
				'file' => $file,
			]]);
		}

		if (!is_object($instance)) {
			self::$methods[$method] = null;
			self::_prepareMethod($method);

			return;
		}

		self::$methods[$method]['instance'] = $instance;
	}

}
