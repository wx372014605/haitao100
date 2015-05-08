<?php 
//获取上一个页面的url地址
$callback_url = get_pre_url();

//获取素材id
$material_id = get_number('material_id');
if($material_id==0){
	admin_tip(0, '获取素材id失败！');
}

//获取音乐素材详情
$music_sql = "select * from weixin_material where material_id=$material_id limit 1";
$music_arr = $mysql_handle->getRow($music_sql);
if($music_arr==false){
	admin_tip(0, '获取音乐素材详情失败！');
}
//解析音乐素材
$music_info = $weixin_handle->data_to_music($music_arr['material_data']);
if(!$music_info){
	admin_tip(0, '解析音乐素材失败！');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/material.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript" src="../js/config.js"></script>
<script type="text/javascript" src="../swfobject/swfobject.js"></script>
<script type="text/javascript">
	//加载音乐播放器
	$(function(){
		$(".music_object").each(function(){
			var object_id = this.id;
			var music_url = $(this).attr('music_url');
			swfobject.embedSWF("<?php echo WEB_URL;?>dewplayer/dewplayer-rect.swf?mp3="+music_url,object_id,"200","20","9.0.0","../swfobject/expressInstall.swf",null,{wmode:"transparent"});
		});
	});
</script>
<script type="text/javascript">
	$(function(){
		//绑定选择歌曲来源的事件
		$("[name='music_source']").click(function(){
			if($(this).val()==0){
				$(".local_upload").show();
				$(".remote_url").hide();
			}else{
				$(".local_upload").hide();
				$(".remote_url").show();
			}
		});
	});

	//表单验证
	function form_check(){
		var title = $("#title");
		if($.trim(title.val())==''){
			layer_tip(title,'请输入歌曲名称！');
			title.focus();
			return false;
		}

		var music_pic = $("#music_pic");
		var music_pic_val = $.trim(music_pic.val());
		if(music_pic_val!='' && !is_img(music_pic_val)){
			layer_tip(music_pic,'封面图片格式不正确！');
			return false;
		}

		var music_source = $("[name='music_source']:checked");
		var music_source_val = $.trim(music_source.val());
		if(music_source_val==0){//本地上传
			var music_file = $("#music_file");
			var music_file_val = $.trim(music_file.val());
			if(music_file_val!='' && !is_file(music_file_val,['mp3'])){
				layer_tip(music_file,'歌曲文件格式不正确！');
				return false;
			}

			var hq_music_file = $("#hq_music_file");
			var hq_music_file_val = $.trim(hq_music_file.val());
			if(hq_music_file_val!='' && !is_file(hq_music_file_val,['mp3'])){
				layer_tip(hq_music_file,'高品质歌曲文件格式不正确！');
				return false;
			}
		}else if(music_source_val==1){//远程链接
			var musicurl = $("#musicurl");
			var musicurl_val = $.trim(musicurl.val());
			if(musicurl_val==''){
				layer_tip(musicurl,'请输入歌曲链接！');
				musicurl.focus();
				return false;
			}else if(!my_regexp['url'].test(musicurl_val)){
				layer_tip(musicurl,'歌曲链接格式不正确！');
				musicurl.focus();
				return false;
			}

			var hqmusicurl = $("#hqmusicurl");
			var hqmusicurl_val = $.trim(hqmusicurl.val());
			if(hqmusicurl_val!='' && !my_regexp['url'].test(hqmusicurl_val)){
				layer_tip(hqmusicurl_val,'高品质歌曲链接格式不正确！');
				hqmusicurl_val.focus();
				return false;
			}
		}
	}
</script>
</head>
<body>
<div class="content_main">
	<div class="web_content">
		<div class="content_info">
			<form action="do.php?act=weixin_music_edit" method="post" onsubmit="return form_check()" enctype="multipart/form-data">
				<input type="hidden" name="callback_url" value="<?php echo $callback_url;?>"/>
				<input type="hidden" name="material_type" value="music"/>
				<input type="hidden" name="material_id" value="<?php echo $material_id;?>"/>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="left_td">歌曲名称：</td>
						<td class="right_td">
							<input type="text" id="title" name="title" maxlength="15" class="data_text" value="<?php echo $music_info->title;?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">演唱者：</td>
						<td class="right_td">
							<input type="text" id="singer" name="singer" maxlength="15" class="data_text" value="<?php echo $music_info->singer;?>"/>
						</td>
					</tr>
					<tr>
						<td class="left_td">封面图片：</td>
						<td class="right_td">
							<?php if($music_info->music_pic){?>
							<img src="<?php echo $music_info->music_pic;?>" width="100px" height="100px" onerror="mg_error(this)"/>
							<?php }?>
							<input type="file" id="music_pic" name="music_pic"/>
							<font class="tip_font">建议大小200*200</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">歌曲来源：</td>
						<td class="right_td">
							<label>本地上传<input class="common_radio" type="radio" name="music_source" value="0" <?php if($music_info->music_source==0)echo 'checked';?>/></label>
							<label>远程链接<input class="common_radio" type="radio" name="music_source" value="1" <?php if($music_info->music_source==1)echo 'checked';?>//></label>
						</td>
					</tr>
					<tr class="local_upload" <?php if($music_info->music_source==1)echo 'style="display:none;"';?>>
						<td class="left_td">上传歌曲：</td>
						<td class="right_td">
							<p class="song_info_p">
								<span id="music_object1" class="music_object" music_url="<?php echo $music_info->musicurl;?>"></span>
							</p>
							<input type="file" id="music_file" name="music_file"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr class="local_upload" <?php if($music_info->music_source==1)echo 'style="display:none;"';?>>
						<td class="left_td">高品质歌曲：</td>
						<td class="right_td">
							<?php if($music_info->hqmusicurl){?>
							<p class="song_info_p">
								<span id="music_object2" class="music_object" music_url="<?php echo $music_info->hqmusicurl;?>"></span>
							</p>
							<?php }?>
							<input type="file" id="hq_music_file" name="hq_music_file"/>
							<font class="tip_font">高品质音乐文件，wifi环境优先使用该链接播放音乐</font>
						</td>
					</tr>
					<tr class="remote_url" <?php if($music_info->music_source==0)echo 'style="display:none;"';?>>
						<td class="left_td">歌曲链接：</td>
						<td class="right_td">
							<p class="song_info_p">
								<span id="music_object3" class="music_object" music_url="<?php echo $music_info->musicurl;?>"></span>
							</p>
							<input type="text" id="musicurl" name="musicurl" maxlength="100" class="data_text" style="width:300px;" value="<?php echo $music_info->musicurl;?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr class="remote_url" <?php if($music_info->music_source==0)echo 'style="display:none;"';?>>
						<td class="left_td">高品质歌曲：</td>
						<td class="right_td">
							<?php if($music_info->hqmusicurl){?>
							<p class="song_info_p">
								<span id="music_object4" class="music_object" music_url="<?php echo $music_info->hqmusicurl;?>"></span>
							</p>
							<?php }?>
							<input type="text" id="hqmusicurl" name="hqmusicurl" maxlength="100" class="data_text" style="width:300px;" value="<?php echo $music_info->hqmusicurl;?>"/>
							<font class="tip_font">高品质音乐链接，wifi环境优先使用该链接播放音乐</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">歌曲描述：</td>
						<td class="right_td">
							<textarea class="data_area" id="description" name="description"><?php echo $music_info->description;?></textarea>
						</td>
					</tr>
					<tr>
						<td class="left_td"></td>
						<td class="right_td"><input class="submit_button" type="submit" value="提交" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
</body>
</html>