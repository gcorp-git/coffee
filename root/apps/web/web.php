<?
require_once __DIR__ . '/core/error.php';

class Web extends RootApp {

	static function onInit(): void {}

	static function onProcess(RootRequest &$request): void {
		$page = self::_getRequestPage($request);

		if (empty($page)) self::error(404);

		$request->response->code = 200;
		$request->response->body = self::page($page['name'], $page['args']);
	}

	static function page(string $name, array $args=[]): string {
		$file = self::_getFile(SETTINGS['web']['path']['pages'], $name);

		if (isset($args['file'])) unset($args['file']);
		if (isset($args['args'])) unset($args['args']);

		if (!empty($args)) extract( $args );

		ob_start();

		include $file;

		return ob_get_clean();
	}

	static function chunk(string $name, array $args=[]): string {
		$file = self::_getFile(SETTINGS['web']['path']['chunks'], $name);

		if (isset($args['file'])) unset($args['file']);
		if (isset($args['args'])) unset($args['args']);

		if (!empty($args)) extract( $args );

		ob_start();

		include $file;

		return ob_get_clean();
	}

	static function snippet(string $name, mixed $value=null, array $args=[]): string {
		$file = self::_getFile(SETTINGS['web']['path']['snippets'], $name);

		if (isset($args['file'])) unset($args['file']);
		if (isset($args['value'])) unset($args['value']);
		if (isset($args['args'])) unset($args['args']);

		if (!empty($args)) extract( $args );

		ob_start();

		$result = include $file;

		ob_get_clean();

		return $result;
	}

	static function src(string $path='', bool $addTimestamp=false): string {
		$home = str_replace("\\", "/", HOME);
		$path = str_replace("\\", "/", $path);

		$ts = $addTimestamp ? (file_exists($path) ? filemtime($path) : '') : '';

		return str_replace($home, '', $path) . (!empty($ts) ? "?{$ts}" : '');
	}

	static function redirect(string $uri): never {
		if (ob_get_length()) ob_end_clean();

		header("HTTP/1.1 301 Moved Permanently");
		header("Location: {$uri}", true, 301);

		exit();
	}

	static function error(int $code=500, string $body=''): never {
		if (empty($body)) {
			$file = self::_getFile(SETTINGS['web']['path']['pages'], "{$code}");

			if (file_exists($file)) {
				$body = self::page("{$code}");
			}
		}

		throw new WebError($code, $body);
	}

	private static function _getRequestPage(RootRequest &$request): array {
		if (!empty(SETTINGS['web']['router'])) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$page = self::_scan_for_page($request->uri);
			} else {
				if (is_array(SETTINGS['web']['router'])) {
					$page = SETTINGS['web']['router'];
				}
				if (is_string(SETTINGS['web']['router'])) {
					$page = ['name' => SETTINGS['web']['router'], 'args' => []];
				}
			}
		} else {
			$page = self::_scan_for_page($request->uri);
		}

		return $page;
	}

	private static function _scan_for_page(string $uri): array {
		$routes = SETTINGS['web']['routes'] ?? [];
		$route = RootRouter::scan($uri, $routes);
		
		if (empty($route)) return [];

		$handler = $route['handler'];

		switch (true) {
			case is_array($handler): {
				$page = ['name' => $handler['name'], 'args' => $handler['args']];

				foreach ($route['args'] as $key => $value) {
					$page['args'][ $key ] = $value;
				}
			} break;
			case is_string($handler): {
				$page = ['name' => $handler, 'args' => []];
			} break;
		}

		return $page;
	}

	private static function _getFile(string $dir, string $name): string {
		$file = realpath("{$dir}/{$name}.php");

		if (!file_exists($file)) return '';
		if (strpos($file, $dir) !== 0) return '';

		return $file;
	}

}
