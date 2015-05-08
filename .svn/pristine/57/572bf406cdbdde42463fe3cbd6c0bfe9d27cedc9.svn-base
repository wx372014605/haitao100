<?php
//引入配置文件和相关函数
require_once ('includes.php');

$app = isset($_GET['app'])?$_GET['app']:"index";

$app_arr = array(
	/****网站首页****/
	"index"						=>	"modules/index.php",
	"index_header"				=>	"modules/index_header.php",
	"index_footer"				=>	"modules/index_footer.php",
	"function_intro"			=>	"modules/use_instructions/function_intro.php",//使用指南
	"quickuse"					=>	"modules/use_instructions/quickuse.php",
	"daoshoujia"				=>	"modules/use_instructions/daoshoujia.php",
	"mianzhuce"					=>	"modules/use_instructions/mianzhuce.php",
	"install_question"			=>	"modules/use_instructions/install_question.php",//安装疑难
	"install_chrome"			=>	"modules/use_instructions/install_chrome.php",
	"install_opera"				=>	"modules/use_instructions/install_opera.php",
	"install_baidu"				=>	"modules/use_instructions/install_baidu.php",
	"install_uc"				=>	"modules/use_instructions/install_uc.php",
	/****网站首页****/

	"get_daolian_pic"			=>	"modules/get_daolian_pic.php",
	
	/****海淘商品****/
	"goods_eval"				=>	"modules/goods/goods_eval.php",
	/****海淘商品****/
	
	/****关于我们****/
	"recruitment"				=>	"modules/about/recruitment.php",
	"company_intro"				=>	"modules/about/company_intro.php",
	"contact_us"				=>	"modules/about/contact_us.php",
	/****关于我们****/

	/****我的海淘****/
	"my_order"					=>	"modules/my_haitao/my_order.php",
	"my_message"				=>	"modules/my_haitao/my_message.php",
	/****我的海淘****/
	
	/****海淘支付****/
	"pay_success"				=>	"modules/pay/pay_success.php",
	"pay_fail"					=>	"modules/pay/pay_fail.php",
	/****海淘支付****/
);


//加载要显示的页面
if(isset($app_arr[$app]) and file_exists($app_arr[$app])){
	require $app_arr[$app];
}else{
	if(!isset($app_arr[$app])){
		show_tip(0, "没有定义的操作app=".$app."！","index.php");
	}elseif(!file_exists($app_arr[$app])){
		show_tip(0, $app_arr[$app]."文件或目录不存在！","index.php");
	}
}
?>