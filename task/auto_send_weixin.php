<?php
/**
 * 每月定时发表微信图文消息
 * @author zjq
 * v1.0
 */
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');
//引入微信通用类
require_once ($webRoot.'class/weixin.php');
//引入phpQuery
require_once ($webRoot.'class/phpQuery.php');

//微信公众帐号的平台id
$platform_id = 'gh_d3a2616cb66e';

//获取海淘商品列表
$goods_cached = true;//是否已经缓存的标记
$haitao_goods = get_cache('haitao_goods');
if(!$haitao_goods){
	curl_get_contents(WEB_URL.'task/get_haitaobei_goods.php',0);
	curl_get_contents(WEB_URL.'task/get_smzdm_goods.php',0);
	curl_get_contents(WEB_URL.'task/get_dealam_goods.php',0);
	$goods_cached = false;
}else{
	if(!isset($haitao_goods['haitaobei'])){
		curl_get_contents(WEB_URL.'task/get_haitaobei_goods.php',0);
		$goods_cached = false;
	}
	if(!isset($haitao_goods['smzdm'])){
		curl_get_contents(WEB_URL.'task/get_smzdm_goods.php',0);
		$goods_cached = false;
	}
	if(!isset($haitao_goods['dealam'])){
		curl_get_contents(WEB_URL.'task/get_dealam_goods.php',0);
		$goods_cached = false;
	}
}
if(!$goods_cached){
	$haitao_goods = get_cache('haitao_goods');
}
if($haitao_goods){
	$haitao_result = array();
	foreach ($haitao_goods as $site_tag=>$val){
		foreach ($val as $class_id=>$goods_arr){
			if(isset($haitao_result[$class_id]) and $haitao_result[$class_id]){
				$haitao_result[$class_id] = array_merge($haitao_result[$class_id],$goods_arr);
			}else{
				$haitao_result[$class_id] = $goods_arr;
			}
		}
	}
	//随机生成待群发的商品列表
	$news_arr = array();
	foreach ($haitao_result as $val){
		$goods_count = count($val);
		if($goods_count==0){
			continue;
		}
		$rand_num = mt_rand(1, $goods_count);
		$rand_goods = $val[$rand_num-1];
		$news_arr[] = $rand_goods;
	}
	$news_arr = array_slice($news_arr, 0, 10);
	//生成图文消息json
	$news_json = '';
	$access_token = $weixin_handle->get_access_token($platform_id);
	$media_type = 'thumb';
	foreach ($news_arr as $key=>$val){
		$site_tag = $val['site_tag'];
		$goods_name = $val['goods_name'];
		$goods_img = $val['goods_img'];
		$goods_url = $val['goods_url'];
		$info_url = $val['info_url'];
		$goods_content = get_goods_content($site_tag,$info_url);
		//生成缩略图并保存为jpg格式
		$tmp_img = md5($goods_img).'_'.$key.'.jpg';
		$img_content = curl_get_contents($goods_img);
		$img_handle = imagecreatefromstring($img_content);
		$img_width = imagesx($img_handle);
		$img_height = imagesy($img_handle);
		$thumb_width = $key==0?300:100;
		$thumb_height = round($thumb_width*$img_height/$img_width);
		$thumb_handle = imagecreatetruecolor($thumb_width, $thumb_height);
		imagecopyresampled($thumb_handle, $img_handle, 0, 0, 0, 0, $thumb_width, $thumb_height, $img_width, $img_height);
		$result = imagejpeg($thumb_handle,get_cache_path().$tmp_img);
		imagedestroy($img_handle);
		imagedestroy($thumb_handle);
		if($result){
			$media_path = get_cache_path().$tmp_img;
			$thumb_media_id = $weixin_handle->upload_media($access_token, $media_type, $media_path);
			if($thumb_media_id){
				$show_cover_pic = $key==0?1:0;//是否显示封面，1为显示，0为不显示
				$news_json .= '
					{
						"thumb_media_id":"'.$thumb_media_id.'",
						"author":"海淘100",
						"title":"'.$goods_name.'",
						"content_source_url":"'.$goods_url.'",
						"content":"'.add_slashes($goods_content,true).'",
						"digest":"",
						"show_cover_pic":"'.$show_cover_pic.'"
					},
				';
			}
			delete_cache($tmp_img);//删除临时文件
		}
	}
	$news_json = preg_replace('/,$/', '', $news_json);
	$news_media_id = $weixin_handle->upload_news($access_token, $news_json);
	$send_result = $weixin_handle->advancedSendNews($access_token, $news_media_id);
}

//获取商品内容
function get_goods_content($site_tag,$info_url){
	$info_result = curl_get_contents($info_url,60);
	$info_doc = phpQuery::newDocument($info_result);
	switch ($site_tag){
		case 'haitaobei':
			$find_list = $info_doc['.find-list'];
			$goods_content = pq($find_list)->find('.find-list-ctn')->html();
			$goods_content .= pq($find_list)->find('.find-body')->html();
			break;
		case 'smzdm':
			$goods_content = $info_doc['.news_content p']->html();
			//破解防盗链
			preg_match_all('/\ssrc="(http:\/\/ym?\.zdmimg\.com\/[^"]*\.(?:jpg|jpeg|png|bmp|gif))"\s/', $goods_content, $matches_arr);
			if(isset($matches_arr[1]) and $matches_arr[1]){
				foreach ($matches_arr[1] as $val){
					$goods_content = str_replace($val, WEB_URL.'index.php?app=get_daolian_pic&img_url='.urlencode($val), $goods_content);
				}
			}
			break;
		case 'dealam':
			$goods_content = $info_doc['.body']->html();
			break;
		case 'wujinggou':
			$goods_content = "";
			break;
		default:
			$goods_content = "";
			break;
	}
	return $goods_content;
}
?>