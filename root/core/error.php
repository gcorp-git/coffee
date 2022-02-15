<?
class RootError extends Exception {
	private mixed $data;

	function __construct(string $message, mixed $data, int $code=0, Throwable $previous=null) {
		parent::__construct($message, $code, $previous);

		$this->data = $data;
	}

	function getData() {
		return $this->data;
	}

	function getBody() {
		return $this->message;
	}

}
