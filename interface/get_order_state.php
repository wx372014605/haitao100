<?php 
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//设置用户登录session
set_session('user_id', $user_id);

//获取消息列表
$msg_sql = "select msg_id,msg_title,msg_content,msg_link,send_time,is_read from system_message 
where msg_type=0 and user_id=$user_id and is_read=0 and is_delete=0 order by msg_id desc";
$msg_arr = $mysql_handle->getRs($msg_sql);
$msg_count = count($msg_arr);
ajax_return(1, '有'.$msg_count.'件商品的状态发生了变化，请点击查看！', array('msg_count'=>$msg_count));
?>