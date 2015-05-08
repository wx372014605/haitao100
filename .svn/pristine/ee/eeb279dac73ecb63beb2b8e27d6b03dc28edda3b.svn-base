<?php
require_once (WEB_ROOT.'class/img_upload.php');

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//待删除的文件数组
$delete_arr = array();

//获取网站id
$site_id = post_number('site_id');
if($site_id==0){
	admin_tip(0, '获取网站id失败！');
}

//获取网站详细信息
$site_sql = "select * from plugin_site where site_id=$site_id limit 1";
$site_arr = $mysql_handle->getRow($site_sql);

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

//判断是否选择了文件
if($_FILES['site_logo']['name']==''){
	$site_logo = $site_arr['site_logo'];
}else{
	$upload_dir = 'uploadfiles/plugin/';
	$img_upload = new ImgUpload('site_logo', $upload_dir);
	$img_upload->create_thumb(100,35);
	$img_info = $img_upload->upload();
	if($img_info['status']==0){
		$site_logo = $img_info['thumb_name'];
		$delete_arr[] = $site_arr['site_logo'];
		$delete_arr[] = str_replace('_small', '', $site_arr['site_logo']);
	}else{
		admin_tip(0, $img_info['message']);
	}
}

//修改网站
$update_sql = "update plugin_site set site_name='$site_name',site_url='$site_url',
site_logo='$site_logo',is_available=$is_available where site_id=$site_id limit 1";
$result = $mysql_handle->query($update_sql);
if($result){
	//删除旧文件以节约资源
	foreach ($delete_arr as $val){
		if($val!=''){
			delete_file($val);
		}
	}
	admin_tip(1, '修改网站成功！',$callback_url);
}else{
	admin_tip(0, '修改网站失败！');
}
?>