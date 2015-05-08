<?php
//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取招聘id
$recruitment_id = post_number('recruitment_id');
if($recruitment_id==0){
	admin_tip(0, '获取招聘id失败！');
}

//获取招聘详细信息
$recruitment_sql  = "select * from company_recruitment where recruitment_id=$recruitment_id limit 1";
$recruitment_arr = $mysql_handle->getRow($recruitment_sql);
if($recruitment_arr==false){
	admin_tip(0, '获取招聘详细信息失败！');
}

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

//修改招聘信息
$update_sql = "update company_recruitment set job_name='$job_name',recruitment_num=$recruitment_num,is_available=$is_available,
requirement_content='$requirement_content' where recruitment_id=$recruitment_id limit 1";
$result = $mysql_handle->query($update_sql);
if($result){
	admin_tip(1, '修改招聘信息成功！', $callback_url);
}else{
	admin_tip(0, '修改招聘信息失败！');
}