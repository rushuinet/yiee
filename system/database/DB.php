<?php
/**
 * DB类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class DB{
	public static $db;
	
	//初始化
	public static function init($dbtype, $config) {
		$dbtype = 'DB_'.$dbtype;
		require_once(SYS_PATH.'database/drivers/'.$dbtype.'.php');		//引入相关数据库驱动文件
		self::$db = new $dbtype();
		self::$db->connect($config);
	}

	
	//运行SQL
	public static function query($sql){
		return self::$db->query($sql);
	}

}