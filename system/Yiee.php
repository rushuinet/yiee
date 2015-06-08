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

//引入常量
require_once(APP_PATH.'config/constants.php');
//引入公共函数
require_once(SYS_PATH.'core/Common.php');
//引入配置
require_once(APP_PATH.'config/config.php');
//引入自动加载配置
require_once(APP_PATH.'config/autoload.php');

//URI
require_once(SYS_PATH.'core/URI.php');

//数据库操作类
require_once(SYS_PATH.'database/DB.php');
//自动数据库链接
if( !empty($autoload['dblink']) ){
	DB::init($config['database'][$autoload['dblink']]['dbdriver'],$config['database'][$autoload['dblink']]);
}
//系统model
require_once(SYS_PATH.'core/Model.php');

//系统控制器
require_once(SYS_PATH.'core/Controller.php');


class Yiee{
	

	private static $config;		//配置
	public static $uri;			//uri对象
	
	//初始化URI
	private static function init_uri(){
		$URI = URI::getInstance();
		$URI->c_name = is_set($URI->c_name,self::$config['default_controller']);
		$URI->m_name = is_set($URI->m_name,self::$config['default_method']);
		self::$uri = $URI;
	}


	//运行初始化
	public static function run($config){
		self::$config = $config;
		self::init_uri();
//		self::init_db();
//		self::init_view();
		//self::init_controllor();
		//self::init_method();
		C(self::$uri->c_dir,self::$uri->c_name, self::$uri->m_name, self::$uri->m_arr);
	}

}

Yiee::run($config);