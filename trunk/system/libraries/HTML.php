<?php
/**
 * HTML类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class HTML {
	
	//禁止创建对象
	private function __construct(){}
	
	//a标签
	public static function a($title,$link='#'){
		return '<a href="'.$link.'" title="'.$title.'">'.$title.'</a>';
	}
	
	//文本框
	public static function input($name,$val='',$type='text'){
		return '<input type="'.$type.'" name="'.$name.'" value="'.$val.'">';
	}

}
