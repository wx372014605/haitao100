<?php
//用户关闭浏览器,PHP脚本也可以继续执行
ignore_user_abort(true);

//获取订单id
$order_id = post_number('order_id');
if($order_id==0){
	ajax_return(0, '获取订单id失败！');
}

//更新订单状态为已分享
$order_sql = "update transport_order set is_share=1 where order_id=$order_id limit 1";
$result = $mysql_handle->query($order_sql);
if($result){
	ajax_return(1, '分享订单成功！');
}else{
	ajax_return(0, '分享订单失败！');
}
?>