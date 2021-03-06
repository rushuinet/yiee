<?php
/**
 * 启动引擎
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

//系统运行开始时间
define('SYS_START', microtime(TRUE));
//版本号
define('YIEE_VERSION', '1.0.1');
//系统目录
define('SYS_PATH',dirname(__FILE__).'/');
//APP目录
define('APP_PATH',str_replace(array('\\','/'),DIRECTORY_SEPARATOR,dirname($_SERVER['SCRIPT_FILENAME']).'/'.$app_dir.'/'));
//入口文件目录
define('IN_PATH',str_replace(array('\\','/'),DIRECTORY_SEPARATOR,dirname($_SERVER['SCRIPT_FILENAME']).'/'));

//环境配置
switch (ENVIRONMENT) {
	//开发
	case 'development':				
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;
	//测试与生产
	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>=')) {
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		} else {
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;
	
	default:
		die( '必需定义环境！' );
}

class Yiee{
	
	public static $config=array();			//配置
	public static $objs=array();			//保存系统启动对象实例
	public static $lang=array();			//语言

	//加载类
	private static function start(){
		require_once(SYS_PATH.'core/Loader.php');
		$obj = new Loader();
		self::$objs['load'] = $obj;
		
	}
	
	//初始化核心类
	private static function _init(){
		self::$objs['benchmark'] = new benchmark();
		self::$objs['config'] = new Config();
		self::$objs['lang'] = new Lang();
		self::$objs['uri'] = URI::getInstance();
		self::$objs['view'] = new View();
	}

	
	//初始化控制器
	private static function _init_controller(){
		$uri = self::$objs['uri'];
		$name = ucfirst($uri->c_name);
		if( file_exists(APP_PATH.'controllers/'.$uri->c_dir.$name.'.php') ){
			require_once(APP_PATH.'controllers/'.$uri->c_dir.$name.'.php');
			$obj = new $name();
			if( method_exists($obj,$uri->m_name) ){
				call_user_func_array(array($obj, $uri->m_name), $uri->m_arr );die;
			}
		}
		show_404();		
	}
	
	/**
	 * 输出配置项(与$this->config->item()相同)
	 * param	$key 输出的KEY
	 * param	$index 所在的索引
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public static function conf($key,$index=''){
		if($index == ''){
			return isset(self::$config[$key]) ? self::$config[$key] : false;
		}
		return isset(self::$config[$index],self::$config[$index][$key]) ? self::$config[$index][$key] : false;
	}
	
	//运行初始化
	public static function run(){
		self::start();
		self::_init();
		self::_init_controller();
	}

}
//运行框架
Yiee::run();