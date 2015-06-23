<?php defined('SYS_PATH') OR die('access error.');
/**
 * 加载类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
 class Loader{

	//程序与系统路径
	protected $paths = array(APP_PATH,SYS_PATH);
	
	//构造方法
	public function __construct(){
		$this->_init();			//初始化类
	}
	
	//加载视图
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

	/**
	 * 加载类库
	 * param	$name 类名称 （为数组时为批量加载，不支持传配置）
	 * param	$config 配置文件
	 * param	$alias 对象别名
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function library($name,$config=array(),$alias=''){
		if( is_array($name) ){
			foreach ($name as $v ){
				require_once(APP_PATH.'libraries/'.$v.'.php');
				$this->_ins_class($v);
			}
		}else{
			require_once(APP_PATH.'libraries/'.$name.'.php');
			$this->_ins_class($name,$config,$alias);
		}
		
	}

	/**
	 * 加载model
	 * param	$name 模型名称（为数组时为批量加载，不支持定义别名）
	 * param	$alias 对象别名
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function model($name,$alias=''){
		if( is_array($name) ){
			foreach ($name as $v){
				require_once(APP_PATH.'models/'.$v.'.php');
				$this->_ins_class($v);
			}
		}else{
			require_once(APP_PATH.'models/'.$name.'.php');
			$this->_ins_class($name);
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
	
	//实例化类并加入到对象实例中：Yiee::$objs['objname']
	private function _ins_class($name,$config=array(),$alias=''){
		$class_name = get_classnmae($name);
		$obj = new $class_name($config);
		if($alias){
			Yiee::$objs[$alias] = $obj;
		}else{
			Yiee::$objs[$class_name] = $obj;
		}
	}



 }