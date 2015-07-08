<?php defined('SYS_PATH') OR die('access error.');
/**
 * 视图基类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class View{

	//模板目录
	protected $_template_dir = '';
	//布局文件
	protected $_layout_file = '';
	//模板输出变量
	protected $_view_data = array();

	/**
	 * 模板变量赋值
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function assign($name,$value=''){
		if(is_array($name)) {
			$this->_view_data   =  array_merge($this->_view_data,$name);
		}else {
			$this->_view_data[$name] = $value;
		}
	}
	
	/**
     * 模板目录
     * @param string $name
     */
    public function template($name=''){
        $this->_template_dir = $name.'/';
    }

	
	/**
	 * 视图布局
	 * @param mixed $name
	 */
	public function layout($name){
		$file_suffix = $this->get_suffix($name);
		$this->_layout_file = $name.$file_suffix;
	}

	/**
	 * 输出视图
	 * param	$__name__	视图文件
	 * param	$__data__	分配的数据
	 * param	$__way__	输出方式(true返回字符串,false直接引入文件)
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function display($__name__,$__data__=array(),$__way__=false){
		if(empty($__name__)){
			return false;
		}
		//路径组装
		$__view_path__ = Yiee::conf('view_path');
		if( empty($__view_path__) ){
			$__view_path__ = APP_PATH.'views/'.$this->_template_dir;
		}else{
			$__view_path__ = IN_PATH.$__view_path__.'/'.$this->_template_dir;
		}
		
		//视图后缀
		$__view_suffix__ = $this->get_suffix($__name__);

		//分配变量
		if(!empty($__data__)){
			$this->assign($__data__);
		}
		
		extract($this->_view_data,EXTR_SKIP);
		
		//引入视图文件
		ob_start ();
		include($__view_path__.$__name__.$__view_suffix__);
		$__view__ = ob_get_contents();
		ob_end_clean();

		//是否有布局文件
		if( empty($this->_layout_file) ){
			$view = $__view__;
		}else{
			$__view__ = trim($__view__) . "\r\n";
			ob_start ();
			include($__view_path__.$this->_layout_file);
			$view = ob_get_contents();
			ob_end_clean ();
		}
		
		//输出方式
		if($__way__===true){
			return $view;
		}else{
			echo $view;
		}
	}
	
	//获取视图文件后缀
	public function get_suffix($name){
		$view_suffix = '';
		$file_type = pathinfo($name, PATHINFO_EXTENSION);
		if( empty($file_type) ){
			$view_suffix = Yiee::conf('view_suffix') ? Yiee::conf('view_suffix') : '.php';
		}
		return $view_suffix;
	}
	
}