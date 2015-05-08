<?php
//获取网站id
$site_id = get_number('site_id');
if($site_id==0){
	ajax_return(0, '获取网站id失败！');
}

//获取网站信息
$site_sql = "select * from plugin_site where site_id=$site_id limit 1";
$site_arr = $mysql_handle->getRow($site_sql);
if($site_arr){
	ajax_return(1, '获取网站信息成功！', $site_arr);
}else{
	ajax_return(0, '获取网站信息失败！');
}
?>