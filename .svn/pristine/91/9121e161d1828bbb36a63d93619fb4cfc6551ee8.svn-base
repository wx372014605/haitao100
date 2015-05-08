<?php
//获取素材id
$material_id = post_number('material_id');
if($material_id==0){
	admin_tip(0, '获取素材id失败！');
}

//获取素材类型
$material_type = post_string('material_type');
if($material_type==''){
	admin_tip(0, '获取素材类型失败！');
}
//获取素材内容
$material_data = post_string('material_data', 0);
$material_data = strip_wraps(strip_slashes($material_data,true));

$update_sql = "update weixin_material set material_type='$material_type',material_data='$material_data' where material_id=$material_id limit 1";
$result = $mysql_handle->query($update_sql);
if($result){
	admin_tip(1, '更新成功！', 'modules.php?menu_tag=weixin_news_manage');
}else{
	admin_tip(0, '更新失败！');
}
?>