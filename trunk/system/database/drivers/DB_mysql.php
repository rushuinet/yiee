<?php
/**
 * mysql 数据库操作类(单例)
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

class DB_mysql{
	
    private static $_instance = null;		//静态变量保存全局实例

	private $links = false;					//数据库链接标识
	//sql对象
	private $table = '';					//表名
	private $fields = '';					//字段
	private $where = '';					//条件
	private $limit = '';					//limit
	//查询结果
	private $res = false;					//查询对象
	private $num_rows;						//行数
	private $last_query; 					//最后查询语句


	//私有构造函数，防止外界实例化对象
	private function __construct(){}
	//私有克隆函数，防止外办克隆对象
    private function __clone() {}

	//静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

	//错误处理
	public static function error($msg){
		log_msg($msg,'mysql');
	}
	
	/**
	 * 链接并选择数据库
	 * param	$config	数据库配置信息array('hostname'=>'127.0.0.1','username'=>'root','password'=>'root','database'=>'rushui','char_set'=>'utf8')
	 * param	$old_link	使用已有链接(用于外部系统扩展)
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function connect($config,$old_link=''){
		if($old_link){
			$this->links = $old_link;
		}else{
			extract($config);
			$db_port = $db_port ? $db_port : '3306';
			if(!($link = mysql_connect($hostname,$username,$password))){
				self::error( mysql_error() );
			}
			if(!mysql_select_db($database,$link)){//mysql_select_db选择库的函数
				self::error( mysql_error() );
			}
			//设置字符集
			mysql_query("set names ".$char_set);
			$this->links = $link;
		}	
	}

	//设置表名
	public function table($table){

		$this->del_query();

		$table = strval($table);
		if(empty($table)){
			self::error('表名不正确.');
		}
		$this->table = $table;
		return $this;
	}

	//设置字段
	public function fields($param){

		$this->del_query();

		if(is_array($param)){
			$this->fields = explode(',',$param);
		}else{
			$this->fields = $param;
		}
	}

	//设置条件
	public function where($param){

		$this->del_query();

		if(is_array($param)){
			foreach ($param as $k=>$v ){
				if( empty($this->where) ){
					$this->where .=  '`'.$k.'` = "'.$v.'"';
				}else{
					$this->where .=  ' AND `'.$k.'` = "'.$v.'"';
				}
			}
		}else{
			$this->where .= $param;
		}
		return $this;
	}

	//设置limit
	public function limit($limit='',$offset=''){

		$this->del_query();

		$limit = intval($limit);
		$offset = intval($offset);

		if($limit > 0 && $offset == 0){
			$this->limit = 'LIMIT '.$limit;
		}
		if($limit > 0 && $offset > 0){
			$this->limit = 'LIMIT '.$offset.','.$limit;
		}
	}

	//拼装sql
	public function get($table='',$where=''){
		$this->del_query();
		if(empty($table)){
			$table = $this->table;
		}
		if(empty($this->fields)){
			$fields = '*';
		}
		if( !empty($where) ){
			$this->where($where);
		}
		if( !empty($this->where) ){
			$where = ' WHERE '.$this->where;
		}

		$sql = 'SELECT '.$fields.' FROM '.$table.$where.$this->limit;
		return $this->query($sql);
	}

	//查询
	public function query($sql=''){
		$this->res = mysql_query($sql,$this->links);
		if(!$this->res){
			self::error( $sql . '<br />' . mysql_error($this->links) );
		}
		$this->last_query = $sql;
		$this->del_sql();
		return $this;
	}


	//删除查询对象
	private function del_query(){
		$this->res = false;
	}

	//删除sql对象
	private function del_sql(){
		$this->table = '';
		$this->fields = '';
		$this->where = '';
		$this->limit = '';
	}

	//结果集
	public function result(){
		$this->result = array();
		if( $this->num_rows() > 0){
			while( $row = mysql_fetch_assoc($this->res) ) {
				$this->result[] = $row;
			}
		}
		return $this->result;
	}

	//一条记录
	public function result_one(){
		$this->result = array();
		if( $this->num_rows() > 0){
			$row = mysql_fetch_row($this->res);
		}
		return $row;
	}
	
	//返回最后查询语句
	public function last_query(){
		return $this->last_query;
	}
	
	//返回查询记录数
	public  function num_rows(){
		return mysql_num_rows($this->res);
	}

	//返回受影响行数
	public  function affected_rows(){
		return mysql_affected_rows($this->res);
	}
	
	//返回最后一个查询中自动生成的 ID
	public function insert_id(){
		return mysql_insert_id($this->links);
	}
	
	//关闭链接
	public function close(){
		mysql_close ( $this->links );		
	}

}