<?php
//引入配置文件和相关函数
require_once ('../includes.php');

//获取menu_tag
$menu_tag = get_string('menu_tag');
if($menu_tag==''){
	$menu_tag = 'index';
}

//判断管理员是否已经登录
if(!admin_login()){
	go_url('admin_login.php');
}else{
	//获取管理员id和名称
	$admin_id = get_session('admin_id');
	$admin_name = get_session('admin_name');
}

$tag_arr = array(
	//默认参数
	"index"					=>	"index.php",
	"start"					=>	"start.php",

	/****微信管理****/
	"weixin_account_list"		=>	"modules/wx_manage/weixin_account_list.php",
	"weixin_account_add"		=>	"modules/wx_manage/weixin_account_add.php",
	"weixin_account_edit"		=>	"modules/wx_manage/weixin_account_edit.php",
	"weixin_menu_manage"		=>	"modules/wx_manage/weixin_menu_manage.php",
	"weixin_user_info"			=>	"modules/wx_manage/weixin_user_info.php",
	"weixin_material_manage"	=>	"modules/wx_manage/weixin_material_manage.php",
	"weixin_news_manage"		=>	"modules/wx_manage/weixin_news_manage.php",
	"weixin_news_add"			=>	"modules/wx_manage/weixin_news_add.php",
	"weixin_news_edit"			=>	"modules/wx_manage/weixin_news_edit.php",
	"weixin_news_content"		=>	"modules/wx_manage/weixin_news_content.php",
	"weixin_news_select"		=>	"modules/wx_manage/weixin_news_select.php",
	"weixin_mass_send"			=>	"modules/wx_manage/weixin_mass_send.php",
	"weixin_send_history"		=>	"modules/wx_manage/weixin_send_history.php",
	"weixin_image_manage"		=>	"modules/wx_manage/weixin_image_manage.php",
	"weixin_image_select"		=>	"modules/wx_manage/weixin_image_select.php",
	"weixin_voice_manage"		=>	"modules/wx_manage/weixin_voice_manage.php",
	"weixin_voice_select"		=>	"modules/wx_manage/weixin_voice_select.php",
	"weixin_music_manage"		=>	"modules/wx_manage/weixin_music_manage.php",
	"weixin_music_add"			=>	"modules/wx_manage/weixin_music_add.php",
	"weixin_music_edit"			=>	"modules/wx_manage/weixin_music_edit.php",
	"weixin_music_select"		=>	"modules/wx_manage/weixin_music_select.php",
	/****微信管理****/

	/****数据分析****/
	"weixin_user_analysis"		=>	"modules/data_analysis/weixin_user_analysis.php",
	"weixin_user_data"			=>	"modules/data_analysis/weixin_user_data.php",
	"weixin_user_range"			=>	"modules/data_analysis/weixin_user_range.php",
	"weixin_user_sex"			=>	"modules/data_analysis/weixin_user_sex.php",
	/****数据分析****/
	
	/****交易管理****/
	"transport_order"			=>	"modules/trade_manage/transport_order.php",
	"site_list"					=>	"modules/trade_manage/site_list.php",
	"site_add"					=>	"modules/trade_manage/site_add.php",
	"site_edit"					=>	"modules/trade_manage/site_edit.php",
	"site_count"				=>	"modules/trade_manage/site_count.php",
	/****交易管理****/

	/****消息管理****/
	"talk_message"				=>	"modules/message_manage/talk_message.php",
	"talk_window"				=>	"modules/message_manage/talk_window.php",
	"talk_history"				=>	"modules/message_manage/talk_history.php",
	"talk_user"					=>	"modules/message_manage/talk_user.php",
	/****消息管理****/
	
	/****公司管理****/
	"recruitment_list"			=>	"modules/company_manage/recruitment_list.php",
	"recruitment_add"			=>	"modules/company_manage/recruitment_add.php",
	"recruitment_edit"			=>	"modules/company_manage/recruitment_edit.php",
	/****公司管理****/
	
	/****系统设置****/
	"admin_list"				=>	"modules/sys_setting/admin_list.php",
	"admin_add"					=>	"modules/sys_setting/admin_add.php",
	"admin_edit"				=>	"modules/sys_setting/admin_edit.php",
	"password_modify"			=>	"modules/sys_setting/password_modify.php",
	/****系统设置****/
);


//加载要显示的页面
if(isset($tag_arr[$menu_tag]) and file_exists($tag_arr[$menu_tag])){
	require $tag_arr[$menu_tag];
}else{
	if(!isset($tag_arr[$menu_tag])){
		admin_tip(0, '找不到菜单'.$menu_tag."！", "index.php");
	}elseif(!file_exists($tag_arr[$menu_tag])){
		admin_tip(0, $tag_arr[$menu_tag].'文件或目录不存在', "index.php");
	}
}
?>