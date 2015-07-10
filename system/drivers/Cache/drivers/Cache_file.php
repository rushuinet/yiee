<?php defined('SYS_PATH') OR die('access error.');
/**
 * 文件缓存
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class Cache_file {
	
	protected $_cache_path = NULL;			//路径
	protected $_cache_suffix = NULL;			//路径
	protected $_key = '';					//缓存KEY
	
	public function __construct($config = array()){
		$this->_cache_path = Yiee::$config['cache_path'] ? IN_PATH.Yiee::$config['cache_path'] : APP_PATH.'cache/';
		$this->_cache_suffix = empty(Yiee::$config['cache_suffix']) ? '.php' : ('.'.Yiee::$config['cache_suffix']);
	}

	//保存
	public function save($id, $data, $ttl = 60, $raw = FALSE){
		$data = array(
			'time'		=> time(),
			'ttl'		=> $ttl,
			'data'		=> $data
		);
		return write_file($this->_cache_path.$id.$this->_cache_suffix,serialize($data));
	}
	
	//获取
	public function get($id){

		$file = $this->_cache_path.$id.$this->_cache_suffix;
		if ( ! file_exists($file) ){
			return FALSE;
		}
		$data =  unserialize(file_get_contents($file));
		if ($data['ttl'] > 0 && time() > $data['time'] + $data['ttl']){
			unlink($file);
			return FALSE;
		}
		return is_array($data) ? $data['data'] : FALSE;
	}

	//删除
	public function del($id){
		return unlink($this->_cache_path.$id.$this->_cache_suffix);
	}
}