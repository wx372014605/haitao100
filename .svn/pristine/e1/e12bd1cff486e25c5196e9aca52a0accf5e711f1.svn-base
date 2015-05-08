<?php
//获取商品链接
$goods_url = get_string('goods_url');
if($goods_url==''){
	ajax_return(0, '获取商品链接失败！');
}

//商品url标识
$goods_sign = md5($goods_url);

//获取商品评价数量
$eval_sql = "select count(eval_id) as eval_count from goods_eval where goods_sign='$goods_sign'";
$eval_arr = $mysql_handle->getRow($eval_sql);
ajax_return(1, '获取商品评价数量成功！', array('eval_count'=>$eval_arr['eval_count']));
?>