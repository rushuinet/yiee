<?php defined('SYS_PATH') OR die('access error.');
	/**
	 * 公共控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	class Controller {
		//对象实例
		private static $instance;

		//构造方法
		public function __construct(){
			self::$instance =& $this;
			//初始化对象
			foreach (Yiee::$objs as $k=>$v ){
				$this->$k = Yiee::$objs[$k];
			}
		}

		//获取对象实例
		public static function &get_instance(){
			return self::$instance;
		}

		//分配视图变量
		public function assign($name,$value=''){
			$this->view->assign($name,$value);
		}

		//分配布局
		public function layout($name){
			$this->view->layout($name);
		}

		//输出视图
		public function display($name,$data=array(),$way=false){
			$this->view->display($name,$data,$way);
		}
	}
