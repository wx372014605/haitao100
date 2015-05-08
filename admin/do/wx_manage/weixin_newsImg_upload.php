<?php
require_once (WEB_ROOT.'class/img_upload.php');

//上传图文消息封面图片
$upload_dir = 'uploadfiles/weixin/';
$img_upload = new ImgUpload('picurl', $upload_dir);
$img_info = $img_upload->upload();
if($img_info['status']==0){
	$uploaded_url = WEB_URL.$img_info['img_name'];
	ajax_return(1, '上传成功！', array("uploaded_url"=>$uploaded_url));
}else{
	ajax_return(0, $img_info['message']);
}
?>