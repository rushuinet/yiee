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

/**
 * 类加载
 * param	$class 类名
 * param	$directory 所在目录
 * param	$param 类的别名
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
function &load_class($class, $directory = 'libraries', $param = NULL){
	static $_classes = array();
	//如果已经存在刚返回该对象
	if (isset($_classes[$class])){
		return $_classes[$class];
	}

	//与系统核心类名称一样可重写系统核心类
	foreach (array(APP_PATH, SYS_PATH) as $path) {
		if (file_exists($path.$directory.'/'.$class.'.php')){
			if (class_exists($class, FALSE) === FALSE){
				require_once($path.$directory.'/'.$class.'.php');
			}
		}
	}
	$name = FALSE;
	// 加载APP扩展核心类
	if (file_exists(APP_PATH.$directory.'/'.'MY_'.$class.'.php')){
		$name = 'MY_'.$class;
		if (class_exists($name, FALSE) === FALSE){
			require_once(APP_PATH.$directory.'/'.$name.'.php');
		}
	}

	// 没能找到类
	if ($name === FALSE){
		//set_status_header(503);
		echo 'Unable to locate the specified class: '.$class.'.php';
		exit(5); // EXIT_UNK_CLASS
	}

	// 将类名称加载到数组中
	is_loaded($class);

	$_classes[$class] = isset($param) ? new $name($param) : new $name();
	return $_classes[$class];
}


//返回已经加载类
function &is_loaded($class = ''){
	static $_is_loaded = array();
	if ($class !== ''){
		$_is_loaded[strtolower($class)] = $class;
	}
	return $_is_loaded;
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

