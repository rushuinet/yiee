<?php
/**
 * 启动引擎
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

//版本号
define('YIEE_VERSION', '0.0.1');
//系统目录
define('SYS_PATH',dirname(__FILE__).'/');
//APP目录
define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/'.$app_dir.'/');

class Yiee{
	
	private static $config;		//配置
	private static $autoload;	//自动加载
	public static $benchmark;	//基准对象
	public static $uri;			//uri对象

	//准备框架文件
	private static function start(){
		//引入常量
		require_once(APP_PATH.'config/constants.php');
		//配置文件
		self::$config = include(APP_PATH.'config/config.php');
		//自动加载
		self::$autoload = include(APP_PATH.'config/autoload.php');
		//基准类
		require_once(SYS_PATH.'core/Benchmark.php');
		//引入公共函数
		require_once(SYS_PATH.'core/Common.php');
		//URI
		require_once(SYS_PATH.'core/URI.php');
		//系统model
		require_once(SYS_PATH.'core/Model.php');
		//系统控制器
		require_once(SYS_PATH.'core/Controller.php');
		//数据库操作
		require_once(SYS_PATH.'database/DB.php');
		
	}
	
	//初始化基准类
	private static function init_benchmark(){
		self::$benchmark = new benchmark();
		self::$benchmark->mark('sys_start');
	}

	//初始化URI
	private static function init_uri(){
		$URI = URI::getInstance();
		$URI->c_name = is_set($URI->c_name,self::$config['default_controller']);
		$URI->m_name = is_set($URI->m_name,self::$config['default_method']);
		self::$uri = $URI;
	}
	
	//初始化数据库链接
	private static function init_db(){
		$link = self::$autoload['dblink'];
		if( !empty($link) ){
			$db = self::$config['database'];
			DB::init($db[$link]['dbdriver'],$db[$link]);
		}
	}

	//初始化控制器
	private static function init_controller(){
		$uri = self::$uri;
		$name = $uri->c_name;
		require_once(APP_PATH.'controllers/'.$uri->c_dir.$name.'.php');
		$obj = new $name();
		call_user_func_array(array($obj, $uri->m_name), $uri->m_arr );
	}

	//运行初始化
	public static function run(){
		self::start();
		self::init_benchmark();
		self::init_uri();
		self::init_db();
		self::init_controller();
	}

}
//运行框架
Yiee::run();