<?
class WebError extends RootError {

	function __construct(int $code, string $message, mixed $data=null, Throwable $previous=null) {
		parent::__construct($message, $data, $code, $previous);
	}

}
