<?php
//获取聊天表情列表
$exp_sql = "select exp_id,exp_name,exp_code from weixin_exp limit 32";
$exp_arr = $mysql_handle->getRs($exp_sql);
ajax_return(1, '获取聊天表情列表成功！', $exp_arr);
?>