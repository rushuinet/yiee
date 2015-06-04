<?php
/**
 * 通用函数
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

/**
 * 实例化控制器
 * param	$dir				类所在目录
 * param	$name				类名
 * param	$method				方法名
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
function C($dir,$name, $method){
	require_once(SYS_PATH.'core/Controller.php');
	require_once(APP_PATH.'controllers/'.$dir.$name.'.php');
	$obj = new $name();
	$obj->{$method}();
}
//实例化model
function M($name){
	$model_name = $name.'_model';
	require_once(SYS_PATH.'core/Model.php');
	require_once(APP_PATH.'models/'.$model_name.'.php');
	$obj = new $model_name();
	return $obj;
}

//加载视图
function VIEW($name,$data=array()){
	extract($data);
	require_once(APP_PATH.'views/'.$name.'.php');
}

//日志信息
function log_msg($type,$msg){
	die($type.' : '.$msg);
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

