<?php defined('SYS_PATH') OR die('access error.');
	/**
	 * 公共控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	class Controller {

		public $benchmark;
		public $uri;

		//构造方法
		public function __construct(){
			$this->_set_obj();
		}

		//初始化对象
		public function _set_obj(){
			//初始化对象
			$this->benchmark = Yiee::$benchmark;
			$this->uri = Yiee::$uri;
			$this->db = DB::$db;
		}
	}
