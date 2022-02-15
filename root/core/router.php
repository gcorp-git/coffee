<?
class RootRouter {

	static function scan(string $uri, array $routes, bool $default=false): array {
		$uri_parts = explode('/', trim(explode('?', $uri)[0], '/'));

		foreach ($routes as $route => $handler) {
			$route_parts = explode('/', trim(explode('?', $route)[0], '/'));
			
			$ok = true;
			$args = [];

			foreach ($route_parts as $index => $part) {
				if (!isset($uri_parts[$index])) break;
				if (strpos($part, ':') === false) {
					if ($part !== $uri_parts[$index]) {
						$ok = false;
						break;
					}
				} else {
					$key = substr($part, 1);
					$args[$key] = $uri_parts[$index];
				}
			}

			if ($ok) return [
				'name' => $route,
				'handler' => $handler,
				'args' => $args,
			];
		}

		if (!$default || empty($routes[''])) return [];

		return [
			'name' => '',
			'handler' => $routes[''],
			'args' => [],
		];
	}

}
