<?php defined('SYS_PATH') OR die('access error.');
/**
 * APP配置
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

return array(
	//数据库
	'database'=>array(
		//默认数据库
		'default'=>array(
			'hostname'=>'127.0.0.1',		//数据库主机
			'db_port'=>'3306',				//端口
			'username'=>'root',				//用户名
			'password'=>'root',				//密码
			'database'=>'test',			//数据库名
			'char_set'=>'utf8',				//字符集
			'dbdriver'=>'mysqli',			//链接方式
		),
	),
	//默认控制器
	'default_controller'=>'index',
	//默认方法
	'default_method'=>'index',
	//视图路径(为空调用APP_PATH/views)
	'view_path'=>'',
	//扩展核心类前缀
	'class_prefix'=>'MY_',
	//url后缀
	'url_suffix'=>'.html',

	
);