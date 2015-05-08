<?php 
//初始化分页类
$page = new Paging();
$page->page_size = 20;

//获取用户id
$user_id = get_number('user_id');
if($user_id==0){
	admin_tip(0, '获取用户id失败！');
}

//获取聊天表情列表
$exp_sql = "select exp_id,exp_name,exp_code from weixin_exp";
$exp_arr = $mysql_handle->getRs($exp_sql);

//获取聊天消息历史记录
$msg_sql = "select message_id,message_content,send_time,is_read,from_user_id from talk_message  
where (from_admin_id=$admin_id and to_user_id=$user_id) or (from_user_id=$user_id and to_admin_id=$admin_id and is_read=1) 
order by message_id desc";
$msg_arr = $page->get_record($msg_sql);
$msg_arr = array_reverse($msg_arr);
$msg_count = count($msg_arr);
if($msg_arr){
	//还原表情
	$code_arr = array();
	$img_arr = array();
	foreach ($exp_arr as $val){
		$code_arr[] = $val['exp_code'];
		$img_arr[] = '<img title="'.$val['exp_name'].'" src="'.WEB_URL.'images/weixin_exp/ico'.$val['exp_id'].'.gif'.'"/>';
	}
	foreach ($msg_arr as $key=>$val){
		$msg_arr[$key]['message_content'] = str_replace($code_arr, $img_arr, $msg_arr[$key]['message_content']);
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/talk_message.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript">
	$(function(){
		//聊天记录翻页
		$('#talk_page_input').live('keyup',function(event){
			//按回车键的处理
			if(event.keyCode==13){
				var page_val = parseInt($(this).val());
				if(isNaN(page_val)){
					page_val = 1;
				}
				location.href = '?menu_tag=talk_history&user_id=<?php echo $user_id;?>&page='+page_val;
			}
		});

		load_page();
	});

	//加载消息分页
	function load_page(){
		var page_count = <?php echo $page->get_page_count();?>;
		var message_page = <?php echo $page->page;?>;
		var page_html = '<a id="first_page" class="page_a'+(message_page<=1?" disabled_page":"")+'" title="首页" href="'+(message_page<=1?"javascript:;":"?page=1")+'"></a>'+
						'<a id="pre_page" class="page_a'+(message_page<=1?" disabled_page":"")+'" title="上一页"  href="'+(message_page<=1?"javascript:;":"?page="+(message_page-1))+'"></a>'+
						'<span>第</span>'+
						'<input id="talk_page_input" type="text" value="'+message_page+'" maxlength="5"/>'+
						'<span>页/<font id="page_count">'+page_count+'</font>页</span>'+
						'<a id="next_page" class="page_a'+(message_page>=page_count?" disabled_page":"")+'" title="下一页"  href="'+(message_page>=page_count?"javascript:;":"?page="+(message_page+1))+'"></a>'+
						'<a id="last_page" class="page_a'+(message_page>=page_count?" disabled_page":"")+'" title="尾页"  href="'+(message_page>=page_count?"javascript:;":"?page="+page_count)+'"></a>';
		$('#talk_history_page').empty().append(page_html);
	}
</script>
</head>
<body>
<div id="haitao_talk_history">
	<div id="talk_history_content">
		<?php foreach ($msg_arr as $key=>$val){?>
			<?php if($key==0){?>
			<div class="talk_record_list"><div id="record_history_tip"><font class="talk_history_date"><?php echo get_date($val['send_time']);?></font></div></div>
			<?php }?>
			<?php if($val['from_user_id']){?>
			<div class="talk_record_list"><p style="color:#008000;">用户<?php echo $val['from_user_id']."　".$val['send_time'];?></p><p><?php echo $val['message_content'];?></p></div>
			<?php }else{?>
			<div class="talk_record_list"><p style="color:#E83931;">我<?php echo "　".$val['send_time'];?></p><p><?php echo $val['message_content'];?></p></div>
			<?php }?>
			<?php if($key<$msg_count-1 and get_date($val['send_time'])!=get_date($msg_arr[$key+1]['send_time'])){?>
			<div class="talk_record_list"><div id="record_history_tip"><font class="talk_history_date"><?php echo get_date($msg_arr[$key+1]['send_time']);?></font></div></div>
			<?php }?>
		<?php }?>
		<?php if(empty($msg_arr)){?>
		<div class="talk_record_list"><div id="record_history_tip">暂无历史消息！</div></div>
		<?php }?>
	</div>
	<div id="talk_history_page"></div>
</div>
</body>
</html>