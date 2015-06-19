<?php defined('SYS_PATH') OR die('access error.');
	/**
	 * 默认控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */

	class index extends MY_Controller{

		//构造方法
		public function __construct(){
			parent::__construct();
			$this->benchmark->mark('aaa');
		}

		public function index(){

			$re = $this->db->table('main_keylib')->where(array('status'=>'1'))->fields(array('id','title'))->limit(2,3)->get();
			$row = $re->result();
			$sql = $re->num_rows();

//			$arr = array('title'=>'小李323','ckey'=>'test144');
			$re = $this->db->del('main_keylib','id >79');
			echo $this->rs();
			
			var_dump($row,$this->db->last_query());

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
			echo $this->uri->complete;
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
			echo $this->benchmark->run_time('aaa');
		}
		//uri测试
		public function url(){
			echo 'complete: '.$this->uri->complete;
			echo '<br />';
			echo 'url: '.$this->uri->url;
			echo '<br />';
			echo 'domain: '.$this->uri->domain;
			echo '<br />';
			echo 'base_url: '.$this->uri->base_url;
			echo '<br />';
			echo 'web_url: '.$this->uri->web_url;
			echo '<br />';
			echo 'uri_all: '.$this->uri->uri_all;
			echo '<br />';
			echo 'uri_str: '.$this->uri->uri_str;
			echo '<br />';
			echo 'in_name: '.$this->uri->in_name;
			echo '<br />';
			echo 'in_dir: '.$this->uri->in_dir;
			echo '<br />';
			echo 'c_dir: '.$this->uri->c_dir;
			echo '<br />';
			echo 'c_name: '.$this->uri->c_name;
			echo '<br />';
			echo 'm_name: '.$this->uri->m_name;
			echo '<br />';
			'uri_arr: '.var_dump($this->uri->uri_arr);
			echo '<br />';
			'm_arr: '.var_dump($this->uri->m_arr);
			echo '<br />';
			echo $this->uri->site_url('adf/sdfsdf?a=ee&b=66');
			echo '<br />';
			echo 'SERVER_NAME: '.$_SERVER['SERVER_NAME'];

		}

		public function url_test(){
			$data = array(
				'title'=>'url_test',
				'body'=>'url_test',
				'uri'=>$this->uri	
			);
			$this->load->view('url_test',$data);
		}

		public function loader(){
			//$load = Loader::getInstance();
			Loader::inc('aaa');
		}

	}