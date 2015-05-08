<?php
//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取原密码
$old_passwd = post_string('old_passwd');
if($old_passwd==''){
	admin_tip(0, '获取原密码失败！');
}
if(!preg_match($my_regexp['password'], $old_passwd)){
	admin_tip(0, '原密码格式不正确！');
}

//获取新密码
$new_passwd = post_string('new_passwd');
if($new_passwd==''){
	admin_tip(0, '获取新密码失败！');
}
if(!preg_match($my_regexp['password'], $new_passwd)){
	admin_tip(0, '新密码格式不正确！');
}

//获取确认密码
$re_passwd = post_string('re_passwd');
if($re_passwd==''){
	admin_tip(0, '获取确认密码失败！');
}
if($re_passwd!=$new_passwd){
	admin_tip(0, '两次输入的密码不一致！');
}

//验证原密码是否正确
$check_sql = "select admin_name,admin_passwd from admins where admin_id=$admin_id limit 1";
$check_arr = $mysql_handle->getRow($check_sql);
if($check_arr==false){
	admin_tip(0, '该管理员不存在！');
}else{
	if($check_arr['admin_passwd']==md5(md5($check_arr['admin_name']).md5($old_passwd))){
		//开始修改密码
		$admin_passwd = md5(md5($check_arr['admin_name']).md5($new_passwd));
		$sql = "update admins set admin_passwd='$admin_passwd' where admin_id=$admin_id limit 1";
		$result = $mysql_handle->query($sql);
		if($result){
			admin_tip(1, '密码修改成功！');
		}else{
			admin_tip(0, '密码修改失败！');
		}
	}else{
		admin_tip(0, '原密码不正确！');
	}
}
?>