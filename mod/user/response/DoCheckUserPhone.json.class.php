<?php
/** 
* @author Abdul R. Wahid
**/

require_once Config::instance()->getValue('docroot') . 'mod/user/response/ProcessUser.proc.class.php';

class DoCheckUserPhone extends JsonResponse {

	function ProcessRequest() {

		$userObj = new ProcessUser();
		
		$response = $userObj->CheckPhone();
		
		return $response;
	}
	 
}
?>
