<?
trait RootLogger {
	
	/**
	 * assert(true) - do nothing
	 * assert(false) - error('AssertionError', null)
	 * assert(false) - error('AssertionError', [])
	 * assert(true ?: ['oh, no...', []]) - do nothing
	 * assert(false ?: 'oh, no...') - error('oh, no...', null)
	 * assert(false ?: ['oh, no...', []]) - error('oh, no...', [])
	 */
	static function assert(bool|string|array $conditionOrError): void {
		if ($conditionOrError === true) return;

		$message = $conditionOrError;
		$data = null;

		if (empty($message)) $message = 'AssertionError';
		if (is_array($message)) {
			$data = $message['data'] ?? $message[1] ?? null;
			$message = $message['message'] ?? $message[0];
		}

		throw new RootError($message, $data);
	}

	static function log(string|Exception|Error $label, mixed $data=null): void {
		if (!is_string($label)) { self::_handle_error( $label ); return; }

		$path = SETTINGS['root']['path']['logs'] . "/{$label}";
		$timestamp = date('Y.m.d H.i.s', time());
		$file = "{$path}/{$timestamp}.php";
		$logs = [];
		
		if (file_exists($file)) $logs = include($file);
		
		$logs[] = $data;
		$contents = '<? return ' . var_export( $logs, true ) . ';';

		if (!is_dir($path)) @mkdir($path, 0777, true);

		file_put_contents($file, $contents);
	}

	private static function _handle_error(Exception|Error $e): void {
		$class = get_class($e);

		$dump = [
			'class' => $class,
			'message' => $e->getMessage(),
		];

		$file = str_replace(HOME, '', $e->getFile());
		$trace = '';
		
		if (!empty($file)) {
			$trace .= "\n{$file}:{$e->getLine()}";
		}

		foreach ($e->getTrace() as $t) {
			if (!isset($t['class'])) $t['class'] = '';
			if (!isset($t['type'])) $t['type'] = '';
			if (!isset($t['file'])) $t['file'] = '';
			if (!isset($t['line'])) $t['line'] = '';

			$file = str_replace(HOME, '', $t['file']);
			$position = "{$file}:{$t['line']}";
			$function = "{$t['class']}{$t['type']}{$t['function']}()";
			$trace .= "\n{$position} - {$function}";
		}

		if ($class === 'RootError') {
			$dump['data'] = $e->getData();
		}

		$dump['trace'] = $trace . "\n";

		self::log('error', $dump);
	}

}
