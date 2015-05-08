<?php
/**
 * 群发消息后台主处理
 * 用缓存机制提高程序执行效率
 * 每次只发送一条消息，并通过ajax在前端显示发送进度条
 * 当前版本如果有多个管理员同时操作群发会有冲突
 * @author zjq
 * @version 1.0
 */

//获取当前发送消息的序号
$current_index = post_number('current_index');
//群发信息的缓存目录
$cache_dir = 'cache/weixin/';
//每1000条数据存为1个缓存文件
$record_num = 1000;

if($current_index==0){
	//清除media_id缓存
	delete_session('media_list');
	delete_session('thumb_media_list');
	
	//获取素材id
	$material_id = post_string('material_id');
	
	//获取消息数据
	$msg_data = strip_wraps(strip_slashes(post_string('msg_data', 0),true));
	
	if($material_id==0 and $msg_data==''){
		ajax_return(-1, '获取群发数据失败！');
	}
	
	//获取消息类型
	$msg_type = post_string('msg_type');
	
	//获取公众平台id
	$platform_id = post_string('platform_id');
	
	//获取用户性别
	$sex = post_number('sex');
	
	//获取关注起始条件
	$start_time = post_string('start_time');
	
	//获取关注结束条件
	$end_time = post_string('end_time');
	
	if($start_time!='' and $end_time!='' and strtotime($start_time)>=strtotime($end_time)){
		ajax_return(-1, '用户关注开始时间不能大于结束时间！');
	}
	
	//获取省份id
	$province_id = post_number('province_id');
	
	//获取城市id
	$city_id = post_number('city_id');
	
	
	//获取待发送的用户列表
	$user_sql = "select open_id,platform_id,nickname from weixin_user_info where 1";
	if($platform_id!=''){
		$user_sql .= " and platform_id='$platform_id'";
	}
	if($sex>0){
		$user_sql .= " and sex=$sex";
	}
	if($start_time!=''){
		$user_sql .= " and subscribe_time>'$start_time'";
	}
	if($end_time!=''){
		$user_sql .= " and subscribe_time<'$end_time'";
	}
	if($city_id>0){//优先判断城市
		$city_sql = "select area_name from areas where area_id=$city_id limit 1";
		$city_arr = $mysql_handle->getRow($city_sql);
		if($city_arr){
			$city_name = str_replace('市', '', $city_arr['area_name']);
			$user_sql .= " and city='$city_name'";
		}
	}else if($province_id>0){
		$province_sql = "select area_name from areas where area_id=$province_id limit 1";
		$province_arr = $mysql_handle->getRow($province_sql);
		if($province_arr){
			$province_name = str_replace('省', '', $province_arr['area_name']);
			$user_sql .= " and province='$province_name'";
		}
	}
	$user_arr = $mysql_handle->getRs($user_sql);
	$totle_count = count($user_arr);
	if($totle_count==0){
		ajax_return(-1, '没有搜索到符合条件的用户，请减少筛选条件后重新尝试！', array('totle_count'=>$totle_count));
	}else{
		//保存群发素材
		if($material_id==0){
			//获取微信表情，将img标签替换成表情符号
			if($msg_type=='text'){
				preg_match_all("/<\s*img.*?src\s*=\s*'[^']+?ico(\d+)\.(?:jpg|jpeg|png|gif|bmp)\s*'\s*[\/]?>/i", $msg_data, $img_arr);
				$img_str_arr = $img_arr[0];
				$exp_id_arr = $img_arr[1];
				$exp_code_arr = array();
				if($img_str_arr){
					$exp_sql = "select exp_id,exp_code from weixin_exp";
					$exp_arr = $mysql_handle->getRs($exp_sql);
					$code_arr = array();
					foreach ($exp_arr as $val){
						$code_arr[$val['exp_id']] = str_replace("'", "\\'", $val['exp_code']);
					}
					unset($exp_arr);
					foreach ($img_str_arr as $key=>$val){
						$img_str = $val;
						$exp_code = $code_arr[$exp_id_arr[$key]];
						$msg_data = str_replace($img_str, $exp_code, $msg_data);
					}
				}
				$msg_data = strip_html_tags($msg_data);
			}
			$add_time = get_current_time();
			$material_sql = "insert into weixin_material (material_type,material_data,add_time) values ('$msg_type','".str_replace("'", "\\'", $msg_data)."','$add_time')";
			$material_result = $mysql_handle->query($material_sql);
			if($material_result){
				$material_id = $mysql_handle->get_insert_id();
			}else{
				ajax_return(-1, '保存群发素材失败！');
			}
		}else{
			//根据素材id获取素材数据
			$material_sql = "select material_data from weixin_material where material_id=$material_id limit 1";
			$material_arr = $mysql_handle->getRow($material_sql);
			if($material_arr==false){
				ajax_return(-1, '获取群发数据失败！');
			}else{
				$msg_data = $material_arr['material_data'];
				//加入图文消息的链接
				if($msg_type=='news'){
					preg_match_all('/"url":""/', $msg_data, $url_arr);
					foreach ($url_arr[0] as $key=>$val){
						$msg_data = preg_replace('/"url":""/', '"url":"'.WEB_URL.'index.php?app=weixin_news_detail&material_id='.$material_id.'&news_index='.$key.'"', $msg_data, 1);
					}
				}
			}
		}
		//用session存储该次群发的信息
		set_session('mass_send', array('material_id'=>$material_id,'totle_count'=>$totle_count,'success_count'=>0,'start_time'=>get_current_time()));
		
		//重新获取群发信息
		$mass_info = array();
		foreach ($user_arr as $val){
			$open_id = $val['open_id'];
			$platform_id = $val['platform_id'];
			$nickname = $val['nickname'];
			$access_token = $weixin_handle->get_access_token($platform_id);
			$mass_info[] = array(
				'open_id'		=>	$open_id,
				'nickname'		=>	$nickname,
				'access_token'	=>	$access_token
			);
		}
		unset($user_arr);//释放内存
		
		//创建缓存目录
		$result = create_dir($cache_dir);
		if(!$result){
			ajax_return(-1, '创建缓存目录失败！');
		}
		
		//缓存群发的用户列表和群发数据内容
		$file_num = 0;//缓存文件的数量
		while(true){
			$cache_arr = array_slice($mass_info, $file_num*$record_num, $record_num);
			if($cache_arr){
				$user_cache_file = $cache_dir.'mass_user_'.$file_num.'.tmp';
				write_file($user_cache_file, json_encode($cache_arr));
				$file_num += 1;
			}else{
				break;
			}
		}
		$data_arr = array(
			'msg_type'	=>	$msg_type,
			'msg_data'	=>	$msg_data
		);
		$data_cache_file = $cache_dir.'mass_data.tmp';
		write_file($data_cache_file, json_encode($data_arr));
	}
}

//获取单次发送的用户
$user_cache_file = $cache_dir.'mass_user_'.floor($current_index/$record_num).'.tmp';
$user_json = read_file($user_cache_file);
if($user_json){
	$user_arr = json_decode($user_json);
	$data_index = $current_index%$record_num;
	if(isset($user_arr[$data_index]) and $user_arr[$data_index]){
		//开始群发
		$open_id = $user_arr[$data_index]->open_id;
		//我的聚芳永open_id oucABj6G9tcHXR6v18q9PwQSIW78
		//$open_id = 'oucABj6G9tcHXR6v18q9PwQSIW78';
		$nickname = $user_arr[$data_index]->nickname?$user_arr[$data_index]->nickname:$open_id;
		$access_token = $user_arr[$data_index]->access_token;
		//获取群发数据内容
		$data_cache_file = $cache_dir.'mass_data.tmp';
		$data_json = read_file($data_cache_file);
		$data_obj = json_decode($data_json);
		if($data_obj){
			$msg_type = $data_obj->msg_type;
			$msg_data = $data_obj->msg_data;
			switch($msg_type){
			case 'text':
				$content_obj = json_decode($msg_data);
				$content = $content_obj->content;
				$send_result = $weixin_handle->sendText($open_id, $access_token, $content);
				break;
			case 'image':
				$image_info = $weixin_handle->data_to_image($msg_data);
				if($image_info){
					//获取图片素材的媒体ID
					$media_list = get_session('media_list');
					if(isset($media_list[$access_token]) and $media_list[$access_token]){
						$media_id = $media_list[$access_token];
					}else{
						$image_path = str_replace(WEB_URL, WEB_ROOT, $image_info->image_url);//图片文件的绝对路径
						$media_id = $weixin_handle->upload_media($access_token, 'image', $image_path);
						if(!$media_id){
							$send_result = false;
							break;
						}
						$media_list[$access_token] = $media_id;
						set_session('media_list', $media_list);
					}
					$send_result = $weixin_handle->sendImage($open_id, $access_token, $media_id);
				}else{
					$send_result = false;
				}
				break;
			case 'voice':
				$voice_info = $weixin_handle->data_to_voice($msg_data);
				if($voice_info){
					//获取语音素材的媒体ID
					$media_list = get_session('media_list');
					if(isset($media_list[$access_token]) and $media_list[$access_token]){
						$media_id = $media_list[$access_token];
					}else{
						$voice_path = str_replace(WEB_URL, WEB_ROOT, $voice_info->voice_url);//语音文件的绝对路径
						$media_id = $weixin_handle->upload_media($access_token, 'voice', $voice_path);
						if(!$media_id){
							$send_result = false;
							break;
						}
						$media_list[$access_token] = $media_id;
						set_session('media_list', $media_list);
					}
					$send_result = $weixin_handle->sendVoice($open_id, $access_token, $media_id);
				}else{
					$send_result = false;
				}
				break;
			case 'video':
				break;
			case 'news':
				$send_result = $weixin_handle->sendNews($open_id,$access_token,$msg_data);
				break;
			case 'music':
				$music_info = $weixin_handle->data_to_music($msg_data);
				if($music_info){
					//获取音乐缩略图的媒体ID
					$thumb_media_list = get_session('thumb_media_list');
					if(isset($thumb_media_list[$access_token]) and $thumb_media_list[$access_token]){
						$thumb_media_id = $thumb_media_list[$access_token];
					}else{
						/*
						$music_pic = $music_info->music_pic;
						if($music_pic){
							$music_path = str_replace(WEB_URL, WEB_ROOT, $music_pic);//音乐封面的绝对路径
						}else{
							$music_path = WEB_ROOT.'admin/images/cd_cover.jpg';//系统默认的音乐封面
						}
						*/
						//用户上传封面不可控因素太多，所以统一采用系统默认封面
						$music_path = WEB_ROOT.'admin/images/cd_cover.jpg';//系统默认的音乐封面
						$thumb_media_id = $weixin_handle->upload_media($access_token, 'thumb', $music_path);
						if(!$thumb_media_id){
							$send_result = false;
							break;
						}
						$thumb_media_list[$access_token] = $thumb_media_id;
						set_session('thumb_media_list', $thumb_media_list);
					}
					$title = $music_info->title;
					if($music_info->singer){
						$title .= '---'.$music_info->singer;
					}
					$description = sub_str($music_info->description, 100);
					$musicurl = $music_info->musicurl;
					$hqmusicurl = $music_info->hqmusicurl;
					$send_result = $weixin_handle->sendMusic($open_id, $access_token, $title, $description, $musicurl, $hqmusicurl, $thumb_media_id);
				}else{
					$send_result = false;
				}
				break;
			}
			if($current_index==0){
				$attach_info = array('totle_count'=>$totle_count);
			}else{
				$attach_info = array();
			}
			if($send_result){
				//增加发送成功的次数
				$mass_send = get_session('mass_send');
				if(isset($mass_send['success_count'])){
					$success_count = $mass_send['success_count'];
					$mass_send['success_count'] = $success_count+1;
					set_session('mass_send', $mass_send);
				}else{
					$mass_send['success_count'] = 0;
					set_session('mass_send', $mass_send);
				}
				ajax_return(1, '微信用户【<font style="color:#2378BE;">'.$nickname.'</font>】发送成功！', $attach_info);
			}else{
				//获取错误信息
				$error_info = $weixin_handle->get_error_info();
				ajax_return(0, '微信用户【<font style="color:#2378BE;">'.$nickname.'</font>】发送失败，'.$error_info['error_info'].'！', $attach_info);
			}
		}else{
			ajax_return(-1, '群发数据解析失败！');
		}
	}else{
		//处理群发时间
		$mass_send = get_session('mass_send');
		if(isset($mass_send['start_time'])){
			$material_id = $mass_send['material_id'];
			$success_count = $mass_send['success_count'];
			$totle_count = $mass_send['totle_count'];
			$send_start_time = $mass_send['start_time'];
			$send_end_time = get_current_time();
			$mass_send['send_time'] = $send_end_time;
			set_session('mass_send', $mass_send);
			$cost_time = strtotime($send_end_time)-strtotime($send_start_time);
			$attach_info = array('cost_time'=>second_to_time($cost_time));
			
			//保存群发历史记录
			$history_sql = "insert into weixin_send_history (material_id,start_time,end_time,success_count,totle_count) values 
			($material_id,'$send_start_time','$send_end_time',$success_count,$totle_count)";
			$history_result = $mysql_handle->query($history_sql);
		}else{
			$attach_info = array();
		}
		ajax_return(0, '群发结束！', $attach_info);
	}
}else{
	ajax_return(-1, '获取用户列表失败！');
}
?>