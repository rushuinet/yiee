# yiee
- 一个简单的php框架！

### 使用方法：
```
get clone https://github.com/rushuinet/yiee.git
```
设置为项目根目录
打开localhost即可

### 案例文件：
application/controllers/dirtest/example.php
### 部分使用展示
```
			//基本使用
			echo $this->rs();		//MY_Controller扩展控制器中的方法
			//** //数据库操作测试
			$re = $this->db->table('main_keylib')->where(array('status'=>'1'))->fields(array('id','title'))->limit(5)->get();
			//$row = $re->result();
			$row = $re->row(3);
			$sql = $re->num_rows();
			$arr = array('title'=>'小李323','ckey'=>'test144');
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
			/** //加载类库
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
      
			//URI
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
      
			//基准类测试
			echo $this->benchmark->run_time();
			echo '<br />';
			echo $a;
			echo '<br />';
			echo $this->benchmark->run_time('aaa');
 ```
