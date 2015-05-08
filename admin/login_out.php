<?php
//引入配置文件和相关函数
require_once ('../includes.php');

//清除登录状态
delete_session('admin_id');
delete_session('admin_name');

//清除管理组权限
delete_session('group_id');
delete_session('group_name');

//跳转到管理员登录页面
go_url('admin_login.php');
?>