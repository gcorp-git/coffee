<?
class APIMethod_auth_signIn implements APIMethod {

	function process(mixed $args=[]): APIResponse {
		if (!isset($_SESSION['auth'])) {
			$_SESSION['auth'] = [
				'login' => $args['login'],
			];
		}

		return api::success();
	}

}
