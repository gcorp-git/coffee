<?
class APIError extends RootError {

	function __construct(int $code, string $message, mixed $data=null, Throwable $previous=null) {
		parent::__construct($message, $data, $code, $previous);
	}

	function getBody() {
		$ok = ($code === 200);
		$dump = ['ok' => $ok];

		if (!$ok) {
			$dump['code'] = $this->code;
			$dump['message'] = $this->message;
		}

		if (!is_null($this->data)) {
			$dump['data'] = $this->data;
		}

		return json_encode($dump, JSON_HEX_APOS);
	}

}
