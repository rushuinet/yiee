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
	}