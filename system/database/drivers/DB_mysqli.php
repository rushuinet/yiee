<?php
/**
 * mysqli 数据库操作类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

class DB_mysqli{
	
	private $links;			//数据库链接标识
	private $res;			//查询对象
	private $num_rows;		//行数
	private $last_query;	//最后查询语句

	//构造方法
	public function __construct(){}

	//错误处理
	public static function error($msg){
		log_msg('mysqli',$msg);
	}
	
	/**
	 * 链接并选择数据库
	 * param	$config	数据库配置信息array('hostname'=>'127.0.0.1','username'=>'root','password'=>'root','database'=>'rushui','char_set'=>'utf8')
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function connect($config){
		extract($config);
		$db_port = $db_port ? $db_port : '3306';
		$link = mysqli_connect($hostname,$username,$password,$database,$db_port);
		if(!$link){
			self::error( mysqli_error($link) );
		}
		//设置字符集
		mysqli_set_charset($link,$char_set);
		$this->links = $link;
	}

	//查询
	public function query($sql){
		$this->res = mysqli_query($dbconnection, $this->links);
		if(!$this->res){
			self::error( mysqli_error($sql) );
		}
		$this->last_query = $sql;
		$this->num_rows = mysqli_num_rows($this->res);
		return $this;
	}

	//结果集
	public function result(){
		$getData = array();
		if( $this->num_rows > 0){
			while( $row = mysqli_fetch_assoc($this->res) ) {
				$getData[] = $row;
			}
		}
		return $getData;
	}
	
	//返回最后查询语句
	public function last_query(){
		return $this->last_query;
	}

}