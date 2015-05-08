<?php
//获取素材类型
$material_type = post_string('material_type');
if($material_type==''){
	admin_tip(0, '获取素材类型失败！');
}
//获取素材内容
$material_data = post_string('material_data', 0);
$material_data = strip_wraps(strip_slashes($material_data,true));

//获取添加时间
$add_time = get_current_time();

$insert_sql = "insert into weixin_material (material_type,material_data,add_time) values ('$material_type','$material_data','$add_time')";
$result = $mysql_handle->query($insert_sql);
if($result){
	admin_tip(1, '添加成功！', 'modules.php?menu_tag=weixin_news_manage');
}else{
	admin_tip(0, '添加失败！');
}
?>