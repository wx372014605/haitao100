<?php
//获取网站id
$site_id = get_number('site_id');
if($site_id==0){
	admin_tip(0, '获取网站id失败！');
}

//标记网站
$site_sql = "update plugin_site_count set is_added=1 where site_id=$site_id limit 1";
$result = $mysql_handle->query($site_sql);
if($result){
	admin_tip(1, '标记成功！');
}else{
	admin_tip(0, '标记失败！');
}
?>