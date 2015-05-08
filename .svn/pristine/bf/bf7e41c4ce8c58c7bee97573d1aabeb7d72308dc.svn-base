<?php
//初始化分页类
$page = new Paging();
$page->page_size = 20;

//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取聊天消息历史记录
$msg_sql = "select msg.message_id,msg.message_content,msg.send_time,msg.is_read,ad.admin_name from talk_message as msg 
left join admins as ad on msg.from_admin_id=ad.admin_id where msg.from_user_id=$user_id or (msg.to_user_id=$user_id and msg.is_read=1) 
order by msg.message_id desc";
$msg_arr = $page->get_record($msg_sql);
if($msg_arr){
	//还原表情
	$exp_sql = "select * from weixin_exp";
	$exp_arr = $mysql_handle->getRs($exp_sql);
	$code_arr = array();
	$img_arr = array();
	foreach ($exp_arr as $val){
		$code_arr[] = $val['exp_code'];
		$img_arr[] = '<img title="'.$val['exp_name'].'" src="icons/talk_exp/ico'.$val['exp_id'].'.gif'.'"/>';
	}
	foreach ($msg_arr as $key=>$val){
		$msg_arr[$key]['message_content'] = str_replace($code_arr, $img_arr, $msg_arr[$key]['message_content']);
	}
}
ajax_return(1, '获取聊天消息历史记录成功！',array('msg_arr'=>$msg_arr,'page_count'=>$page->get_page_count()));
?>