<?php
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取未读的聊天消息
$msg_sql = "select count(message_id) as talk_count from talk_message where to_user_id=$user_id and is_read=0";
$msg_arr = $mysql_handle->getRow($msg_sql);
ajax_return(1, '获取聊天消息条数成功！',$msg_arr['talk_count']);
?>