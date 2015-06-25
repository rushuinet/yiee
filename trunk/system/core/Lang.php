<?php defined('SYS_PATH') OR die('access error.');
/**
 * 语言类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
 class Lang{

	/**
	 * 输出语言
	 * param	$key 输出的KEY
	 * param	$index 所在的索引
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function line($key,$index=''){
		if($index == ''){
			return isset(Yiee::$lang[$key]) ? Yiee::$lang[$key] : false;
		}
		return isset(Yiee::$lang[$index],Yiee::$lang[$index][$key]) ? Yiee::$lang[$index][$key] : false;
	}



 }