<?php defined('SYS_PATH') OR die('access error.');
/**
 * DB类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class DB{
	
	//初始化
	public static function init($dbtype,$config) {
		if( !self::_trust($dbtype) ){
			log_msg('无效的数据库驱动！',__CLASS__);		//此处引用了公共函数
		}
		$dbtype = 'DB_'.$dbtype;
		require_once(SYS_PATH.'database/drivers/'.$dbtype.'.php');		//引入相关数据库驱动文件
		$db =  new $dbtype();
		$db->connect($config);
		return $db;
	}

	//信任的驱动
	private  static function _trust($dbtype){
		$arr = array('mysql','mysqli');
		if(in_array($dbtype,$arr)){
			return true;
		}else{
			return false;
		}
	}
}