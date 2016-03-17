<?php
/**
 * @author Abdul R. Wahid
 **/

class ViewLoginFirst extends JsonResponse {

   function ProcessRequest() {
      $loginFirst = array('login_first' => TRUE);
      if ($loginFirst) {
         $return = MessageResult::Instance()->requestSukses($loginFirst);
      } else {
         $return = MessageResult::Instance()->dataTidakDitemukan($loginFirst);
      }

      return array("result" => $return);
   }
}
?>
