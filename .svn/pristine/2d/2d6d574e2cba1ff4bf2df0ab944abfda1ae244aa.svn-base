<?php
//订单支付后的处理
function pay_action($shopping_num,$trade_num,$payment_id){
	global $mysql_handle;
	$order_sql = "select order_id,status_id from transport_order where shopping_num='$shopping_num' limit 1";
	$order_arr = $mysql_handle->getRow($order_sql);
	if($order_arr){
		if($order_arr['status_id']==2){
			$order_id = $order_arr['order_id'];
			$pay_time = get_current_time();
			$update_sql = "update transport_order set status_id=3,trade_num='$trade_num',payment_id=$payment_id,
			pay_time='$pay_time' where order_id='$order_id' limit 1";
			$result = $mysql_handle->query($update_sql);
			return $result;
		}else{
			return true;
		}
	}else{
		return true;
	}
}
?>