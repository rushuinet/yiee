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
		self::$db = new $dbtype;
		self::$db->connect($config);
	}
	
	//运行SQL
	public static function query($sql){
		return self::$db->query($sql);
	}

}