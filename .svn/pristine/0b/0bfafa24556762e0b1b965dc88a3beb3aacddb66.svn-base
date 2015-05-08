<?php
//权限验证
if(!is_super()){
	admin_tip(0, '非法操作！');
}

//获取管理员id
$aid = get_number('admin_id');
if($aid==0){
	admin_tip(0, '获取管理员id失败！');
}

//删除管理员
$delete_sql = "delete from admins where admin_id=$aid limit 1";
$result = $mysql_handle->query_sql($delete_sql);
if($result){
	admin_tip(1, '删除管理员成功！');
}else{
	admin_tip(0, '删除管理员失败！');
}
?>