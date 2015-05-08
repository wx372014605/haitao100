<?php 
/** 
 * memcache操作通用类
 * @author zjq
 * @version 1.0
 */
class MemcacheClass{
	
	//保存类实例的静态成员变量
	private static $_instance;
	
	//memcache对象
	private static $memcache;
	
	//单例模式禁止用new初始化
	private function __construct(){
		
	}
	
	//单例模式禁止克隆
	private function __clone(){
		
	}
	
	//用于实例化该类的静态方法
	static function getInstance(){
		if(class_exists('Memcache')){
			self::$memcache = new Memcache;
			$conn = @self::$memcache->connect('127.0.0.1', 11211);
			if(!$conn){
				return false;
			}
		}else{
			return false;
		}
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	//获取memcache版本号
	function getVersion(){ 
		return self::$memcache->getVersion();
	}
	
	//获取服务器统计信息
	function getStats(){
		return self::$memcache->getStats();
	}
	
	/*
	 * 写入数据
	 * @param $key string 键名
	 * @param $value mix 键值
	 * @param $expire int 是以秒为单位的失效时间
	 * @param $flag int 是否启用压缩，如果你希望存储的元素 经过压缩（使用zlib），你可以设置flag的值为MEMCACHE_COMPRESSED
	 */
	function set($key,$value,$expire=3600,$flag=0){
		return self::$memcache->set($key, $value, $flag, $expire);
	}
	
	//读取数据
	function get($key){
		return self::$memcache->get($key,$flag=0);
	}
	
	//删除数据
	function delete($key,$timeout=0){
		return self::$memcache->delete($key,$timeout);
	}
	
	//清空数据
	function flush(){
		return self::$memcache->flush();
	}
	
	//自动关闭memcache连接
	function __destruct(){
		@self::$memcache->close();
	}
}

//开启一个memcache连接
$memcache_handle = MemcacheClass::getInstance();
?>