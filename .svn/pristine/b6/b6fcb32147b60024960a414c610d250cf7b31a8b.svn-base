<?php
/**
 * 上传文件通用类
 * @param field 上传控件file的名称，上传多个文件要加[]
 * @param file_type 允许上传的文件类型
 * @param file_size 允许上传的文件大小(单位为K)
 * @author zjq
 * @update 2014-8-15 15:26
 */

class FileUpload{
	private $field;
	private $upload_dir;
	public $file_type = array();//默认允许上传所有类型的文件
	public $file_size = 2048;//默认允许上传2M的文件
	
	//初始化相关变量
	function __construct($field,$upload_dir){
		$this->field = $field;
		$upload_dir = preg_replace("/[\/]+$/", "", $upload_dir);
		$this->upload_dir = $upload_dir."/".date("Y")."/".date("m")."/".date("d")."/";
		create_dir($upload_dir);
	}
	
	//上传文件
	public function upload(){
		$now_time = date("YmdHis");//获取当前时间
		$file_ob = $_FILES[$this->field];
		$name = $file_ob['name'];
		$name_type = gettype($name);
		if($name_type=="string"){//上传单个文件的情况
			//判断文件是否上传成功
			$error = $file_ob['error'];
			if($error!=0){
				return $this->return_result($error, "文件上传失败！");
			}
			//判断文件大小是否超过限制
			$size = $file_ob['size'];
			if($size>$this->file_size*1024){
				return $this->return_result("1", "文件大小超过限制！");
			}
			//判断文件类型是否符合要求
			$extend_name = strtolower(substr(strrchr($name,"."),1));//文件扩展名
			if(!in_array($extend_name, $this->file_type)){
				return $this->return_result("1", "文件类型不符！");
			}
			//表单提交后文件临时存放路径
			$tmp_name = $file_ob['tmp_name'];
			//随机生成上传之后的文件名称
			$file_name = $now_time.mt_rand(1000, 9999).".".$extend_name;
			//上传之后的文件全路径
			$uploaded_name = $this->upload_dir.$file_name;
			
			//开始上传文件
			$result = @move_uploaded_file($tmp_name, WEB_ROOT.$uploaded_name);
			if($result){
				return array("status"=>"0","file_name"=>$uploaded_name);
			}else{
				return $this->return_result("1", "文件上传失败！");
			}
		}else if($name_type=="array"){//上传多个文件的情况
			$return_arr = array();
			foreach ($name as $key=>$val){
				if($val==""){
					$return_arr[] = $this->return_result("1", "文件没有选择！");
					continue;
				}else{
					//判断文件是否上传成功
					$error = $file_ob['error'][$key];
					if($error!=0){
						$return_arr[] = $this->return_result($error, "文件上传失败！");
						continue;
					}
					//判断文件大小是否超过限制
					$size = $file_ob['size'][$key];
					if($size>$this->file_size*1024){
						$return_arr[] = $this->return_result("1", "文件大小超过限制！");
						continue;
					}
					//判断文件类型是否符合要求
					$extend_name = strtolower(substr(strrchr($val,"."),1));//文件扩展名
					if(!in_array($extend_name, $this->file_type)){
						$return_arr[] = $this->return_result("1", "文件类型不符！");
						continue;
					}
					//表单提交后文件临时存放路径
					$tmp_name= $file_ob['tmp_name'][$key];
					//随机生成上传之后的文件名称
					$file_name = $now_time.mt_rand(1000, 9999).$key.".".$extend_name;
					//上传之后的文件全路径
					$uploaded_name = $this->upload_dir.$file_name;
					
					//开始上传文件
					$result = @move_uploaded_file($tmp_name, WEB_ROOT.$uploaded_name);
					if($result){
						$return_arr[] = array("status"=>"0","file_name"=>$uploaded_name);
					}else{
						$return_arr[] = $this->return_result("1", "文件上传失败！");
					}
				}
			}
			return $return_arr;
		}else{
			return "";
		}
	}
	
	//返回结果
	public function return_result($status,$message){
		return array("status"=>$status,"message"=>$message);
	}
}
?>