<?php 
//权限验证
if(!is_super()){
	admin_tip(0, '非法操作！');
}

//获取上一个页面的url地址
$callback_url = get_pre_url();

//获取所有管理组
$group_sql = "select group_id,group_name from admin_group";
$group_arr = $mysql_handle->getRs($group_sql);
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
		var group_id = $("#group_id");
		if(group_id.val()==0){
			layer_tip(group_id,'请选择管理组！');
			group_id.focus();
			return false;
		}

		var admin_name = $("#admin_name");
		var admin_name_val = trim(admin_name.val());
		if(admin_name_val==''){
			layer_tip(admin_name,'请输入登录帐号！');
			admin_name.focus();
			return false;
		}
		if(!my_regexp['account'].test(admin_name_val)){
			layer_tip(admin_name,'登录帐号格式不正确！');
			admin_name.focus();
			return false;
		}

		var admin_passwd = $("#admin_passwd");
		var admin_passwd_val = trim(admin_passwd.val());
		if(admin_passwd_val==''){
			layer_tip(admin_passwd,'请输入登录密码！');
			admin_passwd.focus();
			return false;
		}
		if(!my_regexp['password'].test(admin_passwd_val)){
			layer_tip(admin_passwd,'登录密码格式不正确！');
			admin_passwd.focus();
			return false;
		}
		
		var email = $("#email");
		var email_val = trim(email.val());
		if(email_val==''){
			layer_tip(email,'请输入邮箱！');
			email.focus();
			return false;
		}
		if(!my_regexp['email'].test(email_val)){
			layer_tip(email,'邮箱格式不正确！');
			email.focus();
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
		<span>管理员添加</span>
	</div>
	<div class="web_content">
		<div class="content_info">
			<form action="do.php?act=admin_add" method="post" onsubmit="return form_check()" enctype="multipart/form-data">
				<input type="hidden" name="callback_url" value="<?php echo $callback_url;?>"/>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="left_td">管理组：</td>
						<td class="right_td">
							<select id="group_id" name="group_id">
								<option value="0">请选择管理组</option>
								<?php foreach ($group_arr as $val){?>
								<option value="<?php echo $val['group_id'];?>"><?php echo $val['group_name'];?></option>
								<?php }?>
							</select>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">登录帐号：</td>
						<td class="right_td">
							<input type="text" id="admin_name" name="admin_name" maxlength="18" class="data_text"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">登录密码：</td>
						<td class="right_td">
							<input type="text" id="admin_passwd" name="admin_passwd" maxlength="18" class="data_text"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">邮箱：</td>
						<td class="right_td">
							<input type="text" id="email" name="email" class="data_text"/>
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