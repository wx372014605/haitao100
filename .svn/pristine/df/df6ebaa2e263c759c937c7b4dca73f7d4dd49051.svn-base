<?php
//获取招聘id
$recruitment_id = get_number('recruitment_id');
if($recruitment_id==0){
	admin_tip(0, '获取招聘id失败！');
}

//删除招聘信息
$delete_sql = "delete from company_recruitment where recruitment_id=$recruitment_id limit 1";
$result = $mysql_handle->query($delete_sql);
if($result){
	admin_tip(1, '删除招聘信息成功！');
}else{
	admin_tip(0, '删除招聘信息失败！');
}