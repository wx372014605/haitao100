<?php 
/**
 * 更新用户数据并记录数据
 * @author zjq
 * v1.2
 */
$start_time = microtime();
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行.

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');

//如果没有图片保存目录，则自动创建
if(!is_dir($webRoot."weixin_ico/")){
	mkdir($webRoot."weixin_ico/", 0777);
}

//获取公众帐号列表
$account_sql = "select platform_id from weixin_account where type=1";
$account_arr = $mysql_handle->getRs($account_sql);

foreach($account_arr as $val){
	//获取access_token
	$platform_id = $val['platform_id'];
	$access_token = $weixin_handle->get_access_token($platform_id);
	
	//拉取关注者列表
	if($access_token){
		$subscribe_list = $weixin_handle->get_subscribe_list($platform_id);
		//echo "获取关注者列表".get_cost_time($start_time);
		//更新关注者信息
		foreach($subscribe_list as $open_id){
			$info_obj = $weixin_handle->get_weixin_user_info($platform_id,$open_id);
			if($info_obj){
				$nickname = trim($info_obj->nickname);
				$sex = $info_obj->sex;
				$country = $info_obj->country;
				$province = $info_obj->province;
				$city = $info_obj->city;
				$language = $info_obj->language;
				$headimgurl = $info_obj->headimgurl;
				$subscribe_time = date("Y-m-d H:i:s",$info_obj->subscribe_time);
				$update_time = get_current_time();
				
				//从远程下载图片并保存在本地
				if($headimgurl){
					$curl = curl_init($headimgurl);
					$header = array(
						'Accept:*/*',
						'Accept-Charset:GBK,utf-8;q=0.7,*;q=0.3',
						'Accept-Encoding:gzip,deflate,sdch','Accept-Language:zh-CN,zh;q=0.8',
						'Connection:keep-alive',
						'Host:wx.qlogo.cn'
					);
					curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
					curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:26.0) Gecko/20100101 Firefox/26.0');
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
					$img_str = curl_exec($curl);
					$curl_info = curl_getinfo($curl);
					if(curl_errno($curl)){
						continue;
					}
					curl_close($curl);
					//echo "抓取图片".get_cost_time($start_time);
					if($img_str){
						$dir_name = "weixin_ico/".$platform_id."/";
						//如果没有图片保存目录，则自动创建
						if(!is_dir($webRoot.$dir_name)){
							mkdir($webRoot.$dir_name, 0777);
						}
						//生成新的图片
						switch ($curl_info['content_type']){
							case 'image/png':
								$extend_name = "png";
								break;
							case 'image/gif':
								$extend_name = "gif";
								break;
							default:
								$extend_name = "jpg";
								break;
						}
						$ico_name = $dir_name.$open_id.".".$extend_name;
						$ico_file = @fopen($webRoot.$ico_name, "w");
						if($ico_file==false){
							continue;
						}
						fwrite($ico_file, $img_str);
						fclose($ico_file);
						//echo "保存图片".get_cost_time($start_time);
						//更新数据库
						$check_sql = "select user_id from weixin_user_info where open_id='$open_id' limit 1";
						$check_arr = $mysql_handle->getRow($check_sql);
						if($check_arr){
							$sql = "update weixin_user_info set platform_id='$platform_id',nickname='$nickname',sex='$sex',country='$country',province='$province',
							city='$city',language='$language',headimgurl='$ico_name',subscribe_time='$subscribe_time',update_time='$update_time' 
							where open_id='$open_id' limit 1";
						}else{
							$sql = "insert into weixin_user_info (open_id,platform_id,nickname,sex,country,province,city,language,headimgurl,subscribe_time,update_time) values 
							('$open_id','$platform_id','$nickname','$sex','$country','$province','$city','$language','$ico_name','$subscribe_time','$update_time')";
						}
						$result = $mysql_handle->query($sql);
						//echo "更新数据库".get_cost_time($start_time);
					}
				}
			}
		}
	}
}


//统计耗时
function get_cost_time(&$start_time){
	$end_time = microtime();
	$start_arr = explode(" ",$start_time);
	$end_arr = explode(" ",$end_time);
	$start_time = $end_time;
	return "耗时:".($end_arr[0]+$end_arr[1]-$start_arr[0]-$start_arr[1])."秒\r\n";
}
?>