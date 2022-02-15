<?
class RootRequest {
	readonly string $uri;
	readonly array $options;
	readonly array $route;
	readonly RootResponse $response;
	
	function __construct(string $uri, array $options=[]) {
		$exploded = explode('?', $uri);

		$this->uri = trim($exploded[0], '/');
		$this->options = $options;
		$this->route = $this->_get_route();
		$this->response = new RootResponse();
	}

	function process(): void {
		try {
			$this->_check_handler();
			forward_static_call($this->route['handler'], $this);
		} catch (RootError $e) {
			$this->response->code = $e->getCode() ?: 500;
			$this->response->body = $e->getBody();
		} catch (Exception|Error $e) {
			root::log($e);
			$this->response->code = 500;
			$this->response->body = '';
		}
	}

	private function _get_route(): array {
		$route = RootRouter::scan($this->uri, SETTINGS['root']['routes'] ?? [], true);

		root::assert( !empty($route) ?: ['Request route not found', [
			'uri' => $this->uri,
		]]);

		$handler = SETTINGS['root']['routes'][$route['name']] ?? null;

		if (is_string($handler)) $handler = [$handler, 'process'];

		$route['handler'] = $handler;

		return $route;
	}

	private function _check_handler(): void {
		$class = $this->route['handler'][0];

		$class_exists = class_exists($class);

		root::assert( $class_exists ?: ['Request handler class not found', [
			'uri' => $this->uri,
			'route' => $this->route,
		]]);

		$correct_parent = is_subclass_of($class, 'RootApp');

		root::assert( $correct_parent ?: ['Incorrect request parent class', [
			'uri' => $this->uri,
			'route' => $this->route,
		]]);

		$is_callable = is_callable($this->route['handler']);

		root::assert( $is_callable ?: ['Incorrect request route handler', [
			'uri' => $this->uri,
			'route' => $this->route,
		]]);
	}

}
