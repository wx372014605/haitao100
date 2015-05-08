<?php
/**
 * ftp通用类
 * @author zjq
 * @version 1.0
 */
class FtpFileUpload{
	//ftp配置项
	private $ftp_server = "203.195.189.165";
        private $ftp_user_name = "jfyftp";
        private $ftp_user_pass = "jfy123456";
        //ftp连接句柄
        private $conn;
        //ftp服务器下的文件根目录
        public $upload_base = "/jfy_weixin/";
        //ftp访问的url根目录
        public static $url_root = "http://itealife.gz.1251027950.clb.myqcloud.com/jfy_weixin/";
        
        function __construct(){
        //打开ftp连接
    	$this->conn = ftp_connect($this->ftp_server) or die("Couldn't connect to $this->ftp_server");
    	//登录ftp服务器
    	$login_result = ftp_login($this->conn, $this->ftp_user_name, $this->ftp_user_pass);
		@ftp_pasv($this->conn,true);//被动传输模式
        }
        
	//检查ftp目录下是否存在某目录
	//参数：目录名称,返回值：目录存在返回true，不存在返回false
	public function dir_exists($dirname) {
		//通过切换目录是否成功判断目录是否存在
		if(@ftp_chdir($this->conn,$dirname)){
			//切换到原来目录
			@ftp_chdir($this->conn,'/');
			return true;
		}else{
			return false;
		}
	}
	
	//创建多级目录
	public function create_dir($dirname){
		$dir_arr = explode("/", $dirname);
		$dir_str = "";
		foreach ($dir_arr as $val){
			$dir_str .= "/".$val;
			if(!$this->dir_exists($dir_str)){
				ftp_mkdir($this->conn,$dir_str);
			}
		}
	}
	
	//上传文件到远程ftp服务器
	public function upload_file($source_file,$uploaded_name){
		$upload_dir = dirname($uploaded_name);
		$upload_dir = preg_replace("/[\/]+$/", "", $upload_dir)."/";
		//创建多级目录
		$this->create_dir($this->upload_base.$upload_dir);
		//上传之后的文件路径
		$uploaded_file = $this->upload_base.$uploaded_name;
		//开始上传
		$result = @ftp_put($this->conn, $uploaded_file, $source_file, FTP_BINARY);
		if($result){
			return self::$url_root.$uploaded_name;
		}else{
			return false;
		}
	}
	
	function __destruct(){
		ftp_close($this->conn);
	}
}
?>