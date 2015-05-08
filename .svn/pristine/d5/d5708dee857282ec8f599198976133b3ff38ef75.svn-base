<?php
//待删除的文件数组
$delete_arr = array();

//获取网站id
$site_id = get_number('site_id');
if($site_id==0){
	admin_tip(0, '获取网站id失败！');
}

//获取网站详细信息
$site_sql = "select * from plugin_site where site_id=$site_id limit 1";
$site_arr = $mysql_handle->getRow($site_sql);
if(!$site_arr){
	admin_tip(0, '获取网站详细信息失败！');
}
$delete_arr[] = $site_arr['site_logo'];
$delete_arr[] = str_replace('_small', '', $site_arr['site_logo']);

//删除网站
$delete_sql = "delete from plugin_site where site_id=$site_id limit 1";
$result = $mysql_handle->query($delete_sql);
if($result){
	//删除旧文件以节约资源
	foreach ($delete_arr as $val){
		if($val!=''){
			delete_file($val);
		}
	}
	admin_tip(1, '删除网站成功！');
}else{
	admin_tip(0, '删除网站失败！');
}
?>