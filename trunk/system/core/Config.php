<?php defined('SYS_PATH') OR die('access error.');
/**
 * 配置类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
 class Config{

	//静态变量保存全局实例
    protected static $_instance = null;
	//保存对象实例
	protected static  $objs = array();
	//程序与系统路径
	protected $paths = array(APP_PATH,SYS_PATH);

	//私有构造x函数，防止外界实例化对象
	private function __construct(){
		$this->_init();			//初始化类
	}
	//私有克隆函数，防止外办克隆对象
    private function __clone() {}

	//静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }


	//系统运行所需的类
	protected function _sys_init(){
		
	}

	//APP自动加载的类
	protected function _auto_init(){
		
	}

 }