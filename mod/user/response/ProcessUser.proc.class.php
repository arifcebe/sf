<?php
/*
 * Simple Framework SOA
 * Veritrans Payment
 * Module Version 1.0
 * Author: Wahid <wahid.dulrohman@gmail.com> 
 */

require_once Config::instance()->getValue('docroot') . 'mod/user/business/User.class.php';

class ProcessUser {
	var $userObj;

	var $_POST;
	var $_GET;
	
	function __construct() {
		$this->userObj    = new User();

		$this->post       = $_POST;
		$this->get        = $_GET;

		$this->error      = Array();
		$this->return     = Array();
	}

	function AreSet($needed=array()) {
		$received = array_keys($this->post);
		$diff     = array_diff($needed, $received);
		if (count($diff) == 0){
			return TRUE;
		}
	}

	function AreEmpty($fields=array()) {
		foreach ($fields as $fields) {
			if (empty($this->post[$fields])) {
				$this->error[$fields] = 'harus diisi.';
			}
		}
	}

	function CheckPhone() {
		$data                            = Array();
		$importantParams                 = array('t_username','t_phone');

		$cekParams = $this->AreSet($importantParams);
		if ($cekParams == FALSE) {
			$this->return = MessageResult::Instance()->dataTidakLengkap();
		} else {
			$this->AreEmpty($importantParams);               
			if (!empty($this->error)) {
				$this->return = MessageResult::Instance()->penyimpananGagal($this->error);
			} else {

				$userData = $this->userObj->getUserByUserName($this->post['t_username']);
				if (sizeof($userData) == 0) {
					$this->return = MessageResult::Instance()->dataTidakDitemukan();
				} else {

					$prosesData 	= true;
					if ($userData['phone'] != $this->post['t_phone']) {
						$prosesData 	= $this->userObj->updatePhoneByUserName($this->post['t_phone'], $this->post['t_username']);
					}

					if ($prosesData) {
						$data 			= $this->post;
						$this->return 	= MessageResult::Instance()->requestSukses($data);
					} else {
						$this->return 	= MessageResult::Instance()->penyimpananGagal($this->error);
					}

				}
			}      
		}
		return $this->return;
	}
}