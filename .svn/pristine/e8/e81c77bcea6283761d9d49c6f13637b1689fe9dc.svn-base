<?php 

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
<link rel="stylesheet" type="text/css" href="css/talk_message.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript">
	var iframe_tag = 'talk_window';
	$(function(){
		$("#talk_class_ul .talk_class_li").click(function(){
			$("#talk_class_ul .talk_class_li").removeClass("current_li");
			$(this).addClass("current_li");
			iframe_tag = $(this).attr('iframe_tag');
			window.frames['user_iframe'].location.reload();
		});
	});
</script>
</head>
<body>
<div class="content_main">
	<div class="talk_class_bar">
		<ul id="talk_class_ul">
			<li id="talk_window" class="talk_class_li current_li" iframe_tag="talk_window">聊天窗口</li>
			<li id="talk_history" class="talk_class_li" iframe_tag="talk_history">历史记录</li>
		</ul>
	</div>
	<div class="talk_message_container">
		<div class="talk_message_right">
			<iframe id="user_iframe" name="user_iframe" src="modules.php?menu_tag=talk_user" scrolling="auto" frameborder="0"></iframe>
		</div>
		<div class="talk_message_left">
			<iframe id="talk_iframe" name="talk_iframe" src="" scrolling="auto" frameborder="0"></iframe>
		</div>
	</div>
</div>
</body>
</html>