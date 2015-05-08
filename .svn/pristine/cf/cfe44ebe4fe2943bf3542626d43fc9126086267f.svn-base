<?php
//获取订单id
$order_id = get_string('order_id');
if($order_id==''){
	admin_tip(0, '获取订单id失败！');
}

//获取待入库的订单列表
$order_id_arr = explode(',', $order_id);

//获取入库时间
$storage_time = get_current_time();

foreach ($order_id_arr as $val){
	//判断该订单是否可以入库
	$info_sql = "select od.user_id,od.status_id,od.goods_name,site.site_name from transport_order as od 
	join plugin_site as site on od.site_id=site.site_id where od.order_id=$val limit 1";
	$info_arr = $mysql_handle->getRow($info_sql);
	if($info_arr and $info_arr['status_id']==1){
		//订单入库
		$order_sql = "update transport_order set status_id=2,storage_time='$storage_time' where order_id=$val limit 1";
		$result = $mysql_handle->query($order_sql);
		if($result){
			//发送消息提醒
			$msg_title = '您在【'.$info_arr['site_name'].'】购买的商品 '.sub_str($info_arr['goods_name'], 20).' 已经发货啦！';
			$msg_content = '您在【'.$info_arr['site_name'].'】购买的商品 '.$info_arr['goods_name'].' ,卖家已于'.$storage_time.'发货，请确认订单并尽快付款！';
			send_system_message($info_arr['user_id'],$msg_title,$msg_content);
		}
	}
}
admin_tip(1, '订单入库成功！');
?>