<?php 
//获取上一个页面的url地址
$callback_url = get_pre_url();

//获取网站id
$site_id = get_number('site_id');
if($site_id==0){
	admin_tip(0, '获取网站id失败！');
}

//获取网站详细信息
$site_sql = "select * from plugin_site where site_id=$site_id limit 1";
$site_arr = $mysql_handle->getRow($site_sql);
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
		var site_name = $('#site_name');
		var site_name_val = $.trim(site_name.val());
		if(site_name_val==''){
			layer_tip(site_name,'请输入网站名称！');
			site_name.focus();
			return false;
		}

		var site_url = $('#site_url');
		var site_url_val = $.trim(site_url.val());
		if(site_url_val==''){
			layer_tip(site_url,'请输入网站链接！');
			site_url.focus();
			return false;
		}
		if(!my_regexp['url'].test(site_url_val)){
			layer_tip(site_url,'网站链接格式不正确！');
			site_url.focus();
			return false;
		}

		var site_logo = $("#site_logo");
		var site_logo_val = site_logo.val();
		if(site_logo_val!=''&&!is_img(site_logo_val)){
			layer_tip(account_logo,'网站logo必须为图片格式！');
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
		<span>交易管理</span>
		<span>&gt;&gt;</span>
		<span>网站修改</span>
	</div>
	<div class="web_content">
		<div class="content_info">
			<form action="do.php?act=site_edit" method="post" onsubmit="return form_check()" enctype="multipart/form-data">
				<input type="hidden" name="site_id" value="<?php echo $site_id;?>"/>
				<input type="hidden" name="callback_url" value="<?php echo $callback_url;?>"/>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="left_td">网站名称：</td>
						<td class="right_td">
							<input type="text" id="site_name" name="site_name" class="data_text" value="<?php echo $site_arr['site_name'];?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">网站链接：</td>
						<td class="right_td">
							<input type="text" id="site_url" name="site_url" class="data_text" style="width:300px;" value="<?php echo $site_arr['site_url'];?>"/>
							<font class="necessary">*</font>
							<font class="tip_font">以http://开头</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">网站logo：</td>
						<td class="right_td">
							<img width="100px" height="35px" src="<?php echo $site_arr['site_logo']?WEB_URL.$site_arr['site_logo']:'images/nopic.png';?>" onerror="mg_error(this)">
							<input type="file" id="site_logo" name="site_logo"/>
							<font class="necessary">*</font>
							<font class="tip_font">建议大小100*35</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">状态：</td>
						<td class="right_td">
							<label class="input_label"><input type="radio" name="is_available" value="1" <?php if($site_arr['is_available']==1)echo 'checked="checked"';?>/>支持</label>
							<label class="input_label"><input type="radio" name="is_available" value="0" <?php if($site_arr['is_available']==0)echo 'checked="checked"';?>/>不支持</label>
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