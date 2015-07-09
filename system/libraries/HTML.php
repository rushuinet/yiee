<?php
/**
 * HTML类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class HTML {

	public static function a($title,$link='#'){
		return '<a href="'.$link.'" title="'.$title.'">'.$title.'</a>';
	}

	public static function input($name,$val='',$type='text'){
		return '<input type="'.$type.'" name="'.$name.'" value="'.$val.'">';
	}

}
