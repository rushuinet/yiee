<?php
/**
 * mysqli 数据库操作类(单例)
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

class DB_mysqli{
	
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
		log_msg($msg,'mysqli');
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
			$link = mysqli_connect($hostname,$username,$password,$database,$db_port);
			if(!$link){
				self::error( mysqli_error($link) );
			}
			//设置字符集
			mysqli_set_charset($link,$char_set);
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
			$this->fields = '`'.implode('`,`',$param).'`';
		}else{
			$this->fields = $param;
		}
		return $this;
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
			$this->limit = ' LIMIT '.$limit;
		}
		if($limit > 0 && $offset > 0){
			$this->limit = ' LIMIT '.$offset.','.$limit;
		}
		return $this;
	}

	//拼装sql
	public function get($table='',$where=''){
		$this->del_query();
		if(empty($table)){
			$table = $this->table;
		}
		if(empty($this->fields)){
			$fields = '*';
		}else{
			$fields = $this->fields;
		}
		if( !empty($where) ){
			$this->where($where);
		}
		if( !empty($this->where) ){
			$where = ' WHERE '.$this->where;
		}

		$sql = 'SELECT '.$fields.' FROM `'.$table.'`'.$where.$this->limit;
		return $this->query($sql);
	}

	//查询
	public function query($sql=''){
		$this->res = mysqli_query($this->links,$sql);
		if(!$this->res){
			self::error( $sql . '<br />' . mysqli_error($this->links) );
		}
		$this->last_query = $sql;
		$this->del_sql();
		return $this;
	}

	/**
	 * 插入数据(支持批量插入)
	 * param	$table	表名
	 * param	$data	数据array('name'=>'小王','age'=>18);
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function insert($table,$data=array()){
		//检测是否为二维数组(批量插入)
		if( isset($data[0]) && in_array($data[0])){
			$keys = ''; $vals = ''; $str = '';
			foreach ($data as $dv ){
				foreach ($dv as $k=>$v ){
					$v = mysqli_real_escape_string($this->links,$v);
					$keys .= '`'.$k.'`,';
					$vals .= '"'.$v.'",';
				}
				$str .= '('.trim($keys,',').' VALUES('.trim($vals,',').') ';
				$keys = '';	$vals = '';
			}
			$sql = "INSERT INTO `".$table."` " . $str;
		}else{
			foreach ($data as $k=>$v ){
				$v = mysql_real_escape_string($this->links,$v);
				$key[] = '`'.$k.'`';
				$value[] = '"'.$v.'"';
			}
			$keys = implode(',',$key);
			$values = implode(',',$value);
			$sql = "INSERT INTO `".$table."` (".$keys.") VALUES(".$values.")";
		}
		$this->query($sql);
		return mysqli_insert_id($this->links);
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
			while( $row = mysqli_fetch_assoc($this->res) ) {
				$this->result[] = $row;
			}
		}
		return $this->result;
	}

	//一条记录
	public function result_one(){
		$row = array();
		if( $this->num_rows() > 0){
			$row = mysqli_fetch_assoc($this->res);
		}
		return $row;
	}
	
	//返回最后查询语句
	public function last_query(){
		return $this->last_query;
	}
	
	//返回查询记录数
	public  function num_rows(){
		return mysqli_num_rows($this->res);
	}

	//返回受影响行数
	public  function affected_rows(){
		return mysqli_affected_rows($this->links);
	}
	
	
	//关闭链接
	public function close(){
		mysqli_close ( $this->links );		
	}

}