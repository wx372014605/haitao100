<?php
require_once (WEB_ROOT.'class/img_upload.php');

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取网站名称
$site_name = post_string('site_name');
if($site_name==''){
	admin_tip(0, '获取网站名称失败！');
}

//获取网站链接
$site_url = post_string('site_url');
if($site_url==''){
	admin_tip(0, '获取网站链接失败！');
}
if(!preg_match($my_regexp['url'], $site_url)){
	admin_tip(0, '网站链接格式不正确！');
}

//获取该网站是否已经支持的标记
$is_available = post_number('is_available');

//上传网站logo
$upload_dir = 'uploadfiles/plugin/';
$img_upload = new ImgUpload('site_logo', $upload_dir);
$img_upload->create_thumb(100,35);
$img_info = $img_upload->upload();
if($img_info['status']==0){
	$site_logo = $img_info['thumb_name'];
}else{
	admin_tip(0, $img_info['message']);
}

//添加网站
$insert_sql = "insert into plugin_site (site_name,site_url,site_logo,is_available) 
values ('$site_name','$site_url','$site_logo',$is_available)";
$result = $mysql_handle->query($insert_sql);
if($result){
	admin_tip(1, '添加网站成功！',$callback_url);
}else{
	admin_tip(0, '添加网站失败！');
}
?>