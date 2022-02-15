<?
require_once __DIR__ . '/core/error.php';
require_once __DIR__ . '/core/router.php';
require_once __DIR__ . '/core/response.php';
require_once __DIR__ . '/core/request.php';
require_once __DIR__ . '/core/tool.php';

require_once __DIR__ . '/traits/logger.trait.php';
require_once __DIR__ . '/traits/importer.trait.php';
require_once __DIR__ . '/traits/tooler.trait.php';

require_once __DIR__ . '/core/app.php';

class Root {
	use RootImporter, RootLogger, RootTooler;

	private static bool $_isInited = false;
	private static RootRequest $_request;

	static function init(): void {
		if (self::$_isInited) return;

		self::$_isInited = true;

		self::$_request = new RootRequest($_SERVER['REQUEST_URI']);

		self::_apply_server_settings();

		foreach (SETTINGS['root']['apps'] ?? [] as $name => $path) {
			self::import($path);
		}
	}

	static function request(): RootRequest {
		return self::$_request;
	}

	private static function _apply_server_settings(): void {
		if (!empty(SETTINGS['root']['server']['charset'])) {
			header('Content-Type: text/html; charset=' . SETTINGS['root']['server']['charset']);
		}
		if (isset(SETTINGS['root']['server']['time_limit'])) {
			set_time_limit(SETTINGS['root']['server']['time_limit']);
		}
		if (!empty(SETTINGS['root']['server']['memory_limit'])) {
			ini_set('memory_limit', SETTINGS['root']['server']['memory_limit']);
		}
		if (!empty(SETTINGS['root']['server']['session_start'])) {
			session_start();
		}
	}

}
