<?php
//获取消息id
$msg_id = post_number('msg_id');
if($msg_id==0){
	ajax_return(0, '获取消息id失败！');
}

//获取消息状态
$is_read = post_number('is_read');

//更新消息状态
$msg_sql = "update system_message set is_read=$is_read where msg_id=$msg_id limit 1";
$result = $mysql_handle->query($msg_sql);
if($result){
	ajax_return(1, '更新消息状态成功！');
}else{
	ajax_return(0, '更新消息状态失败！');
}
?>