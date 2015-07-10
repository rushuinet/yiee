<?php
/**
 * 缓存类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class Cache {

	//有效驱动
	protected static $valid_drivers = array(
		'file',
		'memcached',
	);
	protected $_drivers = 'file';			//默认驱动(文件缓存)
	protected $_cache_path = NULL;			//路径
	protected $_key = '';					//缓存KEY
	public $_obj;				//驱动对象实例
	
	public function __construct($config = array()){

	}
	
	//初始化
	public function init($_drivers='',$config=array()) {
		if( $_drivers != '' && in_array($_drivers,self::$valid_drivers) ){
			$this->_drivers = $_drivers;
		}
		$type = 'Cache_'.$this->_drivers;
		require_once(SYS_PATH.'drivers/Cache/drivers/'.$type.'.php');		//引入相关数据库驱动文件
		$this->_obj =  new $type();
	}
	
	//保存
	public function save($id, $data, $ttl = 60){
		return $this->_obj->save($id, $data, $ttl);
	}
	//获取
	public function get($id){
		return $this->_obj->get($id);
	}
	//获取
	public function del($id){
		return $this->_obj->del($id);
	}
	//清除
	public function clean($id){
		return $this->_obj->clean($id);
	}
	
	//方法回调
//	public function __call($method, $args = array()){
//		return call_user_func_array(array($this->_drivers, $method), $args);
//	}
	
	//驱动回调
//	public function __get($var){
//		if (in_array($var, self::$valid_drivers)){
//			return $this->_obj->$var;
//		}
//	}

}
