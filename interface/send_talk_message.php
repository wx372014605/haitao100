<?php
//获取用户id
$user_id = post_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}
//获取消息内容
$msg_content = strip_slashes(post_string('msg_content',0),true);
preg_match_all('/<\s*img.*?src\s*=\s*"[^"]+?ico(\d+)\.(?:jpg|jpeg|png|gif|bmp)\s*"\s*[\/]?>/i', $msg_content, $img_arr);
$img_str_arr = $img_arr[0];
$exp_id_arr = $img_arr[1];
$exp_code_arr = array();
if($img_str_arr){
	$exp_sql = "select exp_id,exp_code from weixin_exp";
	$exp_arr = $mysql_handle->getRs($exp_sql);
	$code_arr = array();
	foreach ($exp_arr as $val){
		$code_arr[$val['exp_id']] = str_replace("'", "\\'", $val['exp_code']);
	}
	unset($exp_arr);
	foreach ($img_str_arr as $key=>$val){
		$img_str = $val;
		$exp_code = $code_arr[$exp_id_arr[$key]];
		$msg_content = str_replace($img_str, $exp_code, $msg_content);
	}
}
//$msg_content = strip_html_tags($msg_content);
$msg_content = preg_replace('/<[\/\s]?(?!img)[^>]*>/', '', $msg_content);
if($msg_content==''){
	ajax_return(0, '发送的消息内容不能为空！');
}

$send_time = get_current_time();

$msg_sql = "insert into talk_message (from_user_id,to_admin_id,message_content,send_time) values ($user_id,1,'$msg_content','$send_time')";
$result = $mysql_handle->query($msg_sql);
if($result){
	ajax_return(1, '消息发送成功！',array('send_time'=>$send_time));
}else{
	ajax_return(0, '消息发送失败，请重试！');
}
?>