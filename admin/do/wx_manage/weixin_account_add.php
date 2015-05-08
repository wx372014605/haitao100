<?php
require_once (WEB_ROOT.'class/img_upload.php');

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取帐号类型
$type = post_number('type');

//获取平台id
$platform_id = post_string('platform_id');
if($platform_id==''){
	admin_tip(0, '获取平台id失败！');
}
if(!preg_match($my_regexp['platform_id'], $platform_id)){
	admin_tip(0, '平台id格式不正确！');
}

//获取帐号名称
$account_name = post_string('account_name');
if($account_name==''){
	admin_tip(0, '获取帐号名称失败！');
}

//获取AppId
$app_id = post_string('app_id');
if($app_id!='' and !preg_match($my_regexp['app_id'], $app_id)){
	admin_tip(0, 'AppId格式不正确！');
}

//获取AppSecret
$app_secret = post_string('app_secret');
if($app_secret!='' and !preg_match($my_regexp['app_secret'], $app_secret)){
	admin_tip(0, 'AppSecret格式不正确！');
}

//获取EncodingAesKey
$encoding_aes_key = post_string('encoding_aes_key');
if($encoding_aes_key!='' and !preg_match($my_regexp['encoding_aes_key'], $encoding_aes_key)){
	admin_tip(0, 'EncodingAesKey格式不正确！');
}

//上传帐号logo
$upload_dir = 'uploadfiles/weixin/';
$img_upload = new ImgUpload('account_logo', $upload_dir);
$img_upload->create_thumb(100,100);
$img_info = $img_upload->upload();
if($img_info['status']==0){
	$account_logo = WEB_URL.$img_info['thumb_name'];
}else{
	admin_tip(0, $img_info['message']);
}

//添加公众帐号
$insert_sql = "insert into weixin_account (account_name,account_logo,platform_id,app_id,app_secret,encoding_aes_key,type) 
values ('$account_name','$account_logo','$platform_id','$app_id','$app_secret','$encoding_aes_key','$type)";
$result = $mysql_handle->query($insert_sql);
if($result){
	admin_tip(1, '添加公众帐号成功！',$callback_url);
}else{
	admin_tip(0, '添加公众帐号失败！');
}
?>