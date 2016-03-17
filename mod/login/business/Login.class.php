<?php
   /**
   *   @ClassName : Login
   *   @Copyright : Dyan Galih Nugroho Wicaksi
   *   @Analyzed By : Dyan Galih Nugroho Wicaksi
   *   @Author By : Dyan Galih Nugroho Wicaksi
   *   @Version : 01
   *   @StartDate : 2015-10-15
   *   @LastUpdate : 2015-10-15
   *   @Description : Login
   */
   
   class Login extends Database
   {
      function __construct ()
      {
         $this->mSqlFile = Config::instance()->getValue('docroot').'mod/login/business/login.sql.php';
         parent::__construct();
      }

      #gtfwMethodOpen
      
   }
?>