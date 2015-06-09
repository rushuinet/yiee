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
			log_msg('无效的数据库驱动！',__CLASS__);		//此处引用了公共函数
		}
		$dbtype = 'DB_'.$dbtype;
		require_once(SYS_PATH.'database/drivers/'.$dbtype.'.php');		//引入相关数据库驱动文件
		eval('self::$db = '.$dbtype.'::getInstance();');
		self::$db->connect($config);
	}

	//设置字段
	public function fields($param){
		return self::$db->fields($param);
	}

	//设置表名
	public function table($table){
		return self::$db->table($table);
	}

	//join
	public function join($table,$on='',$type='left'){
		return self::$db->join($table,$on,$type);
	}
	//设置条件
	public function where($param){
		return self::$db->where($param);
	}
	//order by
	public function by($param){
		return self::$db->by($param);
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
	
	//插入记录
	public function insert($table,$data=array()){
		return self::$db->insert($table,$data);
	}

	//更新记录
	public function update($table,$data=array(),$where=''){
		return self::$db->update($table,$data,$where);
	}

	//删除记录
	public function del($table,$where=''){
		return self::$db->del($table,$where);
	}
	//清空表
	public function truncate($table){
		return self::$db->truncate($table);
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