<?php
//初始化分页类
$page = new Paging();
$page->page_size = 6;
$page->page_num = 3;
$page->show_first = false;
$page->show_last = false;

//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	ajax_return(0, '获取用户id失败！');
}

//获取未读的消息条数
$count_sql = "select count(msg_id) as msg_count from system_message where msg_type=0 and user_id=$user_id and is_read=0 and is_delete=0";
$count_arr = $mysql_handle->getRow($count_sql);

//获取消息列表
$msg_sql = "select msg_id,msg_title,msg_content,msg_link,send_time,is_read from system_message 
where msg_type=0 and user_id=$user_id and is_delete=0 order by msg_id desc";
$msg_arr = $page->get_record($msg_sql);
ajax_return(1, '获取消息列表成功！', array('message_list'=>$msg_arr,'msg_count'=>$count_arr['msg_count'],'page_html'=>$page->get_page()));
?>