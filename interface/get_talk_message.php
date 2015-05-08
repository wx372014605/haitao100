<?php
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取未读的聊天消息
$msg_sql = "select msg.message_id,msg.message_content,msg.send_time,msg.is_read,ad.admin_name from talk_message as msg 
join admins as ad on msg.from_admin_id=ad.admin_id where msg.to_user_id=$user_id and msg.is_read=0";
$msg_arr = $mysql_handle->getRs($msg_sql);
if($msg_arr){
	//还原表情
	$exp_sql = "select * from weixin_exp";
	$exp_arr = $mysql_handle->getRs($exp_sql);
	$code_arr = array();
	$img_arr = array();
	foreach ($exp_arr as $val){
		$code_arr[] = $val['exp_code'];
		$img_arr[] = '<img title="'.$val['exp_name'].'" src="icons/talk_exp/ico'.$val['exp_id'].'.gif'.'"/>';
	}
	$msg_id_list = '';
	foreach ($msg_arr as $key=>$val){
		$msg_id_list .= ','.$val['message_id'];
		$msg_arr[$key]['message_content'] = str_replace($code_arr, $img_arr, $msg_arr[$key]['message_content']);
	}
	$msg_id_list = preg_replace('/^,/', '', $msg_id_list);
	
	//更新聊天消息为已读状态
	$update_sql = "update talk_message set is_read=1 where message_id in ($msg_id_list)";
	$mysql_handle->query($update_sql);
}
ajax_return(1, '获取聊天消息成功！',$msg_arr);
?>