<?php 
//获取上一个页面的url地址
$callback_url = get_pre_url();

//获取帐号id
$account_id = get_number('account_id');
if($account_id==0){
	admin_tip(0, '获取帐号id失败！');
}

//获取帐号信息
$account_sql = "select * from weixin_account where account_id=$account_id limit 1";
$account_arr = $mysql_handle->getRow($account_sql);
if($account_sql==false){
	admin_tip(0, '获取帐号信息失败！');
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
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript" src="../js/config.js"></script>
<script type="text/javascript">
	//表单验证
	function form_check(){
		var platform_id = $("#platform_id");
		var platform_id_val = trim(platform_id.val());
		if(platform_id_val==''){
			layer_tip(platform_id,'请输入平台id！');
			platform_id.focus();
			return false;
		}
		if(!my_regexp['platform_id'].test(platform_id_val)){
			layer_tip(platform_id,'平台id格式不正确！');
			platform_id.focus();
			return false;
		}

		var account_logo = $("#account_logo");
		var account_logo_val = account_logo.val();
		if(account_logo_val!='' && !is_img(account_logo_val)){
			layer_tip(account_logo,'帐号logo必须为图片格式！');
			return false;
		}

		var account_name = $("#account_name");
		if(trim(account_name.val())==''){
			layer_tip(account_name,'请输入帐号名称！');
			account_name.focus();
			return false;
		}

		var app_id = $("#app_id");
		var app_id_val = trim(app_id.val());
		if(app_id_val!=''&&!my_regexp['app_id'].test(app_id_val)){
			layer_tip(app_id,'appId格式不正确！');
			app_id.focus();
			return false;
		}

		var app_secret = $("#app_secret");
		var app_secret_val = trim(app_secret.val());
		if(app_secret_val!=''&&!my_regexp['app_secret'].test(app_secret_val)){
			layer_tip(app_secret,'appSecret格式不正确！');
			app_secret.focus();
			return false;
		}

		var encoding_aes_key = $('#encoding_aes_key');
		var encoding_aes_key_val = $.trim(encoding_aes_key.val());
		if(encoding_aes_key_val!='' && !my_regexp['encoding_aes_key'].test(encoding_aes_key_val)){
			layer_tip(encoding_aes_key,'EncodingAesKey格式不正确！');
			encoding_aes_key.focus();
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
		<span>帐号修改</span>
	</div>
	<div class="web_content">
		<div class="content_info">
			<form action="do.php?act=weixin_account_edit" method="post" onsubmit="return form_check()" enctype="multipart/form-data">
				<input type="hidden" name="callback_url" value="<?php echo $callback_url;?>"/>
				<input type="hidden" name="account_id" value="<?php echo $account_id;?>"/>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="left_td">帐号类型：</td>
						<td class="right_td">
							<select id="type" name="type">
								<option value="0" <?php if($account_arr['type']==0) echo "selected";?>>普通帐号</option>
								<option value="1" <?php if($account_arr['type']==1) echo "selected";?>>高级帐号</option>
							</select>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">平台id：</td>
						<td class="right_td">
							<input type="text" id="platform_id" name="platform_id" maxlength="15" class="data_text" value="<?php echo $account_arr['platform_id'];?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">帐号logo：</td>
						<td class="right_td">
							<img width="80px" height="80px" src="<?php echo $account_arr['account_logo']?$account_arr['account_logo']:'images/nopic.png';?>" onerror="mg_error(this)">
							<input type="file" id="account_logo" name="account_logo"/>
							<font class="necessary">*</font>
							<font class="tip_font">建议大小100*100</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">帐号名称：</td>
						<td class="right_td">
							<input type="text" id="account_name" name="account_name" class="data_text" value="<?php echo $account_arr['account_name'];?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">AppId：</td>
						<td class="right_td">
							<input type="text" id="app_id" name="app_id" maxlength="18" class="data_text" value="<?php echo $account_arr['app_id'];?>"/>
							<font class="tip_font">服务号请务必填写下面两项</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">AppSecret：</td>
						<td class="right_td">
							<input type="text" id="app_secret" name="app_secret" maxlength="32" style="width:300px;" class="data_text" value="<?php echo $account_arr['app_secret'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="left_td">EncodingAesKey：</td>
						<td class="right_td">
							<input type="text" id="encoding_aes_key" name="encoding_aes_key" maxlength="43" style="width:360px;" class="data_text" value="<?php echo $account_arr['encoding_aes_key'];?>"/>
							<font class="tip_font">如果启用了微信消息加密，则需要填写此参数</font>
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