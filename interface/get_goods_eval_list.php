<?php
//获取每页的评论数量
$page_size = get_number('page_size');
if($page_size==0){
	$page_size = 3;
}

//初始化分页类
$page = new Paging();
$page->page_size = $page_size;
$page->page_num = 3;
$page->show_first = false;
$page->show_last = false;

//获取商品链接
$goods_url = get_string('goods_url');
if($goods_url==''){
	ajax_return(0, '获取商品链接失败！');
}

//商品url标识
$goods_sign = md5($goods_url);

//获取商品评论列表
$eval_sql = "select * from goods_eval where goods_sign='$goods_sign' order by eval_id desc";
$eval_arr = $page->get_record($eval_sql);
ajax_return(1, '获取商品评论列表成功！', array('eval_arr'=>$eval_arr,'page_count'=>$page->get_page_count(),'record_count'=>$page->get_record_count(),'page_html'=>$page->get_page()));
?>