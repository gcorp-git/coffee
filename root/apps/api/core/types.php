<?
class APITypes {
	
	static function get(RootRequest $request): array {
		return $_GET;
	}

	static function post(RootRequest $request): array {
		return $_POST;
	}

	static function json(RootRequest $request): array {
		return json_decode(INPUT, true) ?? [];
	}

}
