<?php
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取用户收货地址
$address_sql = "select user_name,user_mobile,user_province,user_city,user_district,user_address from user_address where user_id=$user_id limit 1";
$address_arr = $mysql_handle->getRow($address_sql);
if($address_arr==false){
	$address_arr = array();
}
ajax_return(1, '获取用户收货地址成功！',$address_arr);