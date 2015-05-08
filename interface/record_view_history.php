<?php
//引入翻译函数库
require (WEB_ROOT.'function/translate.php');

//获取用户id
$user_id = post_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取来源网站id
$site_id = post_number('site_id');

//获取商品链接
$goods_url = post_string('goods_url');
if($goods_url==''){
	ajax_return(0, '获取商品链接失败！');
}

//商品url标识
$goods_sign = md5($goods_url);

//获取商品预览图片
$goods_img = post_string('goods_img');
if($goods_img==''){
	write_log('商品链接：'.$goods_url.'获取商品预览图片失败！');
	ajax_return(0, '获取商品预览图片失败！');
}

//获取商品名称
$goods_name = post_string('goods_name');
if($goods_name==''){
	write_log('商品链接：'.$goods_url.'获取商品名称失败！');
	ajax_return(0, '获取商品名称失败！');
}

//获取商品价格
$goods_price = floatval(post_string('goods_price'));
if($goods_price==0){
	ajax_return(0, '获取商品价格失败！');
}

//获取更新时间
$update_time = time();

//判断用户是否访问过该商品
$check_sql = "select goods_id from goods_view_history where goods_sign='$goods_sign' and user_id=$user_id limit 1";
$check_arr = $mysql_handle->getRow($check_sql);
if($check_arr){
	//更新用户浏览记录
	$history_sql = "update goods_view_history set update_time='$update_time',view_count=view_count+1 where goods_sign='$goods_sign' and user_id=$user_id limit 1";
}else{
	//翻译商品中文名称
	$zh_goods_name = en_to_zh($goods_name);
	//添加用户浏览记录
	$history_sql = "insert into goods_view_history (site_id,user_id,goods_sign,goods_name,zh_goods_name,goods_img,goods_url,goods_price,update_time) 
	values ($site_id,$user_id,'$goods_sign','$goods_name','$zh_goods_name','$goods_img','$goods_url','$goods_price',$update_time)";
}
$result = $mysql_handle->query($history_sql);
if($result){
	ajax_return(1, '用户浏览记录成功！');
}else{
	ajax_return(0, '用户浏览记录失败！');
}
?>