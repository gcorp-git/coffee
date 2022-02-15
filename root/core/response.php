<?
class RootResponse {
	public int $code = 200;
	public array $headers = [];
	public string $body = '';

	function __construct() {
		//
	}

	function submit() {
		if (ob_get_length()) ob_end_clean();

		http_response_code($this->code);

		// todo: process headers

		echo $this->body;

		exit;
	}

}
