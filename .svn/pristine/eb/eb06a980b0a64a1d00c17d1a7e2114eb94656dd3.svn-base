<?php
//引入文件上传通用类
require_once (WEB_ROOT.'class/file_upload.php');
//引入获取多媒体信息通用类
require_once (WEB_ROOT.'class/audio_info.php');

//上传音乐文件
$upload_dir = 'uploadfiles/files/weixin/';
$file_upload = new FileUpload('voice_file', $upload_dir);
$file_upload->file_size = 2048;//最大上传的文件大小为2M
$file_upload->file_type = array('mp3');
$file_info = $file_upload->upload();
if($file_info['status']==0){
	$voice_file = WEB_ROOT.$file_info['file_name'];
	//获取音乐文件的信息
	$audio_info = new AudioInfo();
	$voice_info = $audio_info->Info($voice_file);
	if(isset($voice_info['error']) and $voice_info['error']){
		ajax_return(0, $voice_info['error']);
	}else{
		$format_name = strtolower($voice_info['format_name']);
		if($format_name!='mp3'){
			delete_file($file_info['file_name']);
			ajax_return(0, '语音文件只能为mp3格式！');
		}
		$playing_time = $voice_info['playing_time'];
		if($playing_time>=60){
			delete_file($file_info['file_name']);
			ajax_return(0, '语音文件的播放长度不能超过60s！');
		}
		$uploaded_url = WEB_URL.$file_info['file_name'];
		//保存语音消息素材
		$material_data = '{"voice_url":"'.$uploaded_url.'"}';
		$add_time = get_current_time();
		$insert_sql = "insert into weixin_material (material_type,material_data,add_time) values ('voice','$material_data','$add_time')";
		$result = $mysql_handle->query($insert_sql);
		if($result){
			$material_id = $mysql_handle->get_insert_id();
			ajax_return(1, '上传成功！', array("material_id"=>$material_id,"uploaded_url"=>$uploaded_url));
		}else{
			ajax_return(0, '添加语音素材失败！');
		}
	}
}else{
	ajax_return(0, $file_info['message']);
}
?>