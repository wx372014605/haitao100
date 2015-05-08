<?php
//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取商品链接
$goods_url = get_string('goods_url');
if($goods_url==''){
	ajax_return(0, '获取商品链接失败！');
}

//商品url标识
$goods_sign = md5($goods_url);

//判断用户是否已经收藏过改商品
$check_sql = "select goods_id from favorite_goods where user_id=$user_id and goods_sign='$goods_sign' limit 1";
$check_arr = $mysql_handle->getRow($check_sql);
if($check_arr){
	ajax_return(1, '您已关注过此商品！',array('state'=>1));
}else{
	ajax_return(1, '您尚未关注此商品！',array('state'=>0));
}
?>