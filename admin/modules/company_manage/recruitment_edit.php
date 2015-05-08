<?php 
//获取上一个页面的url地址
$callback_url = get_pre_url();

//获取招聘id
$recruitment_id = get_number('recruitment_id');
if($recruitment_id==0){
	admin_tip(0, '获取招聘id失败！');
}

//获取招聘详细信息
$recruitment_sql  = "select * from company_recruitment where recruitment_id=$recruitment_id limit 1";
$recruitment_arr = $mysql_handle->getRow($recruitment_sql);
if($recruitment_arr==false){
	admin_tip(0, '获取招聘详细信息失败！');
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
<!-- 引入百度编辑器 -->
<script type="text/javascript" charset="utf-8" src="../ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="../ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- 引入百度编辑器 -->
<script type="text/javascript">
	//表单验证
	function form_check(){
		var job_name = $('#job_name');
		var job_name_val = $.trim(job_name.val());
		if(job_name_val==''){
			layer_tip(job_name,'请输入职位名称！');
			job_name.focus();
			return false;
		}
		
		var recruitment_num = $('#recruitment_num');
		var recruitment_num_val = $.trim(recruitment_num.val());
		if(recruitment_num_val==''){
			layer_tip(recruitment_num,'请输入招聘人数！');
			recruitment_num.focus();
			return false;
		}
		if(!my_regexp['number'].test(recruitment_num_val)){
			layer_tip(recruitment_num,'招聘人数必须为数字！');
			recruitment_num.focus();
			return false;
		}

		var requirement_content = $("#requirement_content");
		var requirement_content_val = ue.getContent();
		if(requirement_content_val==''){
			layer_tip(requirement_content,'请填写岗位职责！');
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
		<span>公司管理</span>
		<span>&gt;&gt;</span>
		<span>招聘修改</span>
	</div>
	<div class="web_content">
		<div class="content_info">
			<form action="do.php?act=recruitment_edit" method="post" onsubmit="return form_check()" enctype="multipart/form-data">
				<input type="hidden" name="recruitment_id" value="<?php echo $recruitment_id;?>"/>
				<input type="hidden" name="callback_url" value="<?php echo $callback_url;?>"/>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="left_td">职位名称：</td>
						<td class="right_td">
							<input type="text" id="job_name" name="job_name" class="data_text" value="<?php echo $recruitment_arr['job_name'];?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">招聘人数：</td>
						<td class="right_td">
							<input type="text" id="recruitment_num" name="recruitment_num" class="data_text" maxlength="4" style="width:50px;" value="<?php echo $recruitment_arr['recruitment_num'];?>"/>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">状态：</td>
						<td class="right_td">
							<label class="input_label"><input type="radio" name="is_available" value="1" <?php if($recruitment_arr['is_available']==1)echo 'checked="checked"';?>/>显示</label>
							<label class="input_label"><input type="radio" name="is_available" value="0" <?php if($recruitment_arr['is_available']==0)echo 'checked="checked"';?>/>不显示</label>
							<font class="necessary">*</font>
						</td>
					</tr>
					<tr>
						<td class="left_td">岗位职责：</td>
						<td class="right_td">
							<script id="requirement_content" name="requirement_content" type="text/plain" style="width:800px;height:300px;"><?php echo $recruitment_arr['requirement_content'];?></script>
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
<script type="text/javascript">
	var ue = UE.getEditor('requirement_content');
</script>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
</body>
</html>