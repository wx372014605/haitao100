<?php
//初始化分页类
$page = new Paging();
$page->page_size = 5;
$page->page_num = 3;
$page->show_first = false;
$page->show_last = false;

//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取订单列表
$order_sql = "select od.order_id,od.package_id,od.goods_url,od.goods_img,od.goods_name,od.goods_num,od.add_time,od.package_time,od.shipping_num,od.status_id,os.status_name from 
transport_order as od join order_status os on od.status_id=os.status_id where user_id=$user_id and is_delete=0 order by package_id desc,od.order_id desc";
$order_arr = $page->get_record($order_sql);
ajax_return(1, '获取订单列表成功！', array('order_list'=>$order_arr,'record_count'=>$page->get_record_count(),'page_html'=>$page->get_page()));
?>