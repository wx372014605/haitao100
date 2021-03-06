<?php
//获取用户id
$user_id = post_number('user_id');
if($user_id==0){
	//从session中读取用户id
	$user_id = get_session('user_id');
	if($user_id==false){
		ajax_return(0, '获取用户id失败，请安装海淘插件后重试！');
	}
}

//获取来源网站id
$site_id = post_number('site_id');

//获取商品链接
$goods_url = post_string('goods_url',0);
if($goods_url==''){
	ajax_return(0, '获取商品链接失败！');
}

//商品url标识
$goods_sign = md5($goods_url);

//获取商品预览图片
$goods_img = post_string('goods_img');
if($goods_img==''){
	ajax_return(0, '获取商品预览图片失败！');
}

//获取商品名称
$goods_name = post_string('goods_name');
if($goods_name==''){
	ajax_return(0, '获取商品名称失败！');
}

//获取商品价格
$goods_price = floatval(post_string('goods_price'));
if($goods_price==0){
	ajax_return(0, '获取商品价格失败！');
}

//获取缺货提醒的标记
$stock_tip = post_number('stock_tip');

//获取收藏时间
$add_time = get_current_time();

//判断用户是否已经收藏过改商品
$check_sql = "select goods_id from favorite_goods where user_id=$user_id and goods_sign='$goods_sign' limit 1";
$check_arr = $mysql_handle->getRow($check_sql);
if($check_arr){
	ajax_return(0, '您已关注过此商品！');
}

//添加用户收藏
$fav_sql = "insert into favorite_goods (site_id,user_id,goods_sign,goods_name,goods_img,goods_url,goods_price,add_time,stock_tip) 
values ($site_id,$user_id,'$goods_sign','$goods_name','$goods_img','$goods_url','$goods_price','$add_time',$stock_tip)";
$result = $mysql_handle->query($fav_sql);
if($result){
	//更新缓存中的关注数量
	if($site_id==0){
		$class_id = post_number('class_id');
		$site_tag = post_string('site_tag');
		$goods_index = post_number('goods_index');
		if($class_id==0 or $site_tag==''){
			ajax_return(0, '参数错误！');
		}
		$haitao_goods = get_cache('haitao_goods');
		if(isset($haitao_goods[$site_tag][$class_id][$goods_index]['favorite_count'])){
			$favorite_count = $haitao_goods[$site_tag][$class_id][$goods_index]['favorite_count'];
			$haitao_goods[$site_tag][$class_id][$goods_index]['favorite_count'] = $favorite_count + 1;
			set_cache('haitao_goods', $haitao_goods, 3600*24);
		}
	}
	ajax_return(1, '添加关注成功！');
}else{
	ajax_return(0, '添加关注失败！');
}
?>