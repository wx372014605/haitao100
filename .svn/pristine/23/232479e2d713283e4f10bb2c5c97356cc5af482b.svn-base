<?php
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取可以打包的运单列表
$order_sql = "select od.order_id,od.goods_url,od.goods_img,od.goods_name,od.goods_num,od.add_time,od.shipping_num,os.status_name from 
transport_order as od join order_status os on od.status_id=os.status_id where user_id=$user_id and is_delete=0 and od.status_id<4 
and od.package_id='' order by od.order_id desc";
$order_arr = $mysql_handle->getRs($order_sql);
ajax_return(1, '获取可以打包的运单列表成功！', array('order_list'=>$order_arr));
?>