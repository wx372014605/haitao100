<?php 
/** 
 * mysql操作通用类
 * @author zjq
 * @version 1.0
 */
class MysqlClass{
	
	//保存类实例的静态成员变量
	private static $_instance;
	
	//mysql连接句柄
	private $conn;
	
	//单例模式禁止用new初始化
	private function __construct(){
		$this->conn = mysql_connect(MYSQL_HOST.':'.MYSQL_PORT,MYSQL_USER,MYSQL_PASS);
		if($this->conn==false){
			echo "错误代码:".mysql_errno().",".mysql_error();
			exit();
		}
		mysql_select_db(MYSQL_DB);
		mysql_query("set names utf8");
	}
	
	//单例模式禁止克隆
	private function __clone(){
		
	}
	
	//用于实例化该类的静态方法
	static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	//获取一条数据
	function getRow($sql){
		$result_resource = mysql_query($sql,$this->conn);
		if($result_resource==false){
			echo "错误代码:".mysql_errno().",".mysql_error();
			exit();
		}
		$arr = mysql_fetch_assoc($result_resource);
		mysql_free_result($result_resource);
		return $arr;
	}
	
	//获取全部数据
	function getRs($sql){
		$result_resource = mysql_query($sql,$this->conn);
		if($result_resource==false){
			echo "错误代码:".mysql_errno().",".mysql_error();
			exit();
		}
		$result_arr = array();
		while($arr = mysql_fetch_assoc($result_resource)){
			$result_arr[] = $arr;
		}
		mysql_free_result($result_resource);
		return $result_arr;
	}
	
	//执行mysql语句
	function query($sql){
		return mysql_query($sql,$this->conn);
	}
	
	//获取上一次insert操作的id
	function get_insert_id(){
		return mysql_insert_id($this->conn);
	}
	
	//自动关闭数据库连接
	function __destruct(){
		//@mysql_close($this->conn);
	}
}

//开启一个数据库连接
$mysql_handle = MysqlClass::getInstance();
?>