<?
class APIMethod_auth_signOut implements APIMethod {

	function process(mixed $args=[]): APIResponse {
		if (isset($_SESSION['auth'])) {
			unset($_SESSION['auth']);
		}

		return api::success();
	}

}
