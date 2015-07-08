<?php defined('SYS_PATH') OR die('access error.');
	/**
	 * 默认控制器
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */

	class example extends MY_Controller{

		//构造方法
		public function __construct(){
			parent::__construct();
			$this->benchmark->mark('aaa');
			//$this->load->database();
		}

		public function index(){
			var_dump($_SERVER['QUERY_STRING']);
			echo '<br />';
			var_dump($_GET);
			echo '<br />';
			//echo $this->rs();		//MY_Controller扩展控制器中的方法
			//** //数据库操作测试
			$re = $this->db->table('main_keylib')->where(array('status'=>'1'))->fields(array('id','title'))->limit(2,3)->get();
			$row = $re->result_one();
			$sql = $re->num_rows();

//			$arr = array('title'=>'小李323','ckey'=>'test144');
			$re = $this->db->del('main_keylib','id >79');
			
			var_dump($row,$this->db->last_query());
			echo '<br />';
			// **/
			/** //配置测试
			$this->load->config('autoload');
			var_dump($this->config->item('autoload'));
			$this->config->set_item('autoload','adsfadsf');
			var_dump($this->config->item('autoload'));
			// **/
			/** //语言测试
			//$this->load->lang('sys/add');
			$this->load->lang('config');
			var_dump(Yiee::$lang);
			var_dump($this->lang->line('sys_add'));
			// **/
			//** //加载类库
			$this->load->library('cc/bbb');
			$this->bbb->dss();
			$this->load->library('Validation','vali');
			$this->vali->check();
			// **/
			/** //helper测试(与系统同名，优先使用APP下文件)
			$this->load->helper('sys');
			fugaitest();
			$this->load->helper('app');
			apptest();
			// **/
			//** //加载视图测试
			$data = array(
				'title'=>'标题',
				'body'=>'这是一个测试页'
			);
			$this->load->view('test',$data);
			// **/
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
			$this->load->view('test',$data);
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
			echo 'base_url():'.$this->uri->base_url();
			echo '<br />';
			echo 'web_url():'.$this->uri->web_url();
			echo '<br />';
			echo 'site_url():'.$this->uri->site_url();
			echo '<br />';
			echo 'site_url("adf/sdfsdf?a=ee&b=66"):'.$this->uri->site_url('adf/sdfsdf?a=ee&b=66');
			echo '<br />';
			echo 'SERVER_NAME: '.$_SERVER['SERVER_NAME'];

		}
		//$this->uri->site_url()测试
		public function url_test(){
			echo $this->uri->site_url('sdfdsf/substr/');
			echo '<br />';
			echo $this->uri->site_url('sdfdsf/substr/sadsa');
			echo '<br />';
			if(isset($_GET['aa'])){
				$body = $_GET['aa'];
			}else{
				$body = 'url_test';
			}
			$data = array(
				'title'=>'url_test',
				'body'=> $body,
				'uri'=>$this->uri	
			);
			$this->load->view('test',$data);
		}
		
		//输出配置信息
		public function echo_arr(){
			ob_start ();
			echo "<?php \r\n";
			echo 'return ';
			var_export(Yiee::$config);
			echo ';';
			$data = ob_get_contents();
			ob_end_clean ();
			file_put_contents('./tmp/text.php',$data);
		}

		public function viewtest(){
			$this->assign('title','测试布局');
			$this->assign('body','这是一个测试网页内容的例子。。。。');
			$this->assign('run_time',$this->benchmark->run_time());
			$this->layout('layout');
			$this->load->view('view_test');
		}

		public function vali(){

			$this->load->library('Validation','vali');

			if (isset($_POST['send'])) {
				$b = $this->vali->check(array(
					//'username'=>array('s6-10|n6-10|r/^[1]{3}$/|s5,','用户名格式不正确'),
					'password'=>array('s2,','密码格式不正确'),
					'repeat_password'=>array('fpassword|n10,','密码确认格式不正确'),
					//'email'=>array('e&s10,|n10,','Email格式不正确'),
					//'remark'=>array('n10&s10','备注不能为空'),
					//'phone'=>array('s10,','电话格式不正确！！！'),
					//'remark'=>array('_check','8959','备注格式不正逗趣儿 '),//外部函数，函数名前名必须加上_
					//'remark'=>array(new B(),'test','8959','备注格式不正逗趣儿 '),
				), $_POST);
				if(isset($b['rs_status'])) {
					echo $b['rs_error'] .'<br />';
				}
				var_dump($b);
			}
			$this->display('vali');
		}
	}