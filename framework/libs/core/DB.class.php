<?php
class DB{
	public static $db;
	public static function init($dbtype="sql",$config=array()){
		include_once APP_PATH."framework/libs/db/sql.class.php";
		self::$db = new $dbtype();
		self::$db->connect($config);
	}
	public static function getAll($tableName){
		return self::$db->getAll($tableName);
	}
	public static function select($tabname,$where='',$rowsIndex='',$rowsConut=''){
		return self::$db->select($tabname,$where,$rowsIndex,$rowsConut);
	}
	public static function insert($tabName,$insertValues,$alone=''){
		return self::$db->insert($tabName,$insertValues,$alone);
	}
	public static function update($tabname,$updatevalues,$where){
		return self::$db->update($tabname,$updatevalues,$where);
	}
	public static function delete($tabName,$where){
		return self::$db->delete($tabName,$where);
	}
	public static function getLines($tabname,$where="",$startRowID=0,$endRowID=10){
		return self::$db->getLines($tabname,$where,$startRowID,$endRowID);
	}
	public static function custom($sql){
		return self::$db->custom($sql);
	}
	public static function _getSql(){
		return self::$db->_getSql();
	}
	public static function getValue($table,$key,$where=''){
		return self::$db->getValue($table,$key,$where);
	}
}