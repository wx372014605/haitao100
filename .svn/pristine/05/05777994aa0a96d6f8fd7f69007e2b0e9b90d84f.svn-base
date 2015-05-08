<?php
//获取缓存中的优惠商品列表
$discount_goods = get_cache('discount_goods');
if(!$discount_goods){
	curl_get_contents(WEB_URL.'task/get_discount_goods.php');
	$discount_goods = get_cache('discount_goods');
}
$discount_goods = (array)$discount_goods;
ajax_return(1, '优惠商品列表成功！',$discount_goods);
?>