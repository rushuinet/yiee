<?php
	/**
	 * 基准类
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	class Benchmark {
		
		//标记点数组
		public $marker = array();

		public function __construct(){
			$this->marker['sys_start'] = SYS_START;
		}

		//添加一个标记点
		public function mark($name){
			$this->marker[$name] = microtime(TRUE);
		}

		/**
		 * 获取运行时间
		 * param	$a 空是系统运行到当前位置时间，$a非空$b为空是系统到$a标记点时间，$a$b都不为空则是两个标记点的运行时间
		 * @E-mail	rushui@qq.com
		 * @author	Rushui
		 */
		public function run_time($a='',$b=''){
			if( empty($a) ){
				return microtime(TRUE) - $this->marker['sys_start'];
			}elseif( !empty($a) && empty($b) ){
				return $this->marker[$a] - $this->marker['sys_start'];
			}else{
				return $this->marker[$b] - $this->marker[$a];
			}
		}
		
	}
