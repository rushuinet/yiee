<?php
	/**
	 * 默认控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */

	class index extends Controller{

		//构造方法
		public function __construct(){
			parent::__construct();
			Yiee::$benchmark->mark('aaa');
		}

		public function index(){

			$re = DB::table('main_keylib')->where(array('status'=>'1'))->fields(array('id','title'))->limit(2,3)->get();
			$row = $re->result();
			$sql = $re->num_rows();

//			$arr = array('title'=>'小李323','ckey'=>'test144');
//			$re = DB::del('main_keylib','id >118');
			
			var_dump($re,$this->db->last_query());

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
			echo Yiee::$uri->complete;
			echo '<br />';
			var_dump($_GET);
			VIEW('test',$data);
		}
		
		//基准类测试
		public function run_time($a){
			//var_dump($this->benchmark);
			echo $this->benchmark->run_time();
			echo '<br />';
			echo $a;
			echo '<br />';
			echo Yiee::$benchmark->run_time('aaa');
		}
		

	}