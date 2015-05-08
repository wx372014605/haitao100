<?php
//获取订单id
$order_id = post_number('order_id');
if($order_id==0){
	ajax_return(0, '获取订单id失败！');
}

//删除订单
$order_sql = "update transport_order set is_delete=1 where order_id=$order_id limit 1";
$result = $mysql_handle->query($order_sql);
if($result){
	ajax_return(1, '删除订单成功！');
}else{
	ajax_return(0, '删除订单失败！');
}
?>