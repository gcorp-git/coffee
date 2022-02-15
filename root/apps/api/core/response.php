<?
class APIResponse {
	private int $code = 200;
	private string $message = '';
	private mixed $data = null;

	function __construct(int $code=200, string $message='', mixed $data=null) {
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
	}

	function __get($prop) {
		if ($prop === 'body') {
			$ok = ($this->code === 200);
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

		return $this->$prop;
	}

}
