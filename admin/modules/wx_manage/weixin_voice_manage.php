<?php 
//初始化分页类
$page = new Paging();

//获取语音素材列表
$voice_sql = "select * from weixin_material where material_type='voice' and is_delete=0 order by material_id desc";
$voice_arr = $page->get_record($voice_sql);
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
<script type="text/javascript" src="../swfobject/swfobject.js"></script>
<!-- 引入ajax无刷新上传文件插件 -->
<script type="text/javascript" src="../js/ajaxfileupload.js"></script>
<!-- 引入ajax无刷新上传文件插件 -->
<script type="text/javascript">
	//加载音乐播放器
	$(function(){
		$(".voice_object").each(function(){
			var object_id = this.id;
			var voice_url = $(this).attr('voice_url');
			swfobject.embedSWF("http:\/\/localhost/jfy_weixin/dewplayer/dewplayer-mini.swf?mp3="+voice_url,object_id,"160","20","9.0.0","../swfobject/expressInstall.swf",null,{wmode:"transparent"});
		});
	});
</script>
<script type="text/javascript">
	var voice_count = <?php echo count($voice_arr);?>
	//异步上传文件
	function ajaxFileUpload(){
		$.ajaxFileUpload({
			url:'do.php?act=weixin_voice_upload',
			secureuri:false,
			fileElementId:'voice_file',
			dataType: 'json',
			timeout:3000,
			cache:false,
			async:false,
			success: function (result_obj){
				$("#voice_file").clearFileInput();
				if(result_obj.status==1){
					var attach = result_obj.attach;
					var material_id = attach.material_id;
					var uploaded_url = attach.uploaded_url;
					//语音列表html
					var voice_list = '<div class="voice_list">';
					voice_list += '		<div class="voice_cover">';
					voice_list += '			<a href="'+uploaded_url+'" target="_blank">';
					voice_list += '				<img src="images/cd_cover.jpg" onerror="img_error(this)"/>';
					voice_list += '			</a>';
					voice_list += '		</div>';
					voice_list += '		<div class="voice_operate">';
					voice_list += '			<a class="voice_download" title="下载" href="'+uploaded_url+'" target="_blank"></a>';
					voice_list += '			<a class="voice_delete" title="删除" href="do.php?act=weixin_voice_del&material_id='+material_id+'" onclick="return confirm(\'确定删除此素材？\');"></a>';
					voice_list += '			<span class="voice_audition">';
					voice_list += '				<div id="voice'+voice_count+'" class="voice_object" voice_url="'+uploaded_url+'"></div>';
					voice_list += '			</span>';
					voice_list += '		</div>';
					voice_list += '	</div>';
					$(".voice_main").prepend(voice_list).find(".no_data").remove();
					//加载音乐播放器
					var object_id = 'voice'+voice_count;
					var voice_url = uploaded_url;
					swfobject.embedSWF("http:\/\/localhost/jfy_weixin/dewplayer/dewplayer-mini.swf?mp3="+voice_url,object_id,"160","20","9.0.0","../swfobject/expressInstall.swf",null,{wmode:"transparent"});
					//增加语音素材数量
					voice_count += 1;
				}else{
					layer_tip($("#voice_file"),result_obj.message);
				}
			}
		})
	}

	//清空file HTML控件的值
    $.fn.clearFileInput = function(){
        return this.each(function(){
            if (/MSIE/.test(navigator.userAgent)){
                $(this).replaceWith($(this).clone(true));
            }else{
                $(this).val('');
            }
        });
    };
</script>
</head>
<body>
<div class="content_main">
	<div class="data_content">
		<div class="voice_upload">
			<a id="voice_upload" href="javascript:;">
				<font>上传</font>
				<input type="file" id="voice_file" name="voice_file" onchange="ajaxFileUpload()"/>
			</a>
			<span class="voice_upload_tip">大小:不超过5M,　长度:不超过60s,　格式:mp3</span>
		</div>
		<div class="voice_main">
			<?php if(empty($voice_arr)){?>
			<div class="no_data">暂无数据！</div>
			<?php }else{?>
				<?php foreach ($voice_arr as $key=>$val){?>
				<?php $voice_info = $weixin_handle->data_to_voice($val['material_data']);?>
					<?php if($voice_info){?>
					<div class="voice_list">
						<div class="voice_cover">
							<img src="images/cd_cover.jpg" onerror="img_error(this)"/>
						</div>
						<div class="voice_operate">
							<a class="voice_download" title="下载" href="<?php echo $voice_info->voice_url;?>" target="_blank"></a>
							<a class="voice_delete" title="删除" href="do.php?act=weixin_voice_del&material_id=<?php echo $val['material_id'];?>" onclick="return confirm('确定删除此素材？');"></a>
							<span class="voice_audition">
								<div id="voice<?php echo $key;?>" class="voice_object" voice_url="<?php echo $voice_info->voice_url;?>"></div>
							</span>
						</div>
					</div>
					<?php }?>
				<?php }?>
			<?php }?>
		</div>
		<?php echo $page->get_page();?>
	</div>
</div>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
</body>
</html>