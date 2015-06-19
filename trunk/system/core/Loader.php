<?php defined('SYS_PATH') OR die('access error.');
/**
 * 加载类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
 class Loader{

	//保存对象实例
	public static $objs = array();
	//保存的配置数据
	protected static $config = array();
	//程序与系统路径
	protected static $paths = array(APP_PATH,SYS_PATH);
	
	//构造方法
	public function __construct(){
		$this->_init();			//初始化类
	}

	public function view($name,$data=array(),$way=false){
		$view_path = Yiee::conf('view_path');
		if( empty($view_path) ){
			$path = APP_PATH.'views/'.$name.'.php';
		}else{
			$path = $view_path.'/'.$name.'.php';
		}
		extract($data);
		if($way===true){
			ob_start ();
			require_once($path);
			$data = ob_get_contents();
			ob_end_clean ();
			return $data;
		}else{
			require_once($path);
		}
	}

	//初始化
	private function _init(){
		//引入常量
		require_once(APP_PATH.'config/constants.php');
		//系统核心
		$this->_init_sys();
		//自动加载
		$this->_init_auto();
	}
	

	//系统核心
	protected function _init_sys(){
		$sys_list = array(
			'Common',			//公共函数
			'Benchmark',		//基准类
			'URI',				//URI
			'Controller',		//系统控制器
			'Model',			//系统model
		);
		//引入核心
		$this->_inc_core($sys_list);
	}
	//引入系统核心
	private function _inc_core($sys_list){
		foreach ($sys_list as $v ){
			$path = 'core/'.$v.'.php';
			//核心类可替换
			if( file_exists(APP_PATH.$path) ){
				require_once(APP_PATH.$path);
			}else{
				require_once(SYS_PATH.$path);
			}
			$ext_path = APP_PATH.'core/'.Yiee::conf('class_prefix').$v.'.php';;
			//加载扩展核心类
			if( file_exists($ext_path) ){
				require_once($ext_path);
			}
			
		}
	}

	//APP自动加载
	protected function _init_auto(){
		
	}



	/**
	 * 加载不实例化的类
	 * param	$namd 类名
	 * param	$sys 系统名APP_PATH，SYS_PATH默认为SYS_PATH
	 * param	$dir 类所在目录 默认libraries
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public static function inc($name,$dir='libraries/'){
		$path = $dir.$name.'.php';
		foreach (self::$paths as $v){
			if( file_exists($v.$path) ){
				require_once($v.$path);
				return;
			}
		}

	}

 }