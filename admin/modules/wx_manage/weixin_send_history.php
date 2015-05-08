<?php 
//初始化分页类
$page = new Paging();

//获取群发消息历史记录
$history_sql = "select his.*,material.material_type,material.material_data from weixin_send_history as his join weixin_material as material on his.material_id=material.material_id order by his.history_id desc";
$history_arr = $page->get_record($history_sql);

//获取群发消息的时间
function get_cost_time($start_time,$end_time){
	$time1 = strtotime($start_time);
	$time2 = strtotime($end_time);
	$time_diff = abs($time1-$time2);
	return second_to_time($time_diff);
}

//获取群发消息详情
function get_history_info($material_id,$material_type,$material_data){
	global $mysql_handle,$weixin_handle;
	switch ($material_type){
		case 'text':
			$text_info = $weixin_handle->data_to_text($material_data);
			if($text_info){
				//获取微信表情
				$exp_sql = "select * from weixin_exp";
				$exp_arr = $mysql_handle->getRs($exp_sql);
				$code_arr = array();
				$img_arr = array();
				foreach ($exp_arr as $val){
					$code_arr[] = $val['exp_code'];
					$img_arr[] = '<img title="'.$val['exp_name'].'" src="'.WEB_URL.'images/weixin_exp/ico'.$val['exp_id'].'.gif'.'"/>';
				}
				$content = $text_info->content;
				$content = str_replace($code_arr, $img_arr, $content);
				return '<div class="history_data"><p class="history_title">[文本消息]</p><p class="text_content">'.$content.'</p></div>';
			}else{
				return '<div class="data_error">文本消息解析失败！</div>';
			}
			break;
		case 'image':
			$image_info = $weixin_handle->data_to_image($material_data);
			if($image_info){
				$image_url = $image_info->image_url;
				$history_data = '';
				$history_data .= '<div class="history_data">';
				$history_data .= '	<div class="history_img"><img src="'.$image_url.'" width="150px"/></div>';
				$history_data .= '	<div class="history_content">';
				$history_data .= '		<p class="history_title">[图片消息]</p>';
				$history_data .= '		<div class="text_content">暂无描述！</div>';
				$history_data .= '	</div>';
				$history_data .= '</div>';
				return $history_data;
			}else{
				return '<div class="data_error">图片消息解析失败！</div>';
			}
			break;
		case 'voice':
			$voice_info = $weixin_handle->data_to_voice($material_data);
			if($voice_info){
				$voice_url = $voice_info->voice_url;
				$history_data = '';
				$history_data .= '<div class="history_data">';
				$history_data .= '	<div class="history_voice">';
				$history_data .= '		<span class="history_title">[语音消息]</span>';
				$history_data .= '		<span class="voice_object" voice_url="'.$voice_url.'"></span>';
				$history_data .= '	</div>';
				$history_data .= '</div>';
				return $history_data;
			}else{
				return '<div class="data_error">语音消息解析失败！</div>';
			}
			break;
		case 'video':
			break;
		case 'news':
			$news_info = $weixin_handle->data_to_news($material_data);
			if($news_info){
				//取第一条消息显示
				$news_main = $news_info[0];
				$news_title = $news_main->title;
				$news_picurl = $news_main->picurl;
				$news_description = $news_main->description;
				$news_url = $news_main->url?$news_main->url:WEB_URL.'index.php?app=weixin_news_detail&material_id='.$material_id.'&news_index=0';
				$history_data = '';
				$history_data .= '<div class="history_data">';
				$history_data .= '	<div class="history_img"><img src="'.$news_picurl.'" width="150px" height="83px"/></div>';
				$history_data .= '	<div class="history_content">';
				$history_data .= '		<p class="history_title">[图文消息]'.sub_str($news_title,50).'</p>';
				$history_data .= '		<div class="text_content"><a href="'.$news_url.'" target="_blank">'.get_abstract($news_description,230).'</a></div>';
				$history_data .= '	</div>';
				$history_data .= '</div>';
				return $history_data;
			}else{
				return '<div class="data_error">图文消息解析失败！</div>';
			}
			break;
		case 'music':
			$music_info = $weixin_handle->data_to_music($material_data);
			if($music_info){
				$title = $music_info->title;
				$description = $music_info->description;
				$musicurl = $music_info->musicurl;
				$hqmusicurl = $music_info->hqmusicurl;
				$singer = $music_info->singer;
				$music_pic = $music_info->music_pic?$music_info->music_pic:'images/cd_cover.jpg';
				$history_data = '';
				$history_data .= '<div class="history_data">';
				$history_data .= '	<div class="history_img"><img src="'.$music_pic.'" width="150px" height="150px"/></div>';
				$history_data .= '	<div class="history_content">';
				$history_data .= '		<p class="history_title">[音乐消息]'.sub_str($title,50).'</p>';
				$history_data .= '		<div class="text_content">';
				$history_data .= '			<p class="song_info_p">歌手：'.$music_info->singer.'</p>';
				$history_data .= '			<p class="song_info_p">';
				$history_data .= '				<span>低品质试听：</span>';
				$history_data .= '				<span class="music_object" music_url="'.$musicurl.'"></span>';
				$history_data .= '			</p>';
				if($hqmusicurl){
					$history_data .= '		<p class="song_info_p">';
					$history_data .= '			<span>高品质试听：</span>';
					$history_data .= '			<span class="music_object" music_url="'.$hqmusicurl.'"></span>';
					$history_data .= '		</p>';
				}
				$history_data .= '			<p>'.sub_str($description, 150).'</p>';
				$history_data .= '		</div>';
				$history_data .= '	</div>';
				$history_data .= '</div>';
				return $history_data;
			}else{
				return '<div class="data_error">音乐消息解析失败！</div>';
			}
			break;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/mass_send.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript" src="../swfobject/swfobject.js"></script>
<script type="text/javascript">
	//加载音乐播放器
	$(function(){
		$(".music_object").each(function(index,elem){
			this.id = 'music_object'+index;
			var music_url = $(this).attr('music_url');
			swfobject.embedSWF("<?php echo WEB_URL;?>dewplayer/dewplayer-rect.swf?mp3="+music_url,this.id,"200","20","9.0.0","../swfobject/expressInstall.swf",null,{wmode:"transparent"});
		});
		$(".voice_object").each(function(index,elem){
			this.id = 'voice_object'+index;
			var voice_url = $(this).attr('voice_url');
			swfobject.embedSWF("<?php echo WEB_URL;?>dewplayer/dewplayer-mini.swf?mp3="+voice_url,this.id,"200","20","9.0.0","../swfobject/expressInstall.swf",null,{wmode:"transparent"});
		});
	});
</script>
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>微信管理</span>
		<span>&gt;&gt;</span>
		<span>群发历史</span>
	</div>
	<div class="mass_class_bar">
		<ul class="mass_class_ul">
			<li class="mass_class_li"><a href="modules.php?menu_tag=weixin_mass_send">新建群发消息</a></li>
			<li class="mass_class_li current_li"><a href="javascript:;">已发送</a></li>
		</ul>
	</div>
	<table class="data_table music_table">
		<tr>
			<th width="50%">消息详情</th>
			<th width="20%">发送状态</th>
			<th width="15%">群发时间</th>
			<th width="15%">耗时</th>
		</tr>
		<?php if(empty($history_arr)){?>
		<tr><td colspan="4"><div class="no_data">暂无数据！</div></td></tr>
		<?php }else{?>
			<?php foreach ($history_arr as $val){?>
			<tr>
				<td width="50%"><?php echo get_history_info($val['material_id'],$val['material_type'],$val['material_data']);?></td>
				<td width="20%">
					<div class="histroy_result">
						<span class="histroy_status">发送完成</span>
						<div class="histroy_result_main">
							<div class="histroy_result_info">
								<i class="top_border_ico"></i>
								<p>发送人数：<span><?php echo $val['totle_count'];?></span></p>
								<p>成功人数：<span><?php echo $val['success_count'];?></span></p>
								<p>成功率：<span><?php echo sprintf("%.2f",$val['success_count']*100/$val['totle_count']);?>%</span></p>
							</div>
						</div>
					</div>
				</td>
				<td width="15%"><div class="send_time"><?php echo $val['start_time'];?></div></td>
				<td width="15%"><div class="cost_time"><?php echo get_cost_time($val['start_time'],$val['end_time']);?></div></td>
			</tr>
			<?php }?>
		<?php }?>
	</table>
	<?php if(!empty($history_arr)){?>
		<?php echo $page->get_page();?>
	<?php }?>
</div>
</body>
</html>