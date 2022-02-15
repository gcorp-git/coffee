<?
class RootApp {
	use RootTooler;

	private static bool $_isInited = false;

	final static function init(): void {
		if (self::$_isInited) return;

		self::$_isInited = true;

		static::onInit();
	}

	final static function process(RootRequest &$request): void {
		if (!self::$_isInited) self::init();

		static::onProcess($request);

		$request->response->submit();
	}

	/**
	 * Concats the specified path with own directory path
	 * @param  string $path
	 * @return string
	 */
	static function path($path='') {
		$ref = new ReflectionClass(static::class);
		$dir = dirname($ref->getFileName());

		return realpath($dir . DIRECTORY_SEPARATOR . $path);
	}

	protected static function onInit(): void {}

	protected static function onProcess(RootRequest &$request): void {}

}
