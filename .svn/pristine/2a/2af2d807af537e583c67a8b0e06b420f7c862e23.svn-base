<?php
//创建新用户
$add_time = get_current_time();
$user_sql = "insert into users (add_time) values ('$add_time')";
$result = $mysql_handle->query($user_sql);
if($result){
	$user_id = $mysql_handle->get_insert_id();
	ajax_return(1, '注册新用户成功！', array('user_id'=>$user_id));
}else{
	ajax_return(0, '注册新用户失败！');
}
?>