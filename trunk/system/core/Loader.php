<?php defined('SYS_PATH') OR die('access error.');
/**
 * 加载类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
 class Loader{

	//系统加载对象实例
	public $loadobj = array();
	
	//构造方法
	public function __construct(){
		$this->_init();			//初始化类
	}

	/**
	 * 装载配置
	 * param	$name 配置文件名称
	 * param	$way 为true时直接返回数据,false添加到Yiee::$config
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function config($name,$way=false){
		if(empty($name)){
			return false;
		}
		if( is_array($name) ){
			foreach ($name as $v){
				$this->_inc_config($v);
			}
		}else{
			$this->_inc_config($name,$way);
		}
	}
	//辅助配置装载
	private function _inc_config($name,$way=false){
		$env_path = APP_PATH.'config/'.ENVIRONMENT.'/'.$name.'.php';
		if( file_exists($env_path) ){
			$arr = include($env_path);
		}else{
			$arr = include(APP_PATH.'config/'.$name.'.php');
		}
		if($way === true){
			return $arr;
		}else{
			Yiee::$config[$name] = $arr;
		}
	}
	
	/**
	 * 加载视图
	 * param	$name	视图文件
	 * param	$data	分配的数据
	 * param	$way	输出方式(true返回字符串,false直接引入文件)
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function view($name,$data=array(),$way=false){
		$obj = Controller::get_instance();
		$obj->view->display($name,$data,$way);
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
		if(empty($name)){
			return false;
		}
		if( is_array($name) ){
			foreach ($name as $v ){
				$this->_inc_file('libraries/'.$v.'.php');
				$this->_ins_class($v);
			}
		}else{
			$this->_inc_file('libraries/'.$name.'.php');
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
		if(empty($name)){
			return false;
		}
		if( is_array($name) ){
			foreach ($name as $v){
				require_once(APP_PATH.'models/'.$v.'.php');
				$this->_ins_class($v);
			}
		}else{
			require_once(APP_PATH.'models/'.$name.'.php');
			$this->_ins_class($name,array(),$alias);
		}
		
	}

	/**
	 * 加载helper
	 * param	$name helper名称
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function helper($name){
		if(empty($name)){
			return false;
		}
		if( is_array($name) ){
			foreach ($name as $v){
				$this->_inc_file('helpers/'.$v.'.php');
			}
		}else{
			$this->_inc_file('helpers/'.$name.'.php');
		}	
	}

	/**
	 * 加载语言
	 * param	$name helper名称
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function lang($name,$lang=''){
		if( is_array($name) ){
			foreach ($name as $v){
				$this->_inc_lang($v);
			}
		}else{
			$this->_inc_lang($name,$lang);
		}	
	}
	private function _inc_lang($path,$_lang=''){
		$name = conver_str($path);
		if(empty($_lang)){
			$_lang = Yiee::conf('default_lang');
		}
		Yiee::$lang[$name] = include(APP_PATH.'language/'.$_lang.'/'.$path.'.php');
	}

	/**
	 * 加载程序包
	 * param	$name 程序包名称
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function package($name){
		if(empty($name)){
			return false;
		}
		if( is_array($name) ){
			foreach ($name as $v){
				require_once(APP_PATH.'packages/'.$v.'.php');
			}
		}else{
			require_once(APP_PATH.'packages/'.$name.'.php');
		}
	}

	/**
	 * 装载数据库
	 * param	$link 数据库链接
	 * param	$way 返回方式 为true时返回数据库链接对象
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function database($link='default',$way=false){
		$db = $this->_inc_config('database',true);
		$obj = DB::init($db[$link]['dbdriver'],$db[$link]);
		$obj->connect($db[$link]);	//链接数据库
		if($way === true){
			return $obj;
		}else{
			$this->set_obj('db',$obj);
		}
	}

	//初始化
	private function _init(){
		//常量
		$cons_path = APP_PATH.'config/'.ENVIRONMENT.'/constants.php';
		if( file_exists($cons_path) ){
			require_once($cons_path);
		}else{
			require_once(APP_PATH.'config/constants.php');
		}
		//配置文件
		$config_path = APP_PATH.'config/'.ENVIRONMENT.'/config.php';
		if( file_exists($config_path) ){
			Yiee::$config = include($config_path);
		}else{
			Yiee::$config = include(APP_PATH.'config/config.php');
		}

		//系统核心
		$this->_inc_core(array('Config','Lang','Common','Benchmark','URI'));

		//数据库操作类
		require_once(SYS_PATH.'database/DB.php');

		//自动加载
		$this->_init_auto();

		//加载MVC基类
		$this->_inc_core(array('Model','View','Controller'));

	}
	
	//引入系统核心
	private function _inc_core($sys_list){
		foreach ($sys_list as $v ){
			$path = 'core/'.$v.'.php';
			//核心类可替换
			$this->_inc_file($path);
			//加载扩展核心类
			$ext_path = APP_PATH.'core/'.Yiee::conf('class_prefix').$v.'.php';
			if( file_exists($ext_path) ){
				require_once($ext_path);
			}
			
		}
	}

	//APP自动加载
	protected function _init_auto(){
		$auto = $this->_inc_config('autoload',true);
		//数据库链接
		if(!empty($auto['database'])){
			$this->database($auto['database']);
		}
		//配置
		$this->config($auto['config']);
		//函数
		$this->helper($auto['helper']);
		//类库
		$this->library($auto['libraries']);
		//包
		$this->package($auto['packages']);
		//语言
		$this->lang($auto['language']);
		//model
		$this->model($auto['model']);

		
	}

	//优先引用APP_PATH中的文件
	private function _inc_file ($path){
		$app_path = APP_PATH.$path;
		if( file_exists($app_path) ){
			require_once($app_path);
		}else{
			require_once(SYS_PATH.$path);
		}
	}
	
	//实例化类并加入到对象实例中：Yiee::$objs['objname']
	private function _ins_class($name,$config=array(),$alias=''){
		$class_name = get_filenmae($name);
		$obj = new $class_name($config);
		if($alias){
			$this->set_obj($alias,$obj);
		}else{
			$this->set_obj($class_name,$obj);
		}
	}

	/**
	 * 加载到超级对象
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function set_obj($name,$obj_name){
		
		if(empty($name) || !is_object($obj_name)){
			return false;
		}

		if( class_exists('Controller') ){
			$obj = Controller::get_instance();
			$obj->$name = $obj_name;
		}else{
			Yiee::$objs[$name] = $obj_name;
		}
	}

 }