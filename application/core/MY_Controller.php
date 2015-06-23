<?php defined('SYS_PATH') OR die('access error.');
	/**
	 * 公共控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	class MY_Controller extends Controller {

		//构造方法
		public function __construct(){
			parent::__construct();
		}

		public function rs(){
			echo '测试扩展控制器<br />';
		}

	}
