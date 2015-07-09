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

/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
if ( ! function_exists('redirect')){
	function redirect($url, $time=0, $msg='') {
		//多行URL地址支持
		$url        = str_replace(array("\n", "\r"), '', $url);
		if (empty($msg))
			$msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
		if (!headers_sent()) {
			// redirect
			if (0 === $time) {
				header('Location: ' . $url);
			} else {
				header("refresh:{$time};url={$url}");
				echo($msg);
			}
			exit();
		} else {
			$str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if ($time != 0)
				$str .= $msg;
			exit($str);
		}
	}
}