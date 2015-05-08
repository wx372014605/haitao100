<?php
//引入配置文件和相关函数
require_once ('includes.php');

$act = isset($_GET['act'])?$_GET['act']:"index";

$act_arr = array(
	"index"						=>	"action/index.php",
	
	/****ajax操作****/
	"get_areas"					=>	"action/ajax/get_areas.php",
	/****ajax操作****/
	
	/****海淘支付****/
	"alipay_request"			=>	"action/pay/alipay_request.php",
	"alipay_return"				=>	"action/pay/alipay_return.php",
	"alipay_notify"				=>	"action/pay/alipay_notify.php",
	/****海淘支付****/
);


//加载要显示的页面
if(isset($act_arr[$act]) and file_exists($act_arr[$act])){
	require $act_arr[$act];
}else{
	if(!isset($act_arr[$act])){
		show_tip(0, "没有定义的操作act=".$act."！","index.php");
	}elseif(!file_exists($act_arr[$act])){
		show_tip(0, $act_arr[$act]."文件或目录不存在！","index.php");
	}
}
?>