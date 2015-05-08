<?php
//判断用户是否已经登录
function is_login(){
	if(get_session('user_id')){
		return true;
	}else{
		return false;
	}
}
//判断微信用户是否已经登录
function weixin_login(){
	if(get_session('open_id')){
		return true;
	}else{
		return false;
	}
}

//检测管理员是否登录
function admin_login(){
	if(get_session('admin_id')){
		return true;
	}else{
		return false;
	}
}

//判断当前登录的管理员是否是超级管理员
function is_super(){
	if(get_session('group_id')==1){
		return true;
	}else{
		return false;
	}
}
?>