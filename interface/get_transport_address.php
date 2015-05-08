<?php
//获取转运地址id
$address_id = get_number('address_id');
if($address_id==0){
	ajax_return(0, '获取转运地址id失败！');
}

//获取转运地址列表
$address_sql = "select country_address,province_address,province_address_ab,city_address,street_address,
apartment_address,address_mobile,zip_code from transport_address where address_id=$address_id limit 1";
$address_arr = $mysql_handle->getRow($address_sql);
if($address_arr){
	ajax_return(1, '获取转运地址成功！', $address_arr);
}else{
	ajax_return(0, '获取转运地址失败');
}
?>