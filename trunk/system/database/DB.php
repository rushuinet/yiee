<?php defined('SYS_PATH') OR die('access error.');
/**
 * DB类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class DB{
	//有效的驱动
	protected static $valid_drivers = array('mysql','mysqli');

	//初始化
	public static function init($dbtype,$config) {
		if( !in_array($dbtype,self::$valid_drivers) ){
			log_msg('无效的数据库驱动！',__CLASS__);		//此处引用了公共函数
		}
		$dbtype = 'DB_'.$dbtype;
		require_once(SYS_PATH.'database/drivers/'.$dbtype.'.php');		//引入相关数据库驱动文件
		$db =  new $dbtype();
		$db->connect($config);
		return $db;
	}

}