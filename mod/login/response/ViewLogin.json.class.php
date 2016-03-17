<?php
/*
 * Simple Framework SOA
 * Module Version 1.0
 * Author: Galih <galih_xp@yahoo.com or galih.xp@gmail.com> 
 */

class ViewLogin extends JsonResponse{
   static function ProcessRequest(){
		if ($_SESSION["user"]["userName"] == "nobody") {
			include('login.html');
		} else {
			header("Location: panel");
		}
		exit;
   }
}