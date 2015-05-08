<?php 
//初始化分页类
$page = new Paging();

//获取音乐素材列表
$music_sql = "select * from weixin_material where material_type='music' and is_delete=0 order by material_id desc";
$music_arr = $page->get_record($music_sql);
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
</head>
<body>
<div class="content_main">
	<div class="data_content">
		<div class="data_add">
			<a href="modules.php?menu_tag=weixin_music_add">添加素材</a>
		</div>
		<table class="music_table">
			<?php if(empty($music_arr)){?>
			<tr><td colspan="4"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($music_arr as $key=>$val){?>
				<?php $music_info = $weixin_handle->data_to_music($val['material_data']);?>
				<?php if($music_info){?>
				<tr>
					<td width="180px" align="center">
						<div class="music_pic"><img src="<?php echo $music_info->music_pic?$music_info->music_pic:'images/cd_cover.jpg'?>" onerror="img_error(this)"/></div>
					</td>
					<td width="250px">
						<p class="song_info_p">歌曲名称：<?php echo $music_info->title;?></p>
						<p class="song_info_p">歌手：<?php echo $music_info->singer;?></p>
						<p class="song_info_p">
							<span>低品质试听：</span>
							<span id="low_music<?php echo $key;?>" class="music_object" music_url="<?php echo $music_info->musicurl;?>"></span>
						</p>
						<?php if($music_info->hqmusicurl){?>
						<p class="song_info_p">
							<span>高品质试听：</span>
							<span id="high_music<?php echo $key;?>" class="music_object" music_url="<?php echo $music_info->hqmusicurl;?>"></span>
						</p>
						<?php }?>
					</td>
					<td class="music_description"><?php echo $music_info->description?get_abstract($music_info->description,500):'暂无歌曲描述！';?></td>
					<td width="100px" align="center">
						<a href="modules.php?menu_tag=weixin_music_edit&material_id=<?php echo $val['material_id'];?>">修改</a>
						<a href="do.php?act=weixin_music_del&material_id=<?php echo $val['material_id'];?>" onclick="return confirm('是否要删除这条消息？');">删除</a>
					</td>
				</tr>
				<?php }?>
			<?php }?>
			<?php }?>
		</table>
		<?php if(!empty($music_arr)){?>
			<?php echo $page->get_page();?>
		<?php }?>
	</div>
</div>
</body>
</html>