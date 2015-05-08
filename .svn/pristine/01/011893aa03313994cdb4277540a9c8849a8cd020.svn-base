<?php 
//获取上一个页面的url地址
$callback_url = get_pre_url();
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
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript" src="../js/config.js"></script>
<script type="text/javascript">
	//表单验证
	function form_check(){
		var old_passwd = $("#old_passwd");
		var old_passwd_val = trim(old_passwd.val());
		if(old_passwd_val==''){
			layer_tip(old_passwd,'请输入原密码！');
			old_passwd.focus();
			return false;
		}
		if(!my_regexp['password'].test(old_passwd_val)){
			layer_tip(old_passwd,'原密码格式不正确！');
			old_passwd.focus();
			return false;
		}

		var new_passwd = $("#new_passwd");
		var new_passwd_val = trim(new_passwd.val());
		if(new_passwd_val==''){
			layer_tip(new_passwd,'请输入新密码！');
			new_passwd.focus();
			return false;
		}
		if(!my_regexp['password'].test(new_passwd_val)){
			layer_tip(new_passwd,'新密码格式不正确！');
			new_passwd.focus();
			return false;
		}

		var re_passwd = $("#re_passwd");
		var re_passwd_val = trim(re_passwd.val());
		if(re_passwd_val==''){
			layer_tip(re_passwd,'请输入新密码！');
			re_passwd.focus();
			return false;
		}
		if(re_passwd_val!=new_passwd_val){
			layer_tip(re_passwd,'两次输入的密码不一致！');
			re_passwd.focus();
			return false;
		}
	}
</script>
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>帐号管理</span>
		<span>&gt;&gt;</span>
		<span>修改密码</span>
	</div>
	<div class="web_content">
		<div class="content_info">
			<form action="do.php?act=password_modify" method="post" onsubmit="return form_check()">
				<input type="hidden" name="callback_url" value="<?php echo $callback_url;?>"/>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="left_td">请输入原密码：</td>
						<td class="right_td">
							<input type="password" id="old_passwd" name="old_passwd" maxlength="18" class="data_text"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">请输入新密码：</td>
						<td class="right_td">
							<input type="password" id="new_passwd" name="new_passwd" maxlength="18" class="data_text"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">请输入确认密码：</td>
						<td class="right_td">
							<input type="password" id="re_passwd" name="re_passwd" maxlength="18" class="data_text"/>
							<font class="necessary">*</font>
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