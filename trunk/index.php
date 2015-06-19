<?php
	/**
	 * 入口文件
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	//设置编码格式
	header("Content-Type:text/html;charset=utf-8");

	ini_set('display_errors', true);
	error_reporting(E_ALL);
	
	// 定义应用目录
	$app_dir = 'application';

	//引入框架文件
	require './system/Yiee.php';
