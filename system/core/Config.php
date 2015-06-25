<?php defined('SYS_PATH') OR die('access error.');
/**
 * 配置类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
 class Config{

	/**
	 * 输出配置项
	 * param	$key 输出的KEY
	 * param	$index 所在的索引
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function item($key,$index=''){
		return Yiee::conf($key,$index);
	}

	//修改配置项的值(仅对当前操作有效)
	public function set_item($key,$val){
		Yiee::$config[$key] = $val;
	}

 }