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

//获取商品名称
$goods_name = post_string('goods_name');
if($goods_name==''){
	ajax_return(0, '获取商品名称失败！');
}

//获取商品链接
$goods_url = post_string('goods_url');
if($goods_url==''){
	ajax_return(0, '获取商品链接失败！');
}

//商品url标识
$goods_sign = md5($goods_url);

//获取商品图片
$goods_img = post_string('goods_img');

//获取评价等级
$eval_level = post_number('eval_level');

//获取评价内容
$eval_message = post_string('eval_message');

//获取评价时间
$add_time = get_current_time();

//添加评价
$eval_sql = "insert into goods_eval (user_id,goods_sign,goods_name,goods_url,goods_img,eval_level,eval_message,add_time) values 
($user_id,'$goods_sign','$goods_name','$goods_url','$goods_img',$eval_level,'$eval_message','$add_time')";
$result = $mysql_handle->query($eval_sql);
if($result){
	//更新缓存中的评论数量
	$class_id = post_number('class_id');
	$site_tag = post_string('site_tag');
	$goods_index = post_number('goods_index');
	if($class_id>0 and $site_tag!=''){
		$haitao_goods = get_cache('haitao_goods');
		if(isset($haitao_goods[$site_tag][$class_id][$goods_index]['eval_count'])){
			$eval_count = $haitao_goods[$site_tag][$class_id][$goods_index]['eval_count'];
			$haitao_goods[$site_tag][$class_id][$goods_index]['eval_count'] = $eval_count + 1;
			set_cache('haitao_goods', $haitao_goods, 3600*24);
		}
	}
	ajax_return(1, '评价商品成功！');
}else{
	ajax_return(0, '评价商品失败！');
}
?>