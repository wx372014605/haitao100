<?php
//引入配置文件和相关函数
require_once ('../includes.php');

$act = get_string('act');
if($act==''){
	$act = 'index';
}

//判断管理员是否已经登录
if(!admin_login()){
	go_url('admin_login.php');
}else{
	//获取管理员id和名称
	$admin_id = get_session('admin_id');
	$admin_name = get_session('admin_name');
}

$act_arr = array(
	//默认参数
	"index"						=>	"index.php",
	"memcache_flush"			=>	"memcache_flush.php",
	"file_cache_flush"			=>	"file_cache_flush.php",

	/****微信管理****/
	"weixin_account_add"		=>	"do/wx_manage/weixin_account_add.php",
	"weixin_account_edit"		=>	"do/wx_manage/weixin_account_edit.php",
	"weixin_account_del"		=>	"do/wx_manage/weixin_account_del.php",
	"weixin_menu_manage"		=>	"do/wx_manage/weixin_menu_manage.php",
	"weixin_user_export"		=>	"do/wx_manage/weixin_user_export.php",
	"weixin_newsImg_upload"		=>	"do/wx_manage/weixin_newsImg_upload.php",
	"weixin_news_add"			=>	"do/wx_manage/weixin_news_add.php",
	"weixin_news_edit"			=>	"do/wx_manage/weixin_news_edit.php",
	"weixin_news_del"			=>	"do/wx_manage/weixin_news_del.php",
	"weixin_mass_send"			=>	"do/wx_manage/weixin_mass_send.php",
	"weixin_image_upload"		=>	"do/wx_manage/weixin_image_upload.php",
	"weixin_image_del"			=>	"do/wx_manage/weixin_image_del.php",
	"weixin_voice_upload"		=>	"do/wx_manage/weixin_voice_upload.php",
	"weixin_voice_del"			=>	"do/wx_manage/weixin_voice_del.php",
	"weixin_music_add"			=>	"do/wx_manage/weixin_music_add.php",
	"weixin_music_edit"			=>	"do/wx_manage/weixin_music_edit.php",
	"weixin_music_del"			=>	"do/wx_manage/weixin_music_del.php",
	/****微信管理****/
	
	/****交易管理****/
	"order_storage"				=>	"do/trade_manage/order_storage.php",
	"site_add"					=>	"do/trade_manage/site_add.php",
	"site_edit"					=>	"do/trade_manage/site_edit.php",
	"site_del"					=>	"do/trade_manage/site_del.php",
	"site_mark"					=>	"do/trade_manage/site_mark.php",
	/****交易管理****/

	/****消息管理****/
	"get_talk_message"			=>	"do/message_manage/get_talk_message.php",
	"send_talk_message"			=>	"do/message_manage/send_talk_message.php",
	/****消息管理****/

	/****公司管理****/
	"recruitment_add"			=>	"do/company_manage/recruitment_add.php",
	"recruitment_edit"			=>	"do/company_manage/recruitment_edit.php",
	"recruitment_del"			=>	"do/company_manage/recruitment_del.php",
	/****公司管理****/
	
	/****系统设置****/
	"admin_add"					=>	"do/sys_setting/admin_add.php",
	"admin_edit"				=>	"do/sys_setting/admin_edit.php",
	"admin_del"					=>	"do/sys_setting/admin_del.php",
	"password_modify"			=>	"do/sys_setting/password_modify.php",
	/****系统设置****/
);


//加载要显示的页面
if(isset($act_arr[$act]) and file_exists($act_arr[$act])){
	require $act_arr[$act];
}else{
	if(!isset($act_arr[$act])){
		admin_tip(0, "没有定义的操作act=".$act."！");
	}elseif(!file_exists($act_arr[$act])){
		admin_tip(0, $act_arr[$act]."文件或目录不存在！");
	}
}
?>