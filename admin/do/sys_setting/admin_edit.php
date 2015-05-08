<?php
//权限验证
if(!is_super()){
	admin_tip(0, '非法操作！');
}

//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取管理员id
$aid = post_number('admin_id');
if($aid==0){
	admin_tip(0, '获取管理员id失败！');
}

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
if($admin_passwd!='' and !preg_match($my_regexp['password'], $admin_passwd)){
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

//判断管理员帐号是否存在
$check_sql = "select admin_id from admins where admin_name='$admin_name' limit 1";
$check_arr = $mysql_handle->getRow($check_sql);
if($check_arr){
	admin_tip(0, '该管理员帐号已经存在！');
}

//修改管理员
$update_sql = "update admins set group_id=$gid,admin_name='$admin_name'";
if($admin_passwd!=''){
	$update_sql .= ",admin_passwd='$admin_passwd'";
}
$update_sql .= ",email='$email' where admin_id=$aid limit 1";
$result = $mysql_handle->query_sql($update_sql);
if($result){
	admin_tip(1, '修改管理员成功！',$callback_url);
}else{
	admin_tip(0, '修改管理员失败！');
}
?>