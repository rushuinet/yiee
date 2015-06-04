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
			$data = array(
				'title'=>'标题',
				'body'=>'这是一个测试页'
			);
			VIEW('test',$data);
		}

		public function test(){
			$data = array(
				'title'=>'标题2',
				'body'=>'这是另一个测试页'
			);
			echo $this->rs;
			echo '<br />';
			echo Yiee::$uri->complete;
			echo '<br />';
			var_dump(Yiee::$uri->m_arr);
			VIEW('test',$data);
		}
	}