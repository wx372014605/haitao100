<?php
require_once (WEB_ROOT.'class/img_upload.php');

//上传图文消息封面图片
$upload_dir = 'uploadfiles/weixin/';
$img_upload = new ImgUpload('image_file', $upload_dir);
$img_upload->img_type = array("jpg","jpeg");
$img_upload->img_size = 1024;
$img_info = $img_upload->upload();
if($img_info['status']==0){
	$uploaded_url = WEB_URL.$img_info['img_name'];
	//保存图片消息素材
	$material_data = '{"image_url":"'.$uploaded_url.'"}';
	$add_time = get_current_time();
	$insert_sql = "insert into weixin_material (material_type,material_data,add_time) values ('image','$material_data','$add_time')";
	$result = $mysql_handle->query($insert_sql);
	if($result){
		$material_id = $mysql_handle->get_insert_id();
		ajax_return(1, '上传成功！', array("material_id"=>$material_id,"uploaded_url"=>$uploaded_url));
	}else{
		ajax_return(0, '添加图片素材失败！');
	}
}else{
	ajax_return(0, $img_info['message']);
}
?>