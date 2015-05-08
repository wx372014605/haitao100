<?php
//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取职位名称
$job_name = post_string('job_name');
if($job_name==''){
	admin_tip(0, '获取职位名称失败！');
}

//获取招聘人数
$recruitment_num = post_number('recruitment_num');

//获取是否显示
$is_available = post_number('is_available');

//获取岗位职责
$requirement_content = post_string('requirement_content',0);

//获取添加时间
$add_time = get_current_time();

//添加招聘信息
$insert_sql = "insert into company_recruitment (job_name,recruitment_num,is_available,requirement_content,add_time) 
values ('$job_name',$recruitment_num,$is_available,'$requirement_content','$add_time')";
$result = $mysql_handle->query($insert_sql);
if($result){
	admin_tip(1, '添加招聘信息成功！', $callback_url);
}else{
	admin_tip(0, '添加招聘信息失败！');
}