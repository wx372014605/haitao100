<?php
//获取用户id
$user_id = post_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取需要打包的订单id列表
$order_id_list = post_string('order_id_list');
if($order_id_list==''){
	ajax_return(0, '获取需要打包的订单id列表失败！');
}

//验证用户权限
$check_sql = "select user_id,status_id,package_id from transport_order where order_id in ($order_id_list)";
$check_arr = $mysql_handle->getRs($check_sql);
foreach ($check_arr as $val){
	if($val['status_id']>=4 or $val['package_id']!='' or $user_id!=$val['user_id']){
		ajax_return(0, '非法操作！');
	}
}

//生成打包id
$package_id = get_package_id();

//获取 打包时间
$package_time = get_current_time();

//开始打包
$order_sql = "update transport_order set package_id='$package_id',package_time='$package_time' where order_id in ($order_id_list)";
$result = $mysql_handle->query($order_sql);
if($result){
	ajax_return(1, '订单打包成功！');
}else{
	ajax_return(0, '订单打包失败！');
}


//生成打包id
function get_package_id(){
	$micro_time = microtime();
	$micro_arr = explode(' ', $micro_time);
	$shopping_num = mt_rand(100,999) . $micro_arr[1] . sprintf("%04d", $micro_arr[0]*1000) . mt_rand(100,999);
	return $shopping_num;
}
?>