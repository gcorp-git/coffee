<?
class RootTool {
	private $_name = '';
	private $_path = '';
	private $_file = '';

	function __construct() {
		$class = get_class($this);
		$name = substr($class, strpos($class, '_') + 1);

		$this->_name = $name;
		$this->_path = SETTINGS['root']['path']['tools'] . "/{$name}";
		$this->_file = "{$this->_path}/{$name}.php";
	}

	/**
	 * Returns own settings values by key or all at once
	 * @param  string $key settings value name
	 * @return mixed
	 */
	function settings(string $key=''): mixed {
		$settings = SETTINGS['tools'][$this->_name];

		if (empty($settings)) return [];
		if (empty($key)) return $settings;
		if (!isset($settings[$key])) return null;

		return $settings[$key];
	}

	/**
	 * Concats the specified path with own directory path
	 * @param  string $path
	 * @return string
	 */
	function path($path='') {
		$path = ltrim(realpath($path), DIRECTORY_SEPARATOR);

		return "{$this->_path}/{$path}";
	}

}
