<?php defined('SYS_PATH') OR die('access error.');
/**
 * URI类（单例）
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class URI {
	//静态变量保存全局实例
    private static $_instance = null;

	private $complete = '';			//完整URI（framework/index.php/dir/c/m/add/new.html?id=5&s=yy）
	private $uri_all = '';			//完整的URI参数段（dir/c/m/?id=5&s=yy）
	private $uri_str = '';			//URI段字符串（dir/c/m）
	private $uri_arr = array();		//URI段组成的数组( array(dir,c,m) )
	private $in_name = '';			//入口文件名称（index.php）
	private $in_dir = '';			//入口文件所在目录（framework/）
	private $c_dir = '';				//控制器所在目录（dir/）
	private $c_name = '';			//控制器名称（c）
	private $m_name = '';			//方法名( m )
	private $m_arr = array();		//方法参数值( add,new )
	
	//私有构造函数，防止外界实例化对象
	private function __construct(){
		$this->_init();			//初始化类
	}
	//私有克隆函数，防止外办克隆对象
    private function __clone() {}

	//静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

	
	//初始化URI类
	private function _init(){

		$arr = $this->_get_uri();	//取URI段
		$this->complete = $arr['complete'];
		$this->uri_all = $arr['uri_all'];
		$this->uri_str = $arr['uri_str'];
		$this->uri_arr = $arr['uri_arr'];
		$this->in_name = $arr['in_name'];
		$this->in_dir = $arr['in_dir'];

		$mc = $this->_get_cm($arr['uri_arr']);
		$this->c_dir = $mc['c_dir'];
		$this->c_name = $mc['c_name'];
		$this->m_name = $mc['m_name'];
		$this->m_arr = $mc['m_arr'];
	}


	//处理URI
	private function _get_uri(){
		//准备空数据
		$data = array(
			'complete'=>'',
			'uri_all'=>'',
			'uri_str'=>'',
			'uri_arr'=>array(),
			'in_name'=>'',
			'in_dir'=>''
		);
		if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) ){
			return $data;
		}
		
		//完整URI
		$data['complete'] = ltrim($_SERVER['REQUEST_URI'],'/');
		//完整的URI参数段
		$data['uri_all'] = trim($_SERVER['REQUEST_URI'],dirname($_SERVER['SCRIPT_NAME']).$_SERVER['SCRIPT_NAME']);
		//入口文件名字
		$data['in_name'] = basename($_SERVER['SCRIPT_NAME']);
		//入口文件目录
		$data['in_dir'] = trim(dirname($_SERVER['SCRIPT_NAME']),'/').'/';
		

		$uri = parse_url($_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? $uri['path'] : '';

		if (isset($_SERVER['SCRIPT_NAME'][0])){
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0){
				$uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			}elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0){
				$uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}

		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0){
			$query = explode('?', $query, 2);
			$uri = $query[0];
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}else{
			$_SERVER['QUERY_STRING'] = $query;
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		if ($uri === '/' OR $uri === ''){
			$data['uri_str'] = '/';
		}
		//处理成想要的URI段
		$uris = array();
		$tok = strtok($uri, '/');
		while ($tok !== FALSE){
			if (( ! empty($tok) OR $tok === '0') && $tok !== '..'){
				$uris[] = $tok;
			}
			$tok = strtok('/');
		}
		
		$data['uri_arr'] = $uris;
		$data['uri_str'] = implode('/', $uris);

		return $data;
	}
	
	//控制器目录及方法参数处理
	private function _get_cm($uri_arr){
		$data = array('c_dir' => '','c_name' => '','m_name' => '','m_arr' => array());
		$c_dir = '';
		$i = 0;
		$a = 0;
		foreach ($uri_arr as $k=>$v){

			if( file_exists(APP_PATH.'controllers/'.$v.'.php') && $i === 0 ){
				$i = $k+1;
				$data['c_name'] = $v;
				$data['m_name'] = $uri_arr[$i];
			}
			if($i>0){
				if($a>1){
					$m_arr[] = $v;
				}
				$a++;
			}else{
				$c_dir .= $v.'/';
			}
		}

		$data['c_dir'] = $c_dir;
		$data['m_arr'] = $m_arr;

		return $data;
	}

	//重载(属性设置)
	public function __set($name,$value){
		if($name =='c_name' || $name=='m_name'){
			$this->$name = $value;
		}
	}

	//重载(属性获取)
	public function __get($name){
		return $this->$name;
	}
}
