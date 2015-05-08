<?php
//引入上传图片通用类
require_once (WEB_ROOT.'class/img_upload.php');
//引入上传文件通用类
require_once (WEB_ROOT.'class/file_upload.php');

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取上传的素材类型
$material_type = post_string('material_type');

//获取音乐标题
$title = post_string('title');

//获取歌手名称
$singer = post_string('singer');

//获取音乐描述
$description = post_string('description');

//上传音乐封面
if($_FILES['music_pic']['name']==''){
	$music_pic = '';
}else{
	$upload_dir = 'uploadfiles/weixin/';
	$img_upload = new ImgUpload('music_pic', $upload_dir);
	$img_info = $img_upload->upload();
	if($img_info['status']==0){
		$music_pic = WEB_URL.$img_info['img_name'];
	}else{
		$music_pic = '';
	}
}

//获取音乐来源
$music_source = post_number('music_source');
if($music_source==0){
	//上传音乐文件
	$upload_dir = 'uploadfiles/files/weixin/';
	$file_upload1 = new FileUpload('music_file', $upload_dir);
	$file_upload1->file_size = 5120;//最大上传的文件大小为5M
	$file_upload1->file_type = array('mp3');
	$file_info = $file_upload1->upload();
	if($file_info['status']==0){
		$musicurl = WEB_URL.$file_info['file_name'];
	}else{
		admin_tip(0, $file_info['message']);
	}
	//上传高品质音乐文件
	if($_FILES['hq_music_file']['name']==''){
		$hqmusicurl = '';
	}else{
		$file_upload2 = new FileUpload('hq_music_file', $upload_dir);
		$file_upload2->file_size = 10240;//最大上传的文件大小为10M
		$file_upload2->file_type = array('mp3');
		$file_info = $file_upload2->upload();
		if($file_info['status']==0){
			$hqmusicurl = WEB_URL.$file_info['file_name'];
		}else{
			admin_tip(0, $file_info['message']);
		}
	}
}else if($music_source==1){
	$musicurl = post_string('musicurl', 0);
	$hqmusicurl = post_string('hqmusicurl', 0);
}else{
	admin_tip(0, '非法的音乐来源！');
}

//获取素材内容
$material_data = '{
	"title":"'.str_replace('"', "'", $title).'",
	"description":"'.str_replace('"', "'", $description).'",
	"musicurl":"'.$musicurl.'",
	"hqmusicurl":"'.$hqmusicurl.'",
	"singer":"'.str_replace('"', "'", $singer).'",
	"music_pic":"'.$music_pic.'"
}';
$material_data = strip_wraps($material_data);

//获取添加时间
$add_time = get_current_time();

//添加音乐素材
$insert_sql = "insert into weixin_material (material_type,material_data,add_time) values ('$material_type','$material_data','$add_time')";
$result = $mysql_handle->query($insert_sql);
if($result){
	admin_tip(1, '添加成功！', $callback_url);
}else{
	admin_tip(0, '添加失败！');
}
?>