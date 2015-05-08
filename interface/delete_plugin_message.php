<?php
//获取消息id列表
$msg_id_list = post_string('msg_id_list');
if($msg_id_list==''){
	ajax_return(0, '获取消息id列表失败！');
}

//删除消息
$msg_sql = "update system_message set is_delete=1 where msg_id in ($msg_id_list)";
$result = $mysql_handle->query($msg_sql);
if($result){
	ajax_return(1, '删除消息成功！');
}else{
	ajax_return(0, '删除消息失败！');
}
?>