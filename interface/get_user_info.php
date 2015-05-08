<?php
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取用户信息
$user_sql = "select * from users where user_id=$user_id limit 1";
$user_arr = $mysql_handle->getRow($user_sql);
if($user_arr){
	ajax_return(1, '获取用户信息成功！', $user_arr);
}else{
	ajax_return(0, '获取用户信息失败！');
}
?>