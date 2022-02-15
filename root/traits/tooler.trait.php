<?
trait RootTooler {
	private static array $_tools = [];

	static function tool(string $tool): RootTool {
		if (empty(self::$_tools[$tool])) {
			$class = static::class . "Tool_{$tool}";
			$ref = new ReflectionClass(static::class);
			$dir = dirname($ref->getFileName());
			$file = "{$dir}/tools/{$tool}/{$tool}.php";

			root::import_class($class, $file);

			self::$_tools[$tool] = new $class();
		}

		return self::$_tools[$tool];
	}

}
