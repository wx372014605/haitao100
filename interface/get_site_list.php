<?php
//获取网站分类id
$class_id = get_number('class_id');

//获取插件支持的网站列表
$site_sql = "select * from plugin_site where is_available=1";
if($class_id==0){
	$site_sql .= " and is_hot=1";
}else{
	$site_sql .= " and class_id=$class_id";
}
$site_arr = $mysql_handle->getRs($site_sql);
ajax_return(1, '获取插件支持的网站列表成功！', $site_arr);
?>