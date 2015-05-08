<?php
//权限验证
if(!is_super()){
	admin_tip(0, '非法操作！');
}

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取管理组id
$gid = post_number('group_id');
if($gid==0){
	admin_tip(0, '获取管理组id失败！');
}

//获取登录帐号
$admin_name = post_string('admin_name');
if($admin_name==''){
	admin_tip(0, '获取登录帐号失败！');
}
if(!preg_match($my_regexp['account'], $admin_name)){
	admin_tip(0, '登录帐号格式不正确！');
}

//获取登录密码
$admin_passwd = post_string('admin_passwd');
if($admin_passwd==''){
	admin_tip(0, '获取登录密码失败！');
}
if(!preg_match($my_regexp['password'], $admin_passwd)){
	admin_tip(0, '登录密码格式不正确！');
}
//登录密码加密
$admin_passwd = md5(md5($admin_name).md5($admin_passwd));

//获取邮箱地址
$email = post_string('email');
if($email==''){
	admin_tip(0, '获取邮箱地址失败！');
}
if(!preg_match($my_regexp['email'], $email)){
	admin_tip(0, '获取邮箱地址格式不正确！');
}

//获取添加时间
$add_time = get_current_time();

//判断管理员帐号是否存在
$check_sql = "select admin_id from admins where admin_name='$admin_name' limit 1";
$check_arr = $mysql_handle->getRow($check_sql);
if($check_arr){
	admin_tip(0, '该管理员帐号已经存在！');
}

//添加管理员
$insert_sql = "insert into admins (group_id,admin_name,admin_passwd,email,add_time) 
values ($gid,'$admin_name','$admin_passwd','$email','$add_time')";
$result = $mysql_handle->query_sql($insert_sql);
if($result){
	admin_tip(1, '添加管理员成功！',$callback_url);
}else{
	admin_tip(0, '添加管理员失败！');
}
?>