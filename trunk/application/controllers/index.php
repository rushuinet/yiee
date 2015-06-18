<?php defined('SYS_PATH') OR die('access error.');
	/**
	 * 默认控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */

	class index extends Controller{

		//构造方法
		public function __construct(){
			parent::__construct();
		}

		public function index(){
			$query = trim($_SERVER['REQUEST_URI'],dirname($_SERVER['SCRIPT_NAME']).$_SERVER['SCRIPT_NAME']);	//将当前文件后的URI做为参数
			$x = strtolower($query); //转换为小写
			ECHO '<BR>';
			var_dump($x,$_SERVER['REQUEST_URI']);
			ECHO '<BR>';
			var_dump($_GET);
			ECHO '<BR>';
			$uri = parse_url($_SERVER['REQUEST_URI']);
			var_dump($uri);
			ECHO '<BR>';
			var_dump($_SERVER['SCRIPT_NAME']);
			ECHO '<BR>';
			var_dump(dirname($_SERVER['SCRIPT_NAME']));
		}

		public function test(){
			$data = array(
				'title'=>'标题',
				'body'=>'这是一个测试页'
			);
			VIEW('test',$data);
		}

		public function url(){
			echo 'complete: '.Yiee::$uri->complete;
			echo '<br />';
			echo 'url: '.Yiee::$uri->url;
			echo '<br />';
			echo 'uri_all: '.Yiee::$uri->uri_all;
			echo '<br />';
			echo 'uri_str: '.Yiee::$uri->uri_str;
			echo '<br />';
			echo 'in_name: '.Yiee::$uri->in_name;
			echo '<br />';
			echo 'in_dir: '.Yiee::$uri->in_dir;
			echo '<br />';
			echo 'c_dir: '.Yiee::$uri->c_dir;
			echo '<br />';
			echo 'c_name: '.Yiee::$uri->c_name;
			echo '<br />';
			echo 'm_name: '.Yiee::$uri->m_name;
			echo '<br />';
			'uri_arr: '.var_dump(Yiee::$uri->uri_arr);
			echo '<br />';
			'm_arr: '.var_dump(Yiee::$uri->m_arr);
		}
	}