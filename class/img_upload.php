<?php
//引入ftp文件上传类
require_once 'ftp_upload.php';

/**
 * 上传图片通用类
 * @param field 上传控件file的名称，上传多个图片要加[]
 * @param upload_dir 上传之后的文件目录
 * @param img_type 允许上传的图片类型
 * @param img_size 允许上传的图片大小(单位为K)
 * @param remote 是否用ftp上传到远程服务器(0:本地,1:远程)
 * @param img_root 上传图片的根目录
 * @param thumb 是否生成缩略图
 * @param thumb_width 缩略图宽度
 * @param thumb_height 缩略图高度(一般情况不会用到此参数，图片会自动按比例缩放，如果设置了此参数图片会强制缩放到此大小)
 * @param thumb_auto缩略图是否自动缩放
 * @param watermark是否给图片添加水印
 * @param watermark_src水印图片路径
 * @param watermark_position水印图片位置(1：左上；2：顶部居中；3：右上；4：右侧居中；5：右下；6：底部居中；7：左下；8：左侧居中；9：正中)
 * (或者为以x,y组成坐标数组，可以自定义位置)
 * @return arr
 * 上传单个图片返回上传的结果和上传之后的字符串路径组成的数组，
 * 上传多个图片返回由单个图片数组组成的二维数组
 * @author zjq
 * @update 最后修改时间2014-3-27
 */
class ImgUpload{
	public $field;
	public $upload_dir;
	public $img_type = array("jpg","jpeg","gif","png","bmp");
	public $img_size = 1024;
	public $remote = 0;
	private $img_root;
	private $thumb = 0;
	private $thumb_width = 0;
	private $thumb_height = 0;
	private $thumb_auto = 1;
	private $watermark = 0;
	private $watermark_src = "";
	private $watermark_position = 1;
	
	//初始化相关变量
	function __construct($field,$upload_dir){
		$this->field = $field;
		$upload_dir = str_replace("\\","/",$upload_dir);
		$upload_dir = preg_replace("/[\/]+$/", "", $upload_dir);
		$this->upload_dir = $upload_dir."/".date("Y")."/".date("m")."/".date("d")."/";
		//获取上传图片的根目录，根据当前class文件的路径获取网站根目录
		$img_root = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
		$this->img_root = $img_root;
		//创建多级目录
		$this->create_dir($this->img_root.$this->upload_dir);
	}
	
	//上传图片
	public function upload(){
		$now_time = date("YmdHis");//获取当前时间
		$img_ob = $_FILES[$this->field];
		$name = $img_ob['name'];
		$name_type = gettype($name);
		if($name_type=="string"){//上传单个图片的情况
			//判断图片是否上传成功
			$error = $img_ob['error'];
			if($error!=0){
				return $this->return_result($error, "图片上传失败！");
			}
			//判断图片大小是否超过限制
			$size = $img_ob['size'];
			if($size>$this->img_size*1024){
				return $this->return_result("-1", "图片大小超过限制！");
			}
			//判断图片类型是否符合要求
			$extend_name = strtolower(substr(strrchr($name,"."),1));//文件扩展名
			if(!in_array($extend_name, $this->img_type)){
				return $this->return_result("-2", "图片类型不符！");
			}
			//表单提交后图片临时存放路径
			$tmp_name = $img_ob['tmp_name'];
			//随机生成上传之后的图片名称
			$file_name = $now_time.mt_rand(1000, 9999).".".$extend_name;
			//上传之后的文件全路径
			$uploaded_name = $this->upload_dir.$file_name;
			
			//生成图片句柄，成功则可以对图片创建缩略图和添加水印等操作，失败就直接进行图片上传
			switch ($extend_name){
				case "jpg":
				case "jpeg":
					$img_handle = @imagecreatefromjpeg($tmp_name);
					break;
				case "gif":
					$img_handle = @imagecreatefromgif($tmp_name);
					break;
				case "png":
					$img_handle = @imagecreatefrompng($tmp_name);
					break;
				default:
					$img_handle = false;
					break;
			}
			if($img_handle){
				//获取原始图片大小
				$original_size = getImageSize($tmp_name);
				if($original_size){
					//生成缩略图
					$thumb_name = $this->create_thumb_act($img_handle,$original_size,$uploaded_name);
					//添加水印
					$result = $this->create_watermark_act($img_handle,$original_size);
					if($result){
						$img_handle = $result;
					}
				}else{
					$thumb_name = "";
				}
			}else{
				$thumb_name = "";
			}
			
			//开始上传图片
			if($this->remote==0){
				if($img_handle and $this->watermark){
					$result = $this->save_gd_img($img_handle, $this->img_root.$uploaded_name);
					if(!$result){
						$result = @move_uploaded_file($tmp_name, $this->img_root.$uploaded_name);
					}
				}else{
					$result = @move_uploaded_file($tmp_name, $this->img_root.$uploaded_name);
				}
				if($result){
					return array("status"=>"0","img_name"=>$uploaded_name,"thumb_name"=>$thumb_name);
				}else{
					return $this->return_result("-3", "本地图片上传失败！");
				}
			}else if($this->remote==1){
				$ftp_conn = new FtpFileUpload();
				if(!$ftp_conn){
					return $this->return_result("-4", "ftp服务器连接失败！");
				}
				if($img_handle and $this->watermark){
					//保存添加了水印的临时文件
					$result = $this->save_gd_img($img_handle, $this->img_root.$uploaded_name);
					if($result){
						$ftp_uploaded_name = @$ftp_conn->upload_file($this->img_root.$uploaded_name, $uploaded_name);
						//删除临时文件
						if(file_exists($uploaded_name)){
							@unlink($uploaded_name);
						}
					}else{
						$ftp_uploaded_name = @$ftp_conn->upload_file($tmp_name, $uploaded_name);
					}
				}else{
					$ftp_uploaded_name = @$ftp_conn->upload_file($tmp_name, $uploaded_name);
				}
				if($ftp_uploaded_name){
					if($thumb_name){
						$ftp_thumb_name = @$ftp_conn->upload_file($this->img_root.$thumb_name, $thumb_name);
						if(file_exists($thumb_name)){
							@unlink($thumb_name);
						}
					}else{
						$ftp_thumb_name = "";
					}
					return array("status"=>"0","img_name"=>$ftp_uploaded_name,"thumb_name"=>$ftp_thumb_name);
				}else{
					return $this->return_result("-5", "ftp图片上传失败！");
				}
			}else{
				return $this->return_result("-6", "非法的图片上传方式！");
			}
		}else if($name_type=="array"){//上传多个图片的情况
			$return_arr = array();
			foreach ($name as $key=>$val){
				if($val==""){
					$return_arr[] = $this->return_result("1", "图片没有选择！");
					continue;
				}else{
					//判断图片是否上传成功
					$error = $img_ob['error'][$key];
					if($error!=0){
						$return_arr[] = $this->return_result($error, "图片上传失败！");
						continue;
					}
					//判断图片大小是否超过限制
					$size = $img_ob['size'][$key];
					if($size>$this->img_size*1024){
						$return_arr[] = $this->return_result("-1", "图片大小超过限制！");
						continue;
					}
					//判断图片类型是否符合要求
					$extend_name = strtolower(substr(strrchr($val,"."),1));//文件扩展名
					if(!in_array($extend_name, $this->img_type)){
						$return_arr[] = $this->return_result("-2", "图片类型不符！");
						continue;
					}
					//表单提交后图片临时存放路径
					$tmp_name= $img_ob['tmp_name'][$key];
					//随机生成上传之后的图片名称
					$file_name = $now_time.mt_rand(1000, 9999).$key.".".$extend_name;
					//上传之后的文件全路径
					$uploaded_name = $this->upload_dir.$file_name;
					
					//生成图片句柄，成功则可以对图片创建缩略图和添加水印等操作，失败就直接进行图片上传
					switch ($extend_name){
						case "jpg":
						case "jpeg":
							$img_handle = @imagecreatefromjpeg($tmp_name);
							break;
						case "gif":
							$img_handle = @imagecreatefromgif($tmp_name);
							break;
						case "png":
							$img_handle = @imagecreatefrompng($tmp_name);
							break;
						default:
							$img_handle = false;
							break;
					}
					if($img_handle){
						//获取原始图片大小
						$original_size = getImageSize($tmp_name);
						if($original_size){
							//生成缩略图
							$thumb_name = $this->create_thumb_act($img_handle,$original_size,$uploaded_name);
							//添加水印
							$result = $this->create_watermark_act($img_handle,$original_size);
							if($result){
								$img_handle = $result;
							}
						}else{
							$thumb_name = "";
						}
					}else{
						$thumb_name = "";
					}
					
					//开始上传图片
					if($this->remote==0){
						if($img_handle and $this->watermark){
							$result = $this->save_gd_img($img_handle, $this->img_root.$uploaded_name);
							if(!$result){
								$result = @move_uploaded_file($tmp_name, $this->img_root.$uploaded_name);
							}
						}else{
							$result = @move_uploaded_file($tmp_name, $this->img_root.$uploaded_name);
						}
						if($result){
							$return_arr[] = array("status"=>"0","img_name"=>$uploaded_name,"thumb_name"=>$thumb_name);
						}else{
							$return_arr[] = $this->return_result("-3", "本地图片上传失败！");
						}
					}else if($this->remote==1){
						$ftp_conn = new FtpFileUpload();
						if(!$ftp_conn){
							$return_arr[] = $this->return_result("-4", "ftp服务器连接失败！");
							continue;
						}
						if($img_handle and $this->watermark){
							//保存添加了水印的临时文件
							$result = $this->save_gd_img($img_handle, $this->img_root.$uploaded_name);
							if($result){
								$ftp_uploaded_name = @$ftp_conn->upload_file($this->img_root.$uploaded_name, $uploaded_name);
								//删除临时文件
								if(file_exists($uploaded_name)){
									@unlink($uploaded_name);
								}
							}else{
								$ftp_uploaded_name = @$ftp_conn->upload_file($tmp_name, $uploaded_name);
							}
						}else{
							$ftp_uploaded_name = @$ftp_conn->upload_file($tmp_name, $uploaded_name);
						}
						if($ftp_uploaded_name){
							if($thumb_name){
								$ftp_thumb_name = @$ftp_conn->upload_file($this->img_root.$thumb_name, $thumb_name);
								if(file_exists($thumb_name)){
									@unlink($thumb_name);
								}
							}else{
								$ftp_thumb_name = "";
							}
							$return_arr[] = array("status"=>"0","img_name"=>$ftp_uploaded_name,"thumb_name"=>$ftp_thumb_name);
						}else{
							$return_arr[] = $this->return_result("-5", "ftp图片上传失败！");
						}
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
	
	//存储gd库处理之后的图片
	private function save_gd_img($img_handle,$uploaded_name,$quailty=80){
		$name_arr = explode(".", $uploaded_name);
		$extend_name = $name_arr[1];
		switch ($extend_name){
			case "jpg":
			case "jpeg":
				$result = @imagejpeg($img_handle,$uploaded_name,$quailty);
				break;
			case "gif":
				$result = @imagegif($img_handle,$uploaded_name);
				break;
			case "png":
				$result = @imagepng($img_handle,$uploaded_name);
				break;
			default:
				$result = false;
				break;
		}
		return $result;
	}
	
	//生成缩略图
	public function create_thumb($thumb_width=60,$thumb_height=0){
		$this->thumb = 1;
		$this->thumb_width = $thumb_width;
		if($thumb_height>0){
			$this->thumb_height = $thumb_height;
			$this->thumb_auto = 0;//取消自动缩放
		}
	}
	
	//生成缩略图主处理
	private function create_thumb_act($img_handle,$source_size,$original_file_path){
		if($this->thumb){
			$name_arr = explode(".", $original_file_path);
			$extend_name = $name_arr[1];
			$thumb_file = $name_arr[0]."_small.".$extend_name;//缩略图路径
			
			//获取原始图片大小
			$src_w = $source_size[0];
			$src_h = $source_size[1];
			
			if($this->thumb_auto){
				$this->thumb_height = intval($this->thumb_width*$src_h/$src_w);
			}
			$thumb_image = imageCreateTrueColor($this->thumb_width,$this->thumb_height);
			imagecopyresampled($thumb_image,$img_handle,0,0,0,0,$this->thumb_width,$this->thumb_height,$src_w,$src_h);
			//保存缩略图
			$result = $this->save_gd_img($thumb_image,$this->img_root.$thumb_file,100);
			if($result){
				return $thumb_file;
			}
		}
		return "";
	}
	
	//给图片添加水印
	public function create_watermark($watermark_src,$watermark_position=1){
		$this->watermark = 1;
		$this->watermark_src = $watermark_src;
		$this->watermark_position = $watermark_position;
	}
	
	//给图片添加水印主处理
	public function create_watermark_act($img_handle,$source_size){
		if($this->watermark){
			$name_arr = explode(".", $this->watermark_src);
			$extend_name = $name_arr[1];
			switch ($extend_name){
				case "jpg":
				case "jpeg":
					$watermark_handle = @imagecreatefromjpeg($this->watermark_src);
					break;
				case "gif":
					$watermark_handle = @imagecreatefromgif($this->watermark_src);
					break;
				case "png":
					$watermark_handle = @imagecreatefrompng($this->watermark_src);
					break;
				default:
					$watermark_handle = false;
					break;
			}
			if($watermark_handle){
				//获取原始图片大小
				$src_w = $source_size[0];
				$src_h = $source_size[1];
				
				//获取水印图片大小
				$watermark_size = @getimagesize($this->watermark_src);
				if($watermark_size){
					$watermark_w = $watermark_size[0];
					$watermark_h = $watermark_size[1];
					if($watermark_w<=$src_w and $watermark_h<=$src_h){//水印图片大小不能超过原始图片
						//计算添加水印的位置
						switch ($this->watermark_position){
							case "1":
								$watermark_x = 0;
								$watermark_y = 0;
								break;
							case "2":
								$watermark_x = intval(($src_w-$watermark_w)/2);
								$watermark_y = 0;
								break;
							case "3":
								$watermark_x = $src_w-$watermark_w;
								$watermark_y = 0;
								break;
							case "4":
								$watermark_x = $src_w-$watermark_w;
								$watermark_y = intval(($src_h-$watermark_h)/2);
								break;
							case "5":
								$watermark_x = $src_w-$watermark_w;
								$watermark_y = $src_h-$watermark_h;
								break;
							case "6":
								$watermark_x = intval(($src_w-$watermark_w)/2);
								$watermark_y = $src_h-$watermark_h;
								break;
							case "7":
								$watermark_x = 0;
								$watermark_y = $src_h-$watermark_h;
								break;
							case "8":
								$watermark_x = 0;
								$watermark_y = intval(($src_h-$watermark_h)/2);
								break;
							case "9":
								$watermark_x = intval(($src_w-$watermark_w)/2);
								$watermark_y = intval(($src_h-$watermark_h)/2);
								break;
							default://自定义位置
								$watermark_x = $this->watermark_position[0];
								$watermark_y = $this->watermark_position[1];
								break;
						}
						//添加水印
						imagecopyresampled($img_handle,$watermark_handle,$watermark_x,$watermark_y,0,0,$watermark_w,$watermark_h,$watermark_w,$watermark_h);
						return $img_handle;
					}
				}
			}
		}
		return false;
	}
	
	//创建多级目录
	private function create_dir($path){
		if($path==""){
			return;
		}
		$path_array = explode("/", $path);
		$path_str = "";
		for ($i=0;$i<count($path_array);$i++){
			$path_str .= $path_array[$i]."/";
			if(!file_exists($path_str)){
				mkdir($path_str,0777);
			}
		}
	}
}
?>