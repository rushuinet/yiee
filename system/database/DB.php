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
		if( !self::_trust($dbtype) ){
			
		}
		$dbtype = 'DB_'.$dbtype;
		require_once(SYS_PATH.'database/drivers/'.$dbtype.'.php');		//引入相关数据库驱动文件
		eval('self::$db = '.$dbtype.'::getInstance();');
		self::$db->connect($config);
	}

	//设置表名
	public function table($table){
		return self::$db->table($table);
	}

	//设置字段
	public function fields($param){
		return self::$db->fields($param);
	}

	//设置条件
	public function where($param){
		return self::$db->where($param);
	}

	//设置limit
	public function limit($limit='',$offset=''){
		return self::$db->limit($limit,$offset);
	}

	//拼装sql
	public function get($table='',$where=''){
		return self::$db->get($table,$where);
	}

	//运行SQL
	public static function query($sql){
		return self::$db->query($sql);
	}

	//结果集
	public function result(){
		return self::$db->result();
	}

	//一条记录
	public function result_one(){
		return self::$db->result_one();
	}
	
	//返回最后查询语句
	public function last_query(){
		return self::$db->last_query();
	}
	
	//返回最后一个查询中自动生成的 ID
	public function insert($table,$data=array()){
		return self::$db->insert($table,$data);
	}
	
	//信任的驱动
	private function _trust($dbtype){
		$arr = array('mysql','mysqli');
		if(in_array($dbtype,$arr)){
			return true;
		}else{
			return false;
		}
	}
}