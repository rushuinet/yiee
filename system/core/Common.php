<?php defined('SYS_PATH') OR die('access error.');
/**
 * 通用函数
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */


//日志信息
function log_msg($msg,$type='err'){
	die($type.' : '.$msg);
}

//实例化model
function M($name){
	$model_name = $name.'_model';
	require_once(APP_PATH.'models/'.$model_name.'.php');
	$obj = new $model_name();
	return $obj;
}

//加载视图
function VIEW($name,$data=array(),$way=false){
	$path = APP_PATH.'views/'.$name.'.php';
	extract($data);
	if($way===true){
		ob_start ();
		require_once($path);
		$data = ob_get_contents();
		ob_end_clean ();
		return $data;
	}else{
		require_once($path);
	}
	
}

/**
 * 引用超级对象
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
function &get_instance(){
	return Controller::get_instance();
}


//返回文件名: dir/name 返回name
//用于返回不带扩展名文件的名称
function get_filenmae($name){
	$arr = explode('/',$name);
	return end($arr);
}

/**
 * 转换字符串： dir/name 返回dir_name
 * param	$name 要转换的字符
 * param	$a 要转换的字符
 * param	$b 转换成为的字符
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
function conver_str($str,$a='_',$b='/'){
	return implode($a, explode($b,$str) );
}

//判断是否为https
function is_https(){
	if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
		return TRUE;
	}elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
		return TRUE;
	} elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
		return TRUE;
	}
	return FALSE;
}

/**
 * 变量为空时的返回	
 * param	$value		变量值
 * param	$str		为空时的值
 * param	$str1		不为空时的值(如为空则返回本身)
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
function is_set($value,$str='',$str1=''){
	if(empty($value)){
		return $str;
	}else{
		return $str1==''?$value:$str1;
	}
}

//转义字符
function daddslashes($str){
	return (!get_magic_quotes_gpc())?addslashes($str):$str;
}

