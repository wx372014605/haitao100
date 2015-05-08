<?php
/**
 * 自定义seesion处理类，通过将session储存在数据库实现多服务器session共享
 * 也可以统计网站在线人数等
 * 需要通过计划任务定时执行OPTIMIZE TABLE session来整理表(每月一次)
 * @author zjq
 * @version 1.0
 */
define('SESSION_TIME',86400); //session过期时间为一天

class MySession{
	/**
	* 数据库连接对象，设置成了静态变量，因为不设置为静态变量，数据库连接对象在其他方法不能被调用，目前还不清楚什么原因
	*
	* @var obj
	*/
	static public $db_handle;
	/**
	* 构造函数
	*
	* @param obj $db_handle 数据库连接对象
	*/
	function __construct($db_handle){
		self::$db_handle = $db_handle;
		
		$domain = '';
		
		//不使用 GET/POST 变量方式
		ini_set('session.use_trans_sid',0);
		
		//设置垃圾回收最大生存时间
		ini_set('session.gc_maxlifetime',SESSION_TIME);
		
		//使用 COOKIE 保存 SESSION ID 的方式
		ini_set('session.use_cookies',1);
		ini_set('session.cookie_path','/');
		
		//多主机共享保存 SESSION ID 的 COOKIE,因为我是本地服务器测试所以设置$domain=''
		ini_set('session.cookie_domain',$domain);
		
		//将 session.save_handler 设置为 user，而不是默认的 files
		session_module_name('user');
		
		//定义 SESSION 各项操作所对应的方法名，注意顺序不能互换
		session_set_save_handler(
			array(__CLASS__,'start'),//对应于类的start()方法，下同。
			array(__CLASS__,'close'),
			array(__CLASS__,'read'),
			array(__CLASS__,'write'),
			array(__CLASS__,'destroy'),
			array(__CLASS__,'gc')
		);
		
		//session_start()必须位于session_set_save_handler方法之后
		session_start();
	}
	
	static function start($save_path, $session_name){
		return true;
	}
	
	static function close(){
		return true;
	}
	
	static function read($session_id){
		$session_sql = "select session_data from session where session_id='$session_id' and expire_time>=".time()." limit 1";
		$result = self::$db_handle->getRow($session_sql);
		if($result){
			return $result['session_data'];
		}else{
			return '';
		}
	}
	
	static function write($session_id,$session_data){
		//ip地址
		$ip_addr = get_ip();
		//session过期时间
		$expire_time = time() + SESSION_TIME;
		
		//判断session是否存在
		$check_sql = "select session_id from session where session_id='$session_id' limit 1";
		$check_arr = self::$db_handle->getRow($check_sql);
		if($check_arr){
			$session_sql = "update session set ip_addr='$ip_addr',session_data='$session_data',expire_time='$expire_time' where session_id='$session_id' limit 1";
		}else{
			$session_sql = "insert into session (session_id,ip_addr,session_data,expire_time) values ('$session_id','$ip_addr','$session_data','$expire_time')";
		}
		
		$result = self::$db_handle->query($session_sql);
		return $result;
	}
	
	static function destroy($session_id){
		$session_sql = "delete from session where session_id='$session_id' limit 1";
		$result = self::$db_handle->query($session_sql);
		return true;
	}
	
	static function gc(){
		$session_sql = "delete from session where expire_time<".time();
		$result = self::$db_handle->query($session_sql);
		/*
		//由于经常性的对表做删除操作，容易产生碎片，所以在垃圾回收中对该表进行优化操作。
		$gc_sql = "optimize table session";
		self::$db_handle->query($gc_sql);
		*/
		return $result;
	}
}

//初始化 SESSION 设置，初始化时已经包含了session_start()！
$my_session = new MySession($mysql_handle);
?>