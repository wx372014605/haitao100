<?php
$site_domain = post_string('site_domain');
if($site_domain==''){
	ajax_return(0, '获取网站链接地址失败！');
}
$site_domain .= 'http://'.preg_replace('^http:\/\/', '', $site_domain);
$site_info = parse_url($site_domain);
if($site_info){
	$site_domain = $site_info['host'];
	//判断该域名是否存在
	$check_sql = "select site_id from plugin_site_count where site_domain='$site_domain' limit 1";
	$check_arr = $mysql_handle->getRow($check_sql);
	if($check_arr){
		$sql = "update plugin_site_count set submit_count=submit_count+1 where site_domain='$site_domain' limit 1";
	}else{
		$sql = "insert into plugin_site_count (site_domain) values ('$site_domain')";
	}
	$result = $mysql_handle->query($sql);
	if($result){
		ajax_return(1, '网站提交成功，感谢您的反馈！');
	}else{
		ajax_return(0, '未知错误,网站提交失败！');
	}
}else{
	ajax_return(0, '网站链接地址格式不正确！');
}
?>