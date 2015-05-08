<?php
//获取上一个页面的url地址
$callback_url = get_pre_url();

//获取素材id
$material_id = get_number('material_id');
if($material_id==0){
	admin_tip(0, '获取素材id失败！');
}

//删除语音消息(逻辑删除)
$delete_sql = "update weixin_material set is_delete=1 where material_id=$material_id limit 1";
$result = $mysql_handle->query($delete_sql);
if($result){
	admin_tip(1, '删除成功！');
}else{
	admin_tip(0, '删除失败！');
}
?>