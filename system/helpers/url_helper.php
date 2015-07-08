<?php
/**
 * URL辅助函数
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

//网站根路径
if ( ! function_exists('base_url')){
	function base_url($uri=''){
		return Yiee::$objs['uri']->base_url($uri);
	}
}


//网站路径(资源引用)
if ( ! function_exists('web_url')){
	function web_url($uri=''){
		return Yiee::$objs['uri']->web_url($uri);
	}
}


//站点入口((应该程序引用)
if ( ! function_exists('site_url')){
	function site_url($uri=''){
		return Yiee::$objs['uri']->site_url($uri);
	}
}

