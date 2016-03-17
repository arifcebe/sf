<?php
/**
 *   @ClassName : User
 *   @Copyright : Dyan Galih Nugroho Wicaksi
 *   @Analyzed By : Dyan Galih Nugroho Wicaksi
 *   @Author By : Dyan Galih Nugroho Wicaksi
 *   @Version : 01
 *   @StartDate : 2016-01-30
 *   @LastUpdate : 2016-01-30
 *   @Description : User Class
 */

class User extends Database {
   protected $mSqlFile;

   function __construct() {
      $this->mSqlFile = Config::instance()->getValue("docroot") . 'mod/user/business/user.sql.php';
      parent::__construct();
      #$this->setDebugOn();
   }

   public function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
         $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
   }

   public function addMember($user, $password, $email) {
      return $this->Execute($this->mSqlQueries['add_member'], array($user, md5($password), $email));
   }

   public function addOtomaticMemberByEmail($email) {
      $passKey = $this->generateRandomString();
      $this->addMember($email, $passKey, $email);
      return $passKey;
   }

   public function getMemberIdByEmail($email) {
      $result = $this->Open($this->mSqlQueries['get_member_id_by_email'], array($email));
      return $result;
   }

   public function getMemberIdByUsernamePhone($key) {
      $result = $this->Open($this->mSqlQueries['get_member_id_by_phone_username'], array($key, $key));
      return $result;
   }

   public function getUserByUserName($userName) {
      $result = $this->Open($this->mSqlQueries['get_user_by_user_name'], array($userName));
      return $result["0"];
   }

   public function updatePhoneByUserName($phone, $userName) {
      return $this->Execute($this->mSqlQueries['update_phone_by_username'], array($phone, $userName));
   }
}
?>