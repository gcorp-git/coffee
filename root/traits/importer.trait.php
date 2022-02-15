<?
trait RootImporter {
	private static array $imported = [];

	static function import(string|array $files): void {
		foreach ((array) $files as $file) {
			if (!empty(self::$imported[$file])) return;

			root::assert( file_exists( $file ) ?: ['Import file not found', [
				'file' => $file,
			]]);

			require_once $file;

			self::$imported[$file] = true;
		}
	}

	static function import_class(string $class, string $file): void {
		if (class_exists($class)) return;

		root::assert( file_exists($file) ?: ['Class file not found', [
			'class' => $class,
			'file' => $file,
		]]);

		require_once $file;

		root::assert( class_exists($class) ?: ['Class not implemented', [
			'class' => $class,
			'file' => $file,
		]]);
	}

}
