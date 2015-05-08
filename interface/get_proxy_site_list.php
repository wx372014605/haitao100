<?php
//获取可以代理的网站列表
$site_sql = "select site_id,site_name,site_url,site_domin from proxy_site where is_available=1";
$site_arr = $mysql_handle->getRs($site_sql);
ajax_return(1, '获取可以代理的网站列表成功！', $site_arr);
?>