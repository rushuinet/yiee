<?php defined('SYS_PATH') OR die('access error.');
/**
 * APP配置
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
return array(
	//默认控制器
	'default_controller'=>'index',
	//默认方法
	'default_method'=>'index',
	//视图路径(默认为APP_PATH/views)
	'view_path'=>'tpl',
	//view后缀(为空则为.php)
	'view_suffix'=>'.html',
	//扩展核心类前缀
	'class_prefix'=>'MY_',
	//url后缀
	'url_suffix'=>'.html',
	//默认语言
	'default_lang'=>'zh-cn',
	//缓存目录(默认为APP_PATH/cache)
	'cache_path'=>'',
	
);