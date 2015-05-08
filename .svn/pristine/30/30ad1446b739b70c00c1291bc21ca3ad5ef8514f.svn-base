<?php
//获取最新商品评论列表
$eval_sql = "select * from goods_eval order by eval_id desc limit 10";
$eval_arr = $mysql_handle->getRs($eval_sql);
ajax_return(1, '获取商品评论列表成功！', array('eval_arr'=>$eval_arr));
?>