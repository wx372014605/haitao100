<?php 
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
order by message_id desc limit 20";
$msg_arr = $mysql_handle->getRs($msg_sql);
$msg_arr = array_reverse($msg_arr);
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
	var exp_timer;
	var message_getting = false;//是否正在接收消息的标记，防止重复提交
	var message_sending = false;//是否正在发送消息的标记，防止重复提交

	$(function(){
		//输入框自动获得焦点
		$('#haitao_talk_editor').focus();
		//移动到末尾
		$("#haitao_talk_show").scrollTop($("#haitao_talk_show")[0].scrollHeight);
		//开启定时器刷新未读聊天消息
		setInterval(function(){
			get_message();
		},1000);

		//显示聊天表情列表
		$('#add_exp i').click(function(){
			var talk_exp_main = $("#talk_exp_main");
			if(talk_exp_main.is(":visible")){
				hide_exp_list();
			}else{
				console.log('show');
				clearTimeout(exp_timer);
				$(this).addClass('talk_ico_active');
				$('#talk_exp_main').show();
			}
		});
		$('#add_exp i').mousedown(function(){
			return false;
		});
		$("#talk_exp_main").blur(function(){
			console.log('hide');
			hide_exp_list();
		});
		$("#talk_exp_main img").live('focus',function(){
			setTimeout(function(){
				clearTimeout(exp_timer);
			},100);
		});
		$("#talk_exp_main img").live('blur',function(){
			//hide_exp_list();
		});
		$("#haitao_talk_editor").focus(function(){
			setTimeout(function(){
				clearTimeout(exp_timer);
			},100);
		});
		$("#haitao_talk_editor").blur(function(){
			hide_exp_list();
		});
		$("#haitao_talk_editor").click(function(){
			hide_exp_list();
		});
		
		//插入表情
		$("#talk_exp_main img").live('click',function(){
			var img_src = $(this).attr("src").replace('.png','.gif');
			var img_html = '<img src="'+img_src+'"/>';
			$("#haitao_talk_editor").focus();
			//兼容性处理
			if(document.selection){//如果是ie浏览器
				document.selection.createRange().pasteHTML(img_html);
			}else{//其他浏览器
				exeCommand('InsertImage',img_src);
			}
		});
		
		//发送消息
		$('#haitao_talk_send_button').click(function(){
			send_message();
		});
		$('#haitao_talk_editor').keydown(function(event){
			//按回车键的处理
			if(event.keyCode==13){
				$('#haitao_talk_send_button').focus();
				send_message();
				return false;
			}
		});
	});
	
	//执行document.execCommand指令
	function exeCommand(command,value){
		document.execCommand(command,false,value);
	}

	//隐藏聊天表情列表
	function hide_exp_list(){
		exp_timer = setTimeout(function(){
			$('#add_exp i').removeClass('talk_ico_active');
			$('#talk_exp_main').hide();
		},200);
	}
	
	//获取聊天消息
	function get_message(){
		//防止重复提交
		if(message_getting){
			return;
		}else{
			message_getting = true;
		}
		//接收消息主处理
		$.ajax({
			url:"do.php?act=get_talk_message",
			type:"get",
			data:"user_id=<?php echo $user_id;?>",
			dataType:"json",
			timeout:3000,
			cache:false,
			async:true,
			success:function(result_obj){
				var status = result_obj.status;
				if(status==1){
					//将聊天记录加入列表
					var msg_arr = result_obj.attach;
					if(msg_arr){
						var record_html = '';
						for(var i=0,len=msg_arr.length;i<len;i++){
							record_html += '<div class="talk_record_list readed_message"><p style="color:#008000;">用户：'+msg_arr[i].from_user_id+'　'+msg_arr[i].send_time+'</p><p>'+msg_arr[i].message_content+'</p></div>';
						}
						if(record_html){
							$("#haitao_talk_show").append(record_html);
							//一次限制最多显示20条聊天记录
							var record_list_length = $("#haitao_talk_show .talk_record_list").length;
							if(record_list_length>20){
								var num = record_list_length-20;//获取要隐藏的记录条数
								$("#haitao_talk_show .talk_record_list:lt("+num+")").remove();//隐藏前num条记录
							}
							//移动到末尾
							$("#haitao_talk_show").scrollTop($("#haitao_talk_show")[0].scrollHeight);
						}
					}
				}else{
					var error_html = '<div class="talk_record_list"><i class="record_error_ico"></i><div class="record_error_text">'+result_obj.message+'</div></div>';
					$("#haitao_talk_show").append(error_html);
				}
				message_getting = false;
			},
			error:function(){
				message_getting = false;
			}
		});
	}

	//发送聊天消息
	function send_message(){
		//防止重复提交
		if(message_sending){
			return;
		}else{
			message_sending = true;
		}
		//发送消息主处理
		var msg_content = $.trim($('#haitao_talk_editor').html());
		if(msg_content==''){
			$('#haitao_talk_editor').focus();
		}else{
			$.ajax({
				url:"do.php?act=send_talk_message",
				type:"post",
				data:"user_id=<?php echo $user_id;?>&msg_content="+msg_content,
				dataType:"json",
				timeout:3000,
				cache:false,
				async:true,
				success:function(result_obj){
					var status = result_obj.status;
					if(status==1){
						var attach = result_obj.attach;
						var send_time = attach.send_time;
						//将该条聊天记录加入列表
						var record_html = '<div class="talk_record_list"><p style="color:#E83931;">我　'+send_time+'</p><p>'+msg_content+'</p></div>';
						$("#haitao_talk_show").append(record_html);
						//一次限制最多显示20条聊天记录
						var record_list_length = $("#haitao_talk_show .talk_record_list").length;
						if(record_list_length>20){
							var num = record_list_length-20;//获取要隐藏的记录条数
							$("#haitao_talk_show .talk_record_list:lt("+num+")").remove();//隐藏前num条记录
						}
	
						//隐藏聊天表情列表
						_this.hide_exp_list();
						
						//清空聊天记录输入框
						$("#haitao_talk_editor").empty().focus();
	
						//将超链接都变为新窗口打开
						$("#haitao_talk_show .talk_record_list:last a").attr("target","_blank");
	
						//移动到末尾
						$("#haitao_talk_show").scrollTop($("#haitao_talk_show")[0].scrollHeight);
					}else{
						var error_html = '<div class="talk_record_list"><i class="record_error_ico"></i><div class="record_error_text">'+result_obj.message+'</div></div>';
						$("#haitao_talk_show").append(error_html);
					}
					message_sending = false;
				},
				error:function(){
					message_sending = false;
				}
			});
		}
	}

	//刷新状态
	function refresh_status(){
		$("#talk_class_ul .talk_class_li",parent.document).removeClass("current_li");
		$("#talk_history",parent.document).addClass("current_li");
		parent.iframe_tag = 'talk_history';
		return true;
	}
</script>
</head>
<body>
<div id="haitao_talk_content">
	<div class="haitao_talk_message">
		<div id="haitao_talk_show">
			<?php foreach ($msg_arr as $val){?>
				<?php if($val['from_user_id']){?>
				<div class="talk_record_list"><p style="color:#008000;">用户<?php echo $val['from_user_id']."　".$val['send_time'];?></p><p><?php echo $val['message_content'];?></p></div>
				<?php }else{?>
				<div class="talk_record_list"><p style="color:#E83931;">我<?php echo "　".$val['send_time'];?></p><p><?php echo $val['message_content'];?></p></div>
				<?php }?>
			<?php }?>
			<?php if($msg_arr){?>
			<div class="talk_record_list"><div id="record_history_tip">以上是历史消息</div></div>
			<?php }?>
		</div>
	</div>
	<div class="haitao_talk_bar">
		<div id="add_exp" class="talk_ico" title="插入表情">
			<i></i>
			<div id="talk_exp_main" tabindex="-1">
				<?php foreach ($exp_arr as $val){?>
				<img tabindex="-1" title="<?php echo $val['exp_name'];?>" src="<?php echo WEB_URL;?>images/weixin_exp/ico<?php echo $val['exp_id'];?>.png"/>
				<?php }?>
			</div>
		</div>
		<div id="add_pic" class="talk_ico" title="插入图片">
			<i></i>
			<input id="pic_upload" type="file"/>
		</div>
		<a id="view_talk_history" href="modules.php?menu_tag=talk_history&user_id=<?php echo $user_id;?>" onclick="return refresh_status();">查看聊天记录</a>
	</div>
	<div id="haitao_talk_editor" contentEditable="true" hidefocus="true"></div>
	<div class="haitao_talk_send">
		<a id="haitao_talk_send_button" href="javascript:;"></a>
	</div>
</div>
</body>
</html>