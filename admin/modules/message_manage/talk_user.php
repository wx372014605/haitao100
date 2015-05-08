<?php 
//获取已注册的用户列表
$user_sql = "select user_id,add_time from users limit 15";
$user_arr = $mysql_handle->getRs($user_sql);


//获取最近发送消息的用户列表
$history_sql = "select from_user_id,send_time from talk_message where to_admin_id=1 group by (from_user_id) order by message_id desc limit 15";
$history_arr = $mysql_handle->getRs($history_sql);
if($history_arr){
	$has_history = true;
}else{
	$has_history = false;
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
<link rel="stylesheet" type="text/css" href="css/talk_message.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript">
	$(function(){
		//绑定事件
		$(".talk_user_ul .talk_user_li").click(function(){
			$(".talk_user_ul .talk_user_li").removeClass("current_li");
			$(this).addClass("current_li");
			var user_id = $(this).attr('user_id');
			$('#talk_iframe',parent.document).attr('src','modules.php?menu_tag='+parent.iframe_tag+'&user_id='+user_id);
		});
		$("#user_class_ul .user_class_li").click(function(){
			$("#user_class_ul .user_class_li").removeClass("current_li");
			$(this).addClass("current_li");
			var show_div = $(this).attr('show_div');
			var show_div_obj = $('#'+show_div);
			$('.talk_user_list').hide();
			show_div_obj.show();
			var select_li = show_div_obj.find('.current_li');
			if(select_li.length==0){
				show_div_obj.find('.talk_user_li:first').click();
			}
		});
		page_load();
	});

	//页面初始化加载
	function page_load(){
		var has_history = <?php echo $has_history?'true':'false'?>;
		if(has_history){
			$('#user_class_ul #history_user').click();
		}else{
			$('#user_class_ul #normal_user').click();
		}
	}
</script>
</head>
<body>
	<div class="user_class_bar">
		<ul id="user_class_ul">
			<li id="normal_user" class="user_class_li" show_div="normal_user_list"><a></a></li>
			<li id="history_user" class="user_class_li" show_div="history_user_list"><a></a></li>
		</ul>
	</div>
	<div id="normal_user_list" class="talk_user_list">
		<ul class="talk_user_ul">
			<?php foreach ($user_arr as $val){?>
			<li class="talk_user_li" user_id="<?php echo $val['user_id'];?>">
				<span class="user_name">用户<?php echo $val['user_id'];?></span>
				<span class="user_time"><?php echo $val['add_time'];?></span>
			</li>
			<?php }?>
		</ul>
	</div>
	<div id="history_user_list" class="talk_user_list">
		<ul class="talk_user_ul">
			<?php foreach ($history_arr as $val){?>
			<li class="talk_user_li" user_id="<?php echo $val['from_user_id'];?>">
				<span class="user_name">用户<?php echo $val['from_user_id'];?></span>
				<span class="user_time"><?php echo $val['send_time'];?></span>
			</li>
			<?php }?>
		</ul>
	</div>
</body>
</html>