<?php
/*
 * Simple Framework SOA
 * Version 4.0
 * Author: Dyan Galih <dyan.galih@gmail.com>
 */

class Config {
   private $mCfg = array();
   public static $mrInstance;
   private $docRoot;

   public function getDocRoot() {
      return $this->docRoot;
   }

   public function setDocRoot($docRoot) {
      $this->docRoot = $docRoot;
   }

   public function load($file) {
      require_once $file;
      $this->mCfg = array_merge($this->mCfg, $cfg);
      $this->additionalConfig();
   }

   private function additionalConfig() {

      $this->mCfg['baseaddress'] = 'http://' . $this->mCfg['domain_name'] . $this->docRoot;
      $this->mCfg['docbase'] = $this->docRoot . "/sf";

      $this->mCfg['docroot'] = $this->docRoot;
   }

   public function getValue($value = "") {
      if ($value == "") {
         return $this->mCfg;
      } else {
         if (isset($this->mCfg[$value])) {
            return $this->mCfg[$value];
         } else {
            return null;
         }

      }
   }

   public function setValue($key, $value) {
      $this->mCfg[$key] = $value;
   }

   public static function init() {
      $class_name = __CLASS__;
      self::$mrInstance = new $class_name();
   }

   static function instance() {
      return self::$mrInstance;
   }
}

Config::init();
Config::instance()->setDocRoot($docRoot);

Config::instance()->setValue("start_memory", memory_get_usage());

function getmicrotime() {
   list($usec, $sec) = explode(" ", microtime());
   return ((float) $usec + (float) $sec);
}

Config::instance()->setValue("start_time", getmicrotime());

function convert($size) {
   $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
   return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}
//set configuration
Config::instance()->load(Config::instance()->getDocRoot() . 'cfg/cfg.inc.php');
//set session

class Session {

   public static $mrInstance;

   public function setName($name = NULL) {
      if ($name !== NULL) {
         session_name($name);
      }

      return session_name();
   }

   public function setExpire($expire = NULL) {
      if ($expire !== NULL) {
         session_cache_expire($expire);
      }

      return session_cache_expire();
   }

   public function start() {
      session_start();
      //session_write_close();
   }

   public function destroy() {
      session_unset();
      session_destroy();
   }

   public static function init() {
      $class_name = __CLASS__;
      self::$mrInstance = new $class_name();
   }

   public static function instance() {
      return self::$mrInstance;
   }

}

Session::init();
Session::instance()->setName(Config::instance()->getValue('session_name'));
Session::instance()->setExpire(Config::instance()->getValue('expire'));
Session::instance()->start();

class ErrorMessage {
   function backtrace() {
      $levels = 9999;
      $s = '';

      $html = (isset($_SERVER['HTTP_USER_AGENT']));
      $fmt = ($html) ? "</font><font color=#808080 size=-1> %% line %4d, file: <a href=\"file:/%s\">%s</a></font>" : "%% line %4d, file: %s";

      $MAXSTRLEN = 64;

      $s = ($html) ? '<pre align=left>' : '';

      $traceArr = debug_backtrace();

      array_shift($traceArr);
      $tabs = sizeof($traceArr) - 1;

      foreach ($traceArr as $arr) {
         $levels -= 1;
         if ($levels < 0) {
            break;
         }

         $args = array();

         foreach ($tabs as $value) {
            $s .= ($html) ? ' &nbsp; ' : "\t";
         }

         $tabs -= 1;
         if ($html) {
            $s .= '<font face="Courier New,Courier">';
         }

         if (isset($arr['class'])) {
            $s .= $arr['class'] . '.';
         }

         if (isset($arr['args'])) {
            foreach ($arr['args'] as $v) {
               if (is_null($v)) {
                  $args[] = 'null';
               } else if (is_array($v)) {
                  $args[] = 'Array[' . sizeof($v) . ']';
               } else if (is_object($v)) {
                  $args[] = 'Object:' . get_class($v);
               } else if (is_bool($v)) {
                  $args[] = $v ? 'true' : 'false';
               } else {
                  $v = (string) @$v;
                  $str = htmlspecialchars(substr($v, 0, $MAXSTRLEN));
                  if (strlen($v) > $MAXSTRLEN) {
                     $str .= '...';
                  }

                  $args[] = $str;
               }
            }
         }

         $s .= $arr['function'] . '(' . implode(', ', $args) . ')';

         $s .= @sprintf($fmt, $arr['line'], $arr['file'], basename($arr['file']));

         $s .= "\n";
      }
      if ($html) {
         $s .= '</pre>';
      }

      print $s;

      return $s;
   }
}

/*
 * MySql Connection
 * Version 1.1
 * Author: Dyan Galih <dyan.galih@gmail.com>
 * This feature is used for mysql driver
 * Connection use mysql
 */

class MjConn extends ErrorMessage {
   private $mLink;
   private $mResult;
   private $mObject;
   private $mDebug = false;

   public function __construct($hostName, $usrName, $pwdUser, $dbName) {
      $this->mLink = mysqli_connect($hostName, $usrName, $pwdUser, $dbName) or die('Error ' . mysqli_error($link));
      $this->mLink->set_charset("utf8");

   }

   public function __destruct() {
      $this->mLink->close();
   }

   public function debug($debug) {
      $this->mDebug = $debug;
   }

   private function output($msg) {
      $msg .= "<br>\n";
      echo '<font face="verdana" size="2">' . $msg . '</font>';
      echo "<hr>";
   }
   public function beginTrans() {
      return $this->mLink->autocommit(FALSE);
   }
   public function commit() {
      return $this->mLink->commit();
   }
   public function rollback() {
      return $this->mLink->rollback();
   }
   public function mySqlOpen($sql) {
      //ob_start();
      $this->mResult = $this->mLink->query($sql);
      //ob_end_clean();

      if ($this->mDebug) {
         self::output($sql);
      }

      $i = 0;
      $arr_row = array();

      if (!empty($this->mResult)) {

         while ($row = $this->mResult->fetch_assoc()) {
            $arr_row[$i] = $row;
            $i++;
         }
      }

      if ($this->mResult) {
         $this->mResult->close();
      } else {
         if ($this->mDebug) {
            self::output('[ MySqli ] - ' . $this->mLink->errno . ' : ' . $this->mLink->error);
            $this->Backtrace();
         }
         $this->mLink->close();
      }

      return $arr_row;
   }
   public function mySqlExec($sql) {
      //ob_start();
      $result = $this->mLink->query($sql);
      //ob_end_clean();
      $affectedRows = $this->mLink->affected_rows;

      if ($this->mDebug) {
         self::output($sql);
      }

      if (!$result) {

         if ($this->mDebug) {
            self::output('[ MySqli ] - ' . $this->mLink->errno . ' : ' . $this->mLink->error);
            $this->Backtrace();
         }
         return false;
      } else {
         return $affectedRows;
      }
   }

   //copyleft from adodb lib

}

/*
 * Connection Access
 * Version 1.2
 * Author: Dyan Galih <dyan.galih@gmail.com>
 * This feature is used for connection to mysql driver
 */

class Database {

   protected $mSqlFile;
   protected $dbConnect;
   protected $mSqlQueries = array();

   protected function __construct() {
      $sql = "";
      if (file_exists($this->mSqlFile)) {
         require_once $this->mSqlFile;
         $this->mSqlQueries = $sql;
      }
      //require_once Config::instance()->getValue('docroot') . "sf/lib/driver/database/index.inc.php";
      $this->dbConnect = new MjConn(Config::instance()->getValue("dbhost"), Config::instance()->getValue("dbuser"), Config::instance()->getValue("dbpass"), Config::instance()->getValue("dbname"));

   }

   protected function getParsedSql($sql, $params) {
      if (!is_array($params)) {
         return $sql;
      }

      // processing params
      $params_processed = array();
      foreach ($params as $k => $v) {
         if (is_array($v)) {
            $tmp = array();
            foreach ($v as $c => $d) {
               $tmp[] = $this->getParsedSqlHelper($d);
            }

            $params_processed[$k] = implode(', ', $tmp);
         } else {
            $params_processed[$k] = $this->getParsedSqlHelper($v);
         }
      }

      $sql = preg_replace('/([^%])(%[bcdufoxX])/', '\1%s', $sql); // only replace single percent (%%) not double percent (%%)
      $sql = str_replace('\'%s\'', '%s', $sql);

      $sql_parsed = vsprintf($sql, $params_processed);

      return $sql_parsed;
   }

   protected function getParsedSqlHelper($value) {
      if (!isset($value)) {
         return 'NULL';
      } else {
         if (is_string($value) || is_object($value)) {
            return '\'' . trim($value) . '\'';
         } else {
            return $value;
         }

      }
   }

   protected function open($sql, $arr_data) {
      //$arr_data = $this->htmlClean($arr_data);
      $sql = $this->getParsedSql($sql, $arr_data);

      return $this->dbConnect->mySqlOpen($sql);
   }

   protected function execute($sql, $arr_data, $clearStatus = true) {

      if ($clearStatus) {
         //$arr_data = $this->htmlClean($arr_data);
      }

      $sql = $this->getParsedSql($sql, $arr_data);
      $result = $this->dbConnect->mySqlExec($sql);

      if ($result) {
         return true;
      } else {
         return false;
      }

   }

   public function startTrans() {
      $this->dbConnect->beginTrans();
   }

   public function commit() {
      $this->dbConnect->commit();
   }

   public function rollback() {
      $this->dbConnect->rollback();
   }

   public function setDebugOn() {
      $this->dbConnect->debug(true);
   }

   public function setDebugOff() {
      $this->dbConnect->debug(false);
   }
}

class Security extends Database {
   public static $mrInstance;

   public function __construct() {
      $this->mSqlQueries['get_module'] = "
      SELECT
         a.modulId, accessName
      FROM sf_group_modul a
      INNER JOIN sf_modul b ON a.modulId = b.modulId
      LEFT JOIN sf_access_ref c ON c.accessId = a.accessId
      WHERE b.modulCode = '%s'
      AND groupId = '%s';
   ";

      $this->mSqlQueries['get_user_info'] = "
      SELECT
         a.`userId`,
         a.`userName`,
         a.`realName`,
         a.`pwdUser`,
         b.`groupId`,
         b.`groupName`
      FROM
         `sf_user` a
      JOIN
         `sf_group` b ON a.groupId = b.groupId
      WHERE
         `userName` = '%s'
      AND
        status = 'active';
   ";

      $this->mSqlQueries['get_module_action'] = "
       SELECT
         accessId,
         concat(moduleName,'/response/',action,fileName,'.',typeFile,'.class.','php') as fileName,
         `action`,
         `fileName` as `file`,
         `moduleName`,
         `typeFile`
       FROM
         `sf_group_module` a JOIN `sf_module` b ON a.moduleId = b.moduleId
       WHERE
         moduleCode = '%s'
       AND
         a.groupId = '%s'
    ";

      $this->mSqlQueries["check_user"] = "
      SELECT
         *
      FROM
         `sf_user`
      WHERE
        userName = '%s'
      AND
        status = 'active'
      ";
      parent::__construct();
      $this->checkUser();
   }

   private function checkUser() {
      if (empty($_SESSION['user'])) {
         $arrUser = $this->getUser(Config::instance()->getValue('user_default'));
         $this->setUser($arrUser);
      }
   }

   public function getUser($user) {
      return $this->open($this->mSqlQueries['get_user_info'], array($user));
   }

   public function checkLogin($user, $pass) {
      $result = $this->getUser($user);
      if (!empty($result) && ($result[0]["pwdUser"] == md5($pass))) {
         $this->setUser($result);
         return true;
      } else {
         return false;
      }
   }

   public function doLogout() {
      unset($_SESSION['user']);
   }

   private function setUser($arrUser) {
      unset($arrUser[0]["pwdUser"]);
      $_SESSION['user'] = $arrUser['0'];
   }

   public function getAccessRight($module) {
      if (isset($_SESSION['user']['groupId'])) {
         $groupId = $_SESSION['user']['groupId'];
      } else {
         $groupId = "";
      }

      return $this->open($this->mSqlQueries['get_module_action'], array($module, $groupId));
   }

   public function htmlClean($arrData) {
      $arrResult = array();
      foreach ($arrData as $value) {
         $arrResult[] = htmlentities($value, ENT_QUOTES);
      }
      return $arrResult;
   }

   public static function init() {
      $class_name = __CLASS__;
      self::$mrInstance = new $class_name();
   }

   public static function instance() {
      return self::$mrInstance;
   }
}
Security::init();

class MessageResult {

   private static $mrInstance = null;

   function __construct() {

   }

   public function init() {
      $class_name = __CLASS__;
      self::$mrInstance = new $class_name();
   }

   public static function instance() {
      return self::$mrInstance;
   }

   public function formatMessage($status, $data = '', $extData = array()) {

      $msg["status"] = $status;
      $msg["message"] = Config::instance()->getValue('message', 'msg' . $status);
      $msg["data"] = $data;
      $msg = array("status" => $msg["status"], "message" => $msg["message"], "data" => $msg["data"]);
      return array_merge($msg, $extData);
   }

   public function requestSukses($data = '', $extData = array()) {
      $status = '200';
      return $this->formatMessage($status, $data, $extData);
   }

   public function penyimpananSukses($data = '', $extData = array()) {
      $status = '201';
      return $this->formatMessage($status, $data, $extData);
   }

   public function dataTidakLengkap($data = '', $extData = array()) {
      $status = '204';
      return $this->formatMessage($status, $data, $extData);
   }

   public function dataDitemukan($data = '', $extData = array()) {
      $status = '302';
      return $this->formatMessage($status, $data, $extData);
   }

   public function requestTidakSesuai($data = '', $extData = array()) {
      $status = '400';
      return $this->formatMessage($status, $data, $extData);
   }

   public function aksesDenied($data = '', $extData = array()) {
      $status = '401';
      return $this->formatMessage($status, $data, $extData);
   }

   public function tidakDiperbolehkan($data = '', $extData = array()) {
      $status = '403';
      return $this->formatMessage($status, $data, $extData);
   }

   public function dataTidakDitemukan($data = '', $extData = array()) {
      $status = '404';
      return $this->formatMessage($status, $data, $extData);
   }

   public function methodDitolak($data = '', $extData = array()) {
      $status = '405';
      return $this->formatMessage($status, $data, $extData);
   }

   public function penyimpananGagal($data = '', $extData = array()) {
      $status = '406';
      return $this->formatMessage($status, $data, $extData);
   }

   public function butuhOtentikasiProxy($data = '', $extData = array()) {
      $status = '407';
      return $this->formatMessage($status, $data, $extData);
   }

   public function urlTidakTersedia($data = '', $extData = array()) {
      $status = '410';
      return $this->formatMessage($status, $data, $extData);
   }
}
MessageResult::init();

class JsonResponse {
   protected $mModuleId;
   protected $mModule;

   public function __construct() {}

   public function setModuleId($moduleId) {
      $this->mModuleId = $moduleId;
   }

   public function setModuleName($mModuleName) {
      $this->mModule = $mModuleName;
   }

   public function display() {
      $data = $this->ProcessRequest();
      echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
   }
}

class Dispatcher {

   public function __construct() {
      $this->procesRequest();
   }

   private function setGet() {
      if (!empty($_GET['pCode1']) and !empty($_GET['pCode2'])) {
         $get = $this->encondeData($_GET['pCode1'], $_GET['pCode2']);
         if (isset($_GET['mjAjax'])) {
            $_GET = array('pCode' => $_GET['pCode'], 'mjAjax' => $_GET['mjAjax']);
         } else {
            $_GET = array('pCode' => $_GET['pCode']);
         }

         $_GET = array_merge($_GET, $get);
      }
   }

   private function encondeData($id1, $id2) {

      if (!empty($id1) and !empty($id2)) {
         $id1 = base64_decode($id1);
         $id1 = explode("|", $id1);

         $id2 = base64_decode($id2);
         $id2 = explode("|", $id2);

         if (count($id1) == count($id2)) {
            return array_combine($id2, $id1);
         } else {
            die("there is any error on parameter send");
         }

      }

   }

   private function createLink($id, $id1 = "", $id2 = "") {
      if (!empty($id1) and !empty($id2)) {
         $id1 = implode("|", $id1);
         $id1 = base64_encode($id1);
         $id2 = implode("|", $id2);
         $id2 = base64_encode($id2);
         $link = Config::instance()->getValue('baseaddress') . "index.php?pCode=" . $id . "&pCode1=" . $id2 . "&pCode2=" . $id1;
      } else {
         if (!empty($id)) {
            $link = Config::instance()->getValue('baseaddress') . "index.php?pCode=" . $id;
         } else {
            $link = Config::instance()->getValue('baseaddress') . "index.php";
         }

      }
      return $link;
   }

   private function getPage() {

      if (isset($_GET['pCode']) and (!empty($_GET['pCode']))) {
         $pageCode = $_GET['pCode'];
      } else {
         $pageCode = Config::instance()->getValue('default_page');
      }

      self::setGet();

      unset($_SESSION[$pageCode]);

      $pageAcc = Security::instance()->getAccessRight($pageCode);

      if ($pageAcc) {
         return $pageAcc['0'];
      } else {
         die('forbidden');
      }

   }

   private function procesRequest() {
      $arrPage = self::getPage();
      $this->getRequire($arrPage);
   }

   private function getRequire($arrPage) {

      require_once Config::instance()->getValue('docroot') . 'mod/' . $arrPage["fileName"];

      if (isset($arrPage['module_id'])) {
         $response->mModuleId = $arrPage['module_id'];
      }

      if (isset($arrPage['modulename'])) {
         $response->mModule = $arrPage['modulename'];
      }

      $responseClass = $arrPage['action'] . ucfirst($arrPage['file']);
      $response = new $responseClass();

      $response->display();

   }
}
new Dispatcher();
?>