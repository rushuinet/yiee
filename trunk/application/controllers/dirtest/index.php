<?php
	/**
	 * 默认控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */

	class index extends Controller{

		//构造方法
		public function __construct(){}

		public function index(){

			$re = DB::table('main_keylib')->where(array('status'=>'1'))->fields(array('id','title'))->limit(2,3)->get();
			$row = $re->result();
			$sql = $re->num_rows();

//			$arr = array('title'=>'小李323','ckey'=>'test144');
//			$re = DB::del('main_keylib','id >118');
			
			var_dump($re,DB::last_query());

			$data = array(
				'title'=>'标题',
				'body'=>'这是一个测试页'
			);
			VIEW('test',$data);
		}

		public function test($a='',$b=''){
			$data = array(
				'title'=>'标题2',
				'body'=>'这是另一个测试页'
			);

			echo $a;
			echo '<br />';
			echo $b;
			echo '<br />';
			echo $this->rs;
			echo '<br />';
			echo Yiee::$uri->complete;
			echo '<br />';
			var_dump($_GET);
			VIEW('test',$data);
		}
	}