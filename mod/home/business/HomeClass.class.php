<?php
/*
 * MJFW
 * Version 1.0
 * Author: Galih <galih_xp@yahoo.com or galih.xp@gmail.com> 
 */
class HomeClass extends Database{
	protected $mSqlFile= "home.sql.php";

	public function __construct(){
		parent::__construct();
	}

	function getCount($langId, $id, $month, $year){
		$arrResult = $this->mySqlOpen($this->mSqlQueries['get_count'],array($langId, $id, $month, $year));
		return $arrResult[0]['total'];
	}
	
	function getMenuList($id){
		return $this->mySqlOpen($this->mSqlQueries['get_menu_list'],array($id));
	}
	
	function getLastMonthYear($year){
		if(!empty($year)){
			$str = " WHERE YEAR(dateChange) = '%s' ";
		}
		
		$sql = sprintf($this->mSqlQueries['get_last_month_year'], $str);
		$arrMonthYear = $this->mySqlOpen($sql,array($year));
		return $arrMonthYear['0'];
	}
	
	function getList($langId, $id, $month, $year, $start, $end){
		return $this->mySqlOpen($this->mSqlQueries['get_list'],array($langId, $id, $month, $year, $start, $end));
	}
	
	function getLink($month, $year, $id){
		return $this->mySqlOpen($this->mSqlQueries['get_link'],array($month,$year,$id));
	}
	
	function getUrl(){
		return $this->mySqlOpen($this->mSqlQueries['get_url'],array());
	}
}
?>
