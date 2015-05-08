<?php
//获取商品id
$goods_id = post_number('goods_id');
if($goods_id==0){
	ajax_return(0, '获取商品id失败！');
}

//取消关注商品
$fav_sql = "update favorite_goods set is_delete=1 where goods_id=$goods_id limit 1";
$result = $mysql_handle->query($fav_sql);
if($result){
	ajax_return(1, '取消关注商品成功！');
}else{
	ajax_return(0, '取消关注商品失败！');
}
?>