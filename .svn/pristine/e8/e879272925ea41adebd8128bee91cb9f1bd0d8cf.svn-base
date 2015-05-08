<?php
//初始化分页类
$page = new Paging();

//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取用户收藏列表
$fav_sql = "select fg.*,site.site_name from favorite_goods fg left join plugin_site as site on fg.site_id=site.site_id 
where fg.user_id=$user_id and fg.is_delete=0 order by fg.goods_id desc";
$fav_arr = $page->get_record($fav_sql);
ajax_return(1, '获取用户关注列表成功！', array('favorite_list'=>$fav_arr,'favorite_count'=>$page->get_page_count()));
?>