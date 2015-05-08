<?php
/**
 * 更新用户数据并记录数据
 * 本程序需要在每天晚上24点之前执行
 * @author zjq
 * v1.0
 */
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行.

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');

$pre_date = get_current_date("-1 day");//获取前一天的日期
$now_date = get_current_date();//获取当前的日期

//获取昨天的用户数据
$pre_sql = "select platform_id,user_totle from weixin_data_analysis where data_date='$pre_date'";
$pre_arr = $mysql_handle->getRs($pre_sql);
$old_user_data = array();
foreach ($pre_arr as $val){
	$old_user_data[$val['platform_id']] = $val['user_totle'];
}

//获取当前的用户数据
$user_sql = "select count(id) as user_totle,platform_id from weixin_users where subscribe=1 group by platform_id";
$user_arr = $mysql_handle->getRs($user_sql);
foreach ($user_arr as $val){
	$platform_id = $val['platform_id'];
	$user_totle = $val['user_totle'];
	$old_num = isset($old_user_data[$platform_id])?$old_user_data[$platform_id]:0;
	$user_raise = $user_totle - $old_num;
	$check_sql = "select analysis_id from weixin_data_analysis where platform_id='$platform_id' and data_date='$now_date' limit 1";
	$check_arr = $mysql_handle->getRow($check_sql);
	if($check_arr){
		$sql = "update weixin_data_analysis set user_raise='$user_raise',user_totle='$user_totle' where platform_id='$platform_id' and data_date='$now_date' limit 1";
	}else{
		$sql = "insert into weixin_data_analysis (platform_id,data_date,user_raise,user_totle) values ('$platform_id','$now_date','$user_raise','$user_totle')";
	}
	$result = $mysql_handle->query($sql);
}
?>