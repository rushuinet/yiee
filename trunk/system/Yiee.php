<?php
/**
 * 启动引擎
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

//版本号
define('YIEE_VERSION', '1.0.1');
//系统目录
define('SYS_PATH',dirname(__FILE__).'/');
//APP目录
define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/'.$app_dir.'/');

class Yiee{
	
	private static $config;				//配置
	public static $objs=array();		//保存对象实例

	//准备框架文件
	private static function start(){
		//配置文件
		self::$config = include(APP_PATH.'config/config.php');
		//加载类
		require_once(SYS_PATH.'core/Loader.php');
		$obj = new Loader();
		self::$objs['load'] = $obj;
		
	}
	
	//初始化基准类
	private static function _init_benchmark(){
		self::$objs['benchmark'] = new benchmark();
		self::$objs['benchmark']->mark('sys_start');
	}

	//初始化URI
	private static function _init_uri(){
		$URI = URI::getInstance();
		$URI->c_name = is_set($URI->c_name,self::$config['default_controller']);
		$URI->m_name = is_set($URI->m_name,self::$config['default_method']);
		self::$objs['uri'] = $URI;
	}
	
	//初始化控制器
	private static function _init_controller(){
		$uri = self::$objs['uri'];
		$name = $uri->c_name;
		require_once(APP_PATH.'controllers/'.$uri->c_dir.$name.'.php');
		$obj = new $name();
		call_user_func_array(array($obj, $uri->m_name), $uri->m_arr );
	}
	
	//初始化数据库链接
	private static function _init_db(){
		$autoload = include(APP_PATH.'config/autoload.php');
		require_once(SYS_PATH.'database/DB.php');
		$link = $autoload['dblink'];
		if( !empty($link) ){
			$db = self::$config['database'];
			self::$objs['db'] = DB::init($db[$link]['dbdriver'],$db[$link]);
			self::$objs['db']->connect($db[$link]);	//链接数据库
		}
		//self::$objs['db'] = DB::$db;
	}
	
	//获取配置
	public static function conf($key=''){
		if(empty($key)){
			return self::$config;
		}else{
			return self::$config[$key];
		}
	}
	
	//运行初始化
	public static function run(){
		self::start();
		self::_init_benchmark();
		self::_init_uri();
		self::_init_db();
		self::_init_controller();
	}

}
//运行框架
Yiee::run();