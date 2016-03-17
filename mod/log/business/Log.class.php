<?php
/**
 *   @ClassName : Log
 *   @Copyright : Dyan Galih Nugroho Wicaksi<dyan.galih@gmail.com>
 *   @Analyzed By : Dyan Galih Nugroho Wicaksi<dyan.galih@gmail.com>
 *   @Author By : Dyan Galih Nugroho Wicaksi<dyan.galih@gmail.com>
 *   @Version : 01
 *   @StartDate : 2016-02-23
 *   @LastUpdate : 2016-02-23
 *   @Description : Log
 */

class Log extends Database {
   protected $mSqlFile;

   function __construct() {
      $this->mSqlFile = Config::instance()->getValue("docroot") . 'mod/log/business/log.sql.php';
      parent::__construct();
   }

   public function addLog($log) {
      return $this->Execute($this->mSqlQueries['add_log'], array($log));
   }
}
?>