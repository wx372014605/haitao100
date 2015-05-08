<?php
//获取转运地址标记
$address_mark = get_string('address_mark');

//获取转运地址列表
$address_sql = "select * from transport_address where is_available=1";
if($address_mark!=''){
	$address_sql .= " and address_mark='$address_mark'";
}
$address_arr = $mysql_handle->getRs($address_sql);
if($address_arr){
	ajax_return(1, '获取转运地址成功！', $address_arr);
}else{
	ajax_return(0, '获取转运地址失败');
}
?>