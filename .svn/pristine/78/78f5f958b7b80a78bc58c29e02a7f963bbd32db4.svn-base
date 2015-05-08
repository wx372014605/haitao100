<?php 
//获取素材类型(自动切换到该素材选项卡)
$material_type = get_string('material_type');
if($material_type==""){
	$material_type = "news";
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
<script type="text/javascript">
	var material_type = "<?php echo $material_type;?>";
	$(function(){
		$(".material_class_li").click(function(){
			$(".material_class_li").removeClass("current_li");
			$(this).addClass("current_li");
			var iframe_src = $(this).attr('iframe_src');
			$("#material_content").attr('src',iframe_src);
		});
		/*
		$("#material_content").load(function(){
			//iframe自适应高度
			$(this).height($(this).contents().height());
		});
		*/

		var current_li = $("#"+material_type+"_material");
		current_li.addClass("current_li");
		var iframe_src = current_li.attr('iframe_src');
		$("#material_content").attr('src',iframe_src);
	});

	//iframe自适应高度
	function set_iframe_height(iframe_ob){
		iframe_ob.height(iframe_ob.contents().height());
	}
</script>
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>微信管理</span>
		<span>&gt;&gt;</span>
		<span>素材管理</span>
	</div>
	<div class="material_class_bar">
		<ul class="material_class_ul">
			<li id="news_material" class="material_class_li" iframe_src="modules.php?menu_tag=weixin_news_manage">图文消息</li>
			<li id="image_material" class="material_class_li" iframe_src="modules.php?menu_tag=weixin_image_manage">图片</li>
			<li id="voice_material" class="material_class_li" iframe_src="modules.php?menu_tag=weixin_voice_manage">语音</li>
			<li id="video_material" class="material_class_li" iframe_src="modules.php?menu_tag=weixin_video_manage">视频</li>
			<li id="music_material" class="material_class_li" iframe_src="modules.php?menu_tag=weixin_music_manage">音乐</li>
		</ul>
	</div>
	<div class="material_box">
		<iframe onload="set_iframe_height($(this));" id="material_content" name="material_content" src="" frameborder="0" scrolling="auto"></iframe>
	</div>
</div>
</body>
</html>