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
	public static $uri;			//uri对象

	//准备框架文件
	private static function start(){
		//引入常量
		require_once(APP_PATH.'config/constants.php');
		//配置文件
		self::$config = include(APP_PATH.'config/config.php');
		//自动加载
		self::$autoload = include(APP_PATH.'config/autoload.php');

		//引入公共函数
		require_once(SYS_PATH.'core/Common.php');

		//系统model
		require_once(SYS_PATH.'core/Model.php');

		//系统控制器
		require_once(SYS_PATH.'core/Controller.php');
		
	}
	
	//初始化URI
	private static function init_uri(){
		require_once(SYS_PATH.'core/URI.php');
		$URI = URI::getInstance();
		$URI->c_name = is_set($URI->c_name,self::$config['default_controller']);
		$URI->m_name = is_set($URI->m_name,self::$config['default_method']);
		self::$uri = $URI;
	}
	
	//初始化数据库链接
	private static function init_db(){
		require_once(SYS_PATH.'database/DB.php');
		if( !empty(self::$autoload['dblink']) ){
			DB::init(self::$config['database'][self::$autoload['dblink']]['dbdriver'],self::$config['database'][self::$autoload['dblink']]);
		}
	}

	//初始化控制器
	private static function init_controller(){
		require_once(APP_PATH.'controllers/'.$dir.$name.'.php');
		$obj = new $name();
		call_user_func_array(array($obj, $method), $params);
	}

	//运行初始化
	public static function run(){
		self::start();
		self::init_uri();
		self::init_db();
		C(self::$uri->c_dir,self::$uri->c_name, self::$uri->m_name, self::$uri->m_arr);
	}

}

Yiee::run();