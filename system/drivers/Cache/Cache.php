<?php
/**
 * 缓存类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class Cache {

	//有效驱动
	protected $valid_drivers = array(
		'file',
		'memcached',
	);
	protected $_drivers = 'file';			//默认为文件缓存
	protected $_cache_path = NULL;			//路径
	protected $_key = '';					//缓存KEY
	
	public function __construct($config = array()){
		//配置中的适配器类型
		if( isset($config['driver']) ){ 
			$this->_drivers = $config['driver'];
		}
	}
	


}
