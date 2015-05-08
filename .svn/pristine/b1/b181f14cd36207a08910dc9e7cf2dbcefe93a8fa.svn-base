<?php
//引入配置文件和相关函数
require_once ('../includes.php');

//获取管理员帐号
$admin_name = isset($_POST['admin_name'])?add_slashes($_POST['admin_name']):'';
if($admin_name==''){
	ajax_return(0, '管理员帐号不能为空！');
}

//获取管理员密码
$admin_pwd = isset($_POST['admin_pwd'])?add_slashes($_POST['admin_pwd']):'';
if($admin_pwd==''){
	ajax_return(0, '管理员密码不能为空！');
}

//获取记住帐号的标记
$login_cookie = isset($_POST['login_cookie'])?intval($_POST['login_cookie']):0;

//验证管理员帐号密码是否正确
$login_sql = "select admin_id,group_id,admin_passwd,now_login,now_ip from admins where admin_name='$admin_name' limit 1";
$login_arr = $mysql_handle->getRow($login_sql);
if($login_arr){
	if(md5(md5($admin_name).md5($admin_pwd))==$login_arr['admin_passwd']){
		//验证通过，开始管理员登录处理
		if($login_cookie==1){
			setcookie('admin_name',$admin_name,time()+31536000);
		}else{
			setcookie('admin_name',$admin_name,time()-1);
		}
		//获取本次登录的时间和ip地址
		$now_login = date("Y-m-d H:i:s");
		$now_ip = $_SERVER['REMOTE_ADDR'];
		$last_login = $login_arr['now_login'];
		$last_ip = $login_arr['now_ip'];
		$admin_id = $login_arr['admin_id'];
		$update_sql = "update admins set now_login='$now_login',now_ip='$now_ip'";
		if($last_login){
			$update_sql .= ",last_login='$last_login'";
		}
		if($last_ip){
			$update_sql .= ",last_ip='$last_ip'";
		}
		$update_sql .= " where admin_id=$admin_id limit 1";
		$mysql_handle->query($update_sql);
		
		//设置管理员登录状态
		set_session('admin_id', $admin_id);
		set_session('admin_name', $admin_name);
		
		//设置管理员权限
		$group_id = $login_arr['group_id'];
		$group_sql = "select group_name from admin_group where group_id=$group_id limit 1";
		$group_arr = $mysql_handle->getRow($group_sql);
		if($group_arr==false){
			admin_tip(0, '管理员组异常，禁止登录！');
		}
		set_session('group_id', $group_id);
		set_session('group_name', $group_arr['group_name']);
		ajax_return(1, '登录成功！');
	}else{
		ajax_return(0, '管理员帐号或密码错误！');
	}
}else{
	ajax_return(0, '该管理员帐号不存在！');
}
?>