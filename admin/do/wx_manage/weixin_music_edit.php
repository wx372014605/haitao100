<?php
//引入上传图片通用类
require_once (WEB_ROOT.'class/img_upload.php');
//引入上传文件通用类
require_once (WEB_ROOT.'class/file_upload.php');

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//待删除的文件数组
$delete_arr = array();

//获取上传的素材类型
$material_type = post_string('material_type');

//获取素材id
$material_id = post_number('material_id');
if($material_id==0){
	admin_tip(0, '获取素材id失败！');
}

//获取音乐素材详情
$music_sql = "select * from weixin_material where material_id=$material_id limit 1";
$music_arr = $mysql_handle->getRow($music_sql);
if($music_arr==false){
	admin_tip(0, '获取音乐素材详情失败！');
}
//解析音乐素材
$music_info = $weixin_handle->data_to_music($music_arr['material_data']);
if(!$music_info){
	admin_tip(0, '解析音乐素材失败！');
}

//获取音乐标题
$title = post_string('title');

//获取歌手名称
$singer = post_string('singer');

//获取音乐描述
$description = post_string('description');

//上传音乐封面
if($_FILES['music_pic']['name']==''){
	$music_pic = $music_info->music_pic;
}else{
	$upload_dir = 'uploadfiles/weixin/';
	$img_upload = new ImgUpload('music_pic', $upload_dir);
	$img_info = $img_upload->upload();
	if($img_info['status']==0){
		$music_pic = WEB_URL.$img_info['img_name'];
		$delete_arr[] = $music_info->music_pic;
	}else{
		$music_pic = $music_info->music_pic;
	}
}

//获取音乐来源
$music_source = post_number('music_source');
if($music_source==0){
	//上传音乐文件
	$upload_dir = 'uploadfiles/files/weixin/';
	if($_FILES['hq_music_file']['name']==''){
		$musicurl = $music_info->musicurl;
	}else{
		$file_upload1 = new FileUpload('music_file', $upload_dir);
		$file_upload1->file_size = 5120;//最大上传的文件大小为5M
		$file_upload1->file_type = array('mp3');
		$file_info = $file_upload1->upload();
		if($file_info['status']==0){
			$musicurl = WEB_URL.$file_info['file_name'];
			$delete_arr[] = $music_info->musicurl;
		}else{
			$musicurl = $music_info->musicurl;
		}
	}
	//上传高品质音乐文件
	if($_FILES['hq_music_file']['name']==''){
		$hqmusicurl = $music_info->hqmusicurl;
	}else{
		$file_upload2 = new FileUpload('hq_music_file', $upload_dir);
		$file_upload2->file_size = 10240;//最大上传的文件大小为10M
		$file_upload2->file_type = array('mp3');
		$file_info = $file_upload2->upload();
		if($file_info['status']==0){
			$hqmusicurl = WEB_URL.$file_info['file_name'];
			$delete_arr[] = $music_info->hqmusicurl;
		}else{
			$hqmusicurl = $music_info->hqmusicurl;
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

//修改音乐素材
$update_sql = "update weixin_material set material_type='$material_type',material_data='$material_data' where material_id=$material_id limit 1";
$result = $mysql_handle->query($update_sql);
if($result){
	//删除旧文件以节约资源
	foreach ($delete_arr as $val){
		if($val!='' and strpos($val, WEB_URL)!==false){
			delete_file(str_replace(WEB_URL, '', $val));
		}
	}
	admin_tip(1, '修改成功！', $callback_url);
}else{
	admin_tip(0, '修改失败！');
}
?>