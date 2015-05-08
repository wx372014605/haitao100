<?php
//获取用户id
$user_id = post_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取用户手机号码
$user_mobile = post_string('user_mobile');
if($user_mobile==''){
	ajax_return(0, '获取用户手机号码失败！');
}
if(!preg_match($my_regexp['mobile'], $user_mobile)){
	ajax_return(0, '用户手机号码格式不正确！');
}

//绑定用户手机号码
$bind_sql = "update users set user_mobile='$user_mobile' where user_id=$user_id limit 1";
$result = $mysql_handle->query($bind_sql);
if($result){
	ajax_return(1, '绑定手机号码成功！');
}else{
	ajax_return(0, '绑定手机号码失败！');
}
?>