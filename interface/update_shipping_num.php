<?php
//获取订单id
$order_id = post_number('order_id');
if($order_id==0){
	ajax_return(0, '获取订单id失败！');
}

//获取运单号
$shipping_num = post_string('shipping_num');
if($shipping_num==''){
	ajax_return(0, '获取运单号失败！');
}

//更新运单号
$order_sql = "update transport_order set shipping_num='$shipping_num' where order_id=$order_id limit 1";
write_log($order_sql);
$result = $mysql_handle->query($order_sql);
if($result){
	ajax_return(1, '更新运单号成功！');
}else{
	ajax_return(0, '更新运单号失败！');
}
?>