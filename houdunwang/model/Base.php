<?php

namespace houdunwang\model;

use Exception;
use PDO;

class Base
{		//定义属性
	private static $pdo = null;
	//需要操作的数据表
	protected      $table;
	//sql语句的where条件
	protected      $where;
	//指定查询的字段
	protected      $field = '';
	//sql语句的排序
	protected      $order;
	public function __construct ( $class )
	{
		//获取数据表名方式一：
		//strtolower()将大写转为小写
		//ltrim()函数移除字符串左侧的空白字符或其他预定义字符
		//strrchr() 函数查找字符串在另一个字符串中最后一次出现的位置，并返回从该位置到字符串结尾的所有字符。
		//$this->table = strtolower (ltrim (strrchr($class,'\\'),'\\'));
		//获取数据表名方式二：
		//explode()把字符串转为数组
		$info          = explode ( '\\' , $class );
		$this -> table = strtolower ( $info[ 2 ] );
		//p($this->table);
		//1.连接数据库
		//is_null()检测传入值【值，变量，表达式】是否是null,只有一个变量定义了，且它的值是null，它才返回TRUE . 其它都返回 FALSE 【未定义变量传入后会出错！】.
		if ( is_null ( self ::$pdo ) ) {
			$this -> connect ();
		}
	}

	//连接数据库
	private function connect ()
	{
		try {
			//连接信息：数据库类型：mysql 主机地址：host  数据库名： dbname
			$dsn        = c ( 'database.driver' ) . ":host=" . c ( 'database.host' ) . ";dbname=" .
						  c ( 'database.dbname' );
			//数据库用户名
			$user       = c ( 'database.user' );
			//数据库密码
			$password   = c ( 'database.password' );
			self ::$pdo = new PDO( $dsn , $user , $password );
			//字符集
			self::$pdo->query ('set names '.c('database.charset'));
			//设置错误属性
			self::$pdo->setAttribute (PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		} catch ( Exception $e ) {
			exit( $e -> getMessage () );
		}
	}

	//获取数据库中主键为1的数据（单独的一条数据依靠主键查找）
	public function find ( $pk )
	{
		//获取查找数据表的主键
		$priKey = $this -> getPriKey ();
		//如果他成立用自己本身，不成立用星
		$this->field  = $this->field ? : '*';
		//原sql语句
		//$sql = "select * from student where id=1";
		$sql = "select {$this->field} from {$this->table} where {$priKey}={$pk}";
		$res = $this -> q ( $sql );
		return current ( $res );
	}

	//查找单独的一条数据
	public function first ()
	{
		//原生sql 语句
		//$sql = "select * from student where name='赵虎'";
		//如果他成立用自己本身，不成立用星
		$this->field  = $this->field ? : '*';
		//组合sql语句
		$sql  = "select {$this->field} from {$this->table} {$this->where}";
		//找出来之后是一个二维数组
		$data = $this -> q ( $sql );
		//p($data)
		//current()去掉外层
		return current ( $data );
	}

	//查找指定的字段（换星）
	public function field ( $field )
	{
		//换星（不换星的时候走默认值）
		$this->field = $field;
		return $this;
	}

	//where条件语句
	public function where ( $where )
	{
		//拼接查询语句，where后有空格
		$this -> where = 'where ' . $where;
		//where返回一个this才能接
		return $this;
	}

	//查找数据表中的所有数据
	public function getAll ()
	{
		//如果他成立用自己本身，不成立用星
		$this->field  = $this->field ? : '*';
		//原生sql语句
		//$sql = "select * from student";
		//所有的数据表统一走一个属性（table属性）
		$sql = "select {$this->field} from {$this -> table}  {$this->where}";
		//p($sql);die;
		//返回所有数据的一个数组数据
		return $this -> q ( $sql );
	}

	//获取主键的名称
	public function getPriKey ()
	{
		//查看表的结构
		$sql = "desc {$this->table}";
		//执行sql语句
		$res = $this -> q ( $sql );
		//p($res);
		//使用foreach遍历二维数组
		foreach ( $res as $k => $v ) {
			//把键值相对应的值赋给$priKey
			if ( $v[ 'Key' ] == 'PRI' ) {
				$priKey = $v[ 'Field' ];
				break;
			}
		}
			//返回键值
		return $priKey;
	}
	//排序
	public function order($order){
		//将$order转为数组
		$order=explode (',',$order);
		//p($order);
		//$this->order=$order;
		//order与by拼接，order by的右边和$order[1]左边都加了空格
		$this->order='order by '.$order[0]." $order[1]";
		//$sql="select*from student where age>30 order by age desc";
		//$sql = "select {$this->field} from {$this -> table}  {$this->where} {$this->order}";
		//返回对象，链式操作,从app index那执行最后的getAll
		return $this;

	}


	//insert写入数据
	public function insert($data){
		//p($data);die;
		//字段名称
		//定义一个空的字符串用来接收最后的输出数据
		$field = '';
		//给定的值
		//这是用来存后边的值
		$value = '';
		foreach($data as $k=>$v){
			//所有下标拼成用逗号隔开的字符串
			$field .= $k . ',';
			//是int型的不要引号，不是的要加引号
			if(is_int ($v)){
				$value .= $v . ',';
			}else{
				//解析变量“”
				$value .= "'$v'" . ',';
			}
		}
		//将最右侧的逗号去掉
		$field = rtrim ($field,',');
		//p($field);die;
		$value = rtrim ($value,',');
		//p($value);die;
		//写进去的字段来源于数组的所有下标
		//值来源于所有的键值
		//$sql = "insert into student (sname,age,sex,cid) values ('艾丽丝',20,'女',1)";
		//组合sql语句
		$sql = "insert into {$this->table} ({$field}) values ({$value})";
		//返回受影响的条数
		return $this->e ($sql);
	}

	//更新数据
	//接一个更新的数据
	public function update($data){
		//如果没有写where条件则不允许更新
		if(!$this->where){
			return false;
		}
		$set = '';
		foreach($data as $k=>$v){
			//是int型的不要引号，不是的要加引号
			if(is_int ($v)){
				//组成下标等于值；在跟一个逗号
				$set .= $k . '=' . $v . ',';
			}else{
				$set .= $k . '=' . "'$v'" . ',';
			}
		}
		//去掉逗号
		$set = rtrim($set,',');
		//p($set);die;
		//sql = "update student set name='',age=35,sex='男' where id=1";
		//组合sql语句
		$sql = "update {$this->table} set {$set} {$this->where}";
		return $this->e ($sql);
	}

	//删除数据
	public function delete(){
		//如果没有where条件不允许更新
		if(!$this->where){
			return false;
		}
		//原生sql语句
		//$sql = "delete from student where id=10";
		//组合sql语句
		$sql = "delete from {$this->table} {$this->where}";
		return $this->e ($sql);
	}


	//执行有结果集的查询
	//select
	public function q ( $sql )
	{
		try {
			//执行sql语句
			$res = self ::$pdo -> query ( $sql );

			//将结果集取出来
			return $res -> fetchAll ( PDO::FETCH_ASSOC );
		} catch ( Exception $e ) {
			die( $e -> getMessage () );
		}
	}

	//执行无结果集的sql
	//insert、update、delete
	public function e ( $sql )
	{
		try {
			//执行sql语句
			return self ::$pdo -> exec ( $sql );
		} catch ( Exception $e ) {
			//输出错误消息
			die( $e -> getMessage () );
		}
	}
}