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
   
   #require_once Config::instance()->getValue('docroot') . 'mod/gtfw_rest_result/business/MessageResult.class.php';

   class DoLogout extends JsonResponse {
      function ProcessRequest() {
         Security::instance()->doLogout();
         return array("result"=>MessageResult::instance()->requestSukses());
      }
   }

?>