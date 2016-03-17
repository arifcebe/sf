<?php
#gtfwTemplateResponse
/**
 * @package DoLogin
 * @copyright Copyright (c) Dyan Galih Nugroho Wicaksi
 * @Analyzed By Dyan Galih
 * @author Dyan Galih
 * @version v01
 * @startDate 2015-09-15
 * @lastUpdate 2015-09-15
 * @description DoLogin
 */

#require_once Config::instance()->getValue('docroot') . 'mod/login/business/Login.class.php';
#require_once Config::instance()->getValue('docroot') . 'mod/member/business/Member.class.php';
#require_once Config::instance()->getValue('docroot') . 'mod/gtfw_rest_result/business/MessageResult.class.php';

class DoLogin extends JsonResponse {
   function ProcessRequest() {
      $userName = isset($_POST["user"]) ? $_POST["user"] : "";
      $pass = isset($_POST["pass"]) ? $_POST["pass"] : "";

      if (Security::instance()->checkLogin($userName, $pass)) {
         // $objMember = new Member();
         // $member = $objMember->getMemberById($_SESSION["user"]["userId"]);

         $user = Security::instance()->getUser($userName);
         unset($user[0]["pwdUser"]);
         // $user[0]["avatar"] = $member[0]["memberPhoto"];
         return MessageResult::instance()->requestSukses($user);
      } else {
         return MessageResult::instance()->dataTidakDitemukan("gagal");
      }
   }
}
?>