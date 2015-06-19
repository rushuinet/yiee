<?php defined('SYS_PATH') OR die('access error.');
/**
 * mysqli 数据库操作类(单例)
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */

class DB_mysqli{
	
	private $links = false;					//数据库链接标识
	//sql对象
	private $fields = '';					//字段
	private $table = '';					//表名
	private $join = '';						//连表
	private $where = '';					//条件
	private $by = '';						//排序
	private $limit = '';					//limit
	//查询结果
	private $res = false;					//查询对象
	private $num_rows;						//行数
	private $last_query; 					//最后查询语句


	//错误处理
	public function error($msg){
		log_msg($msg,__CLASS__);		//此处引用了公共函数
	}
	
	/**
	 * 链接并选择数据库
	 * param	$config	数据库配置信息array('hostname'=>'127.0.0.1','username'=>'root','password'=>'root','database'=>'rushui','char_set'=>'utf8')
	 * param	$old_link	使用已有链接(用于外部系统扩展)
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function connect($config){
		if( is_object($config) ){
			$this->links = $config;
		}else{
			extract($config);
			$db_port = $db_port ? $db_port : '3306';
			$link = mysqli_connect($hostname,$username,$password,$database,$db_port);
			if(!$link){
				$this->error( mysqli_error($link) );
			}
			//设置字符集
			mysqli_set_charset($link,$char_set);
			$this->links = $link;
		}	
	}

	//字段
	public function fields($param){

		$this->del_query();

		if(is_array($param)){
			$this->fields = '`'.implode('`,`',$param).'`';
		}else{
			$this->fields = $param;
		}
		return $this;
	}

	//表名
	public function table($table){

		$this->del_query();

		$table = strval($table);
		if(empty($table)){
			$this->error('表名不正确！');
		}
		$this->table = $table;
		return $this;
	}

	//join
	public function join($table,$on='',$type='left'){

		$this->del_query();

		if(is_array($table)){
			foreach ($table as $v){
				$this->join .= ' '.strtoupper($v[2]) . ' JOIN `'.$v[0].'` ON '.$v[1];
			}
		}else{
			$this->join .= ' '.strtoupper($type) . ' JOIN `'.$table.'` ON '.$on;
		}
		return $this;
	}

	//条件
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

	//条件
	public function by($param){
		$this->del_query();
		if( empty($this->by) ){
			$this->by = ' ORDER BY '.$param;
		}else{
			$this->by .= ','.$param;
		}
		return $this;
	}

	//limit
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

		$sql = 'SELECT '.$fields.' FROM `'.$table.'` '.$this->join.$where.$this->by.$this->limit;
		return $this->query($sql);
	}

	//查询
	public function query($sql=''){
		$this->res = mysqli_query($this->links,$sql);
		if(!$this->res){
			$this->error( $sql . '<br />' . mysqli_error($this->links) );
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
		$this->fields = '';
		$this->table = '';
		$this->join = '';
		$this->where = '';
		$this->by = '';
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

	/**
	 * 插入记录(支持批量插入)
	 * param	$table	表名
	 * param	$data	数据array('name'=>'小王','age'=>18) || array(array('name'=>'小王','age'=>18),array('name'=>'小李','age'=>20))
	 * return	insert_id
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function insert($table,$data=array()){
		//检测是否为二维数组(批量插入)
		if( isset($data[0]) && is_array($data[0])){
			$key = ''; $val = ''; $keys = ''; $vals = ''; $str = '';
			foreach ($data as $dv ){
				if(!is_array($dv)){$this->error( '数据格式不正确.' );}	//不是正确数组返回错误
				foreach ($dv as $k=>$v ){
					$v = mysqli_real_escape_string($this->links,$v);
					$key .= '`'.$k.'`,';
					$val .= '"'.$v.'",';
				}
				$keys = '('.trim($key,',').')';
				$vals .=  '('.trim($val,',').') ,';
				$key = ''; $val = '';
			}
			$sql = "INSERT INTO `".$table."` " . $keys . ' VALUES ' . trim($vals,',');
		}else{
			foreach ($data as $k=>$v ){
				$v = mysqli_real_escape_string($this->links,$v);
				$key[] = '`'.$k.'`';
				$value[] = '"'.$v.'"';
			}
			$keys = implode(',',$key);
			$values = implode(',',$value);
			$sql = "INSERT INTO `".$table."` (".$keys.") VALUES(".$values.")";
		}
		$this->query($sql);
		$this->del_query();
		return mysqli_insert_id($this->links);
	}

	/**
	 * 更新记录
	 * param	$table	表名
	 * param	$data	数据array('name'=>'小王','age'=>18)
	 * param	$where	更新条件(为整数时前面自动加id = )
	 * return	affected_rows
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function update($table,$data,$where){
		foreach($data as $k=>$v){
			$v = mysqli_real_escape_string($this->links,$v);
			$arr[] = "`".$k."`='".$v."'";
		}
		if(is_int($where)){
			$where = '`id` ='.$where;
		}
		$str = implode(",",$arr);
		$sql = "UPDATE `".$table."` SET ".$str." WHERE ".$where;
		$this->query($sql);
		return $this->affected_rows();
	}

	/**
	 * 删除数据
	 * param	$table	表名
	 * param	$where	删除条件(为整数时前面自动加id = )
	 * return	affected_rows
	 * @E-mail	rushui@qq.com
	 * @author	Rushui
	 */
	public function del($table,$where){
		if(is_int($where)){
			$where = '`id` ='.$where;
		}
		$sql = "DELETE FROM	`".$table."` WHERE ".$where;
		$this->query($sql);
		return $this->affected_rows();
	}

	//返回受影响行数
	private  function affected_rows(){
		$this->del_query();
		return mysqli_affected_rows($this->links);
	}
	
	//清空表
	public function truncate($table){
		$this->query('TRUNCATE TABLE `'.$table.'`');
		$this->del_query();
	}
	
	//关闭链接
	public function close(){
		mysqli_close ( $this->links );		
	}

}