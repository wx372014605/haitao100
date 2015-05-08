<?php 
//获取所有高级接口的公众帐号
$account_sql = "select account_name,platform_id from weixin_account where type=1";
$account_arr = $mysql_handle->getRs($account_sql);

//获取微信表情
$exp_sql = "select * from weixin_exp";
$exp_arr = $mysql_handle->getRs($exp_sql);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/mass_send.css" />
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>微信管理</span>
		<span>&gt;&gt;</span>
		<span>群发消息</span>
	</div>
	<div class="mass_class_bar">
		<ul class="mass_class_ul">
			<li class="mass_class_li current_li"><a href="javascript:;">新建群发消息</a></li>
			<li class="mass_class_li"><a href="modules.php?menu_tag=weixin_send_history">已发送</a></li>
		</ul>
	</div>
	<div class="mass_send_tip">
		为保障用户体验，微信公众平台严禁恶意营销以及诱导分享朋友圈，严禁发布色情低俗、暴力血腥、政治谣言等各类违反法律法规及相关政策规定的信息。一旦发现，我们将严厉打击和处理。
	</div>
	<div class="mass_send_padding">
		<form action="" method="post" onsubmit="return start_send()">
			<input type="hidden" id="msg_type" name="msg_type" value="text" />
			<input type="hidden" id="material_id" name="material_id" value="0" />
			<div class="mass_send_range">
				<span>发送平台：</span>
				<select id="platform_id" name="platform_id">
					<option value="">所有平台</option>
					<?php foreach ($account_arr as $val){?>
					<option value="<?php echo $val['platform_id'];?>"><?php echo sub_str($val['account_name'],20);?></option>
					<?php }?>
				</select>
				<span class="search_tip">性别：</span>
				<select id="sex" name="sex">
					<option value="0">全部</option>
					<option value="1">男</option>
					<option value="2">女</option>
				</select>
				<span class="search_tip">关注时间：</span>
				<input class="Wdate" id="start_time" type="text" name="start_time" value="" onclick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
				<span>至</span>
				<input class="Wdate" id="end_time" type="text" name="end_time" value="" onclick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
				<span id="area_box"></span>
			</div>
			<div class="msg_content_main">
				<div class="msg_class_bar">
					<ul class="msg_class_ul">
						<li id="text_message" class="disable_select" title="文字消息"><i></i>文字</li>
						<li id="image_message" class="disable_select" title="图片消息"><i></i>图片</li>
						<li id="voice_message" class="disable_select" title="语音消息"><i></i>语音</li>
						<li id="video_message" class="disable_select" title="视频消息"><i></i>视频</li>
						<li id="news_message" class="disable_select" title="图文消息"><i></i>图文</li>
						<li id="music_message" class="disable_select" title="音乐消息"><i></i>音乐</li>
					</ul>
				</div>
				<div id="msg_text_main">
					<div id="msg_text_content" hidefocus="true" contentEditable="true" oncontextmenu="return false;" onkeyup="words_limit($(this))"></div>
					<div class="weixin_text_bar">
			    		<a class="exp_button" href="javascript:;" onclick="toggle_exp()"></a>
			    		<span id="left_words">还可以输入570字</span>
			    		<div id="weixin_exp_main" class="weixin_exp_main" tabindex="-1">
			    			<ul class="weixin_exp_ul">
			    				<?php foreach ($exp_arr as $val){?>
			    				<li tabindex="-1">
			    					<img title="<?php echo $val['exp_name'];?>" src="<?php echo WEB_URL.'images/weixin_exp/ico'.$val['exp_id'].'.png';?>" class="exp_ico" exp_id="1"/>
			    				</li>
			    				<?php }?>
			    			</ul>
			    		</div>
			    	</div>
				</div>
				<div id="msg_content_box"></div>
			</div>
			<div class="mass_send_button"><input class="submit_button" type="submit" value="群发"/></div>
			<div class="send_result">
				<div class="send_result_bar">
					<div class="percent_tip">进度</div>
					<div class="percent_num">0.00%</div>
					<div class="percent_show">
						<div id="percent_bar"></div>
					</div>
				</div>
				<div class="send_result_log"></div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript" src="../js/area.js"></script>
<script type="text/javascript" charset="utf-8" src="../My97DatePicker/WdatePicker.js"></script>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
<script type="text/javascript">
	$(function(){
		//绑定切换选项事件
		$(".mass_class_li").click(function(){
			$(".mass_class_li").removeClass("current_li");
			$(this).addClass("current_li");
		});
		
		//省市地区选择
		var area_box = load_area_widget('area_box',
			{
				'show_district':false
			}
		);
		area_box.find('span').addClass('search_tip').text('群发地区：');


		
		//初始化群发消息类型
		change_msg_type(msg_type);
		//根据群发消息类型执行不同的操作
		function change_msg_type(msg_type){
			switch(msg_type){
			case 'text':
				$(".msg_class_ul li").removeClass('selected_li');
				$("#text_message").addClass('selected_li');
				$("#msg_content_box").hide();
				$("#msg_text_main").show();
				$("#material_id").val(0);
				setTimeout(function(){
					$("#msg_text_content").focus();
				},100);
				break;
			case 'image':
				layer_iframe('选择素材', 'modules.php?menu_tag=weixin_image_select');
				break;
			case 'voice':
				layer_iframe('选择素材', 'modules.php?menu_tag=weixin_voice_select');
				break;
			case 'video':
				break;
			case 'news':
				layer_iframe('选择素材', 'modules.php?menu_tag=weixin_news_select');
				break;
			case 'music':
				layer_iframe('选择素材', 'modules.php?menu_tag=weixin_music_select');
				break;
			}
		}
		$("#text_message").click(function(){
			msg_type = 'text';
			$("#msg_type").val(msg_type);
			change_msg_type(msg_type);
		});
		$("#image_message").click(function(){
			change_msg_type('image');
		});
		$("#voice_message").click(function(){
			change_msg_type('voice');
		});
		$("#video_message").click(function(){
			alert('功能尚未开发！');
			return false;
			change_msg_type(msg_type);
		});
		$("#news_message").click(function(){
			change_msg_type('news');
		});
		$("#music_message").click(function(){
			change_msg_type('music');
		});

		//执行document.execCommand指令
		function exeCommand(command, value){
			document.execCommand(command, false, value);
		}

		//绑定插入表情事件
		$("#weixin_exp_main .exp_ico").click(function(){
			var exp_id = $(this).attr("exp_id");
			var img_ico = $(this).attr("src").replace('.png','.gif');
			var img_html = '<img src="'+img_ico+'"/>';
			$("#msg_text_content").focus();
			//兼容性处理
			if(document.selection){//如果是ie浏览器
				document.selection.createRange().pasteHTML(img_html);
			}else{//其他浏览器
				exeCommand('InsertImage',img_ico);
			}
			words_limit($("#msg_text_content"));
		});
		$("#weixin_exp_main").blur(function(){
			hide_exp($(this));
		});
		$("#msg_text_content").focus(function(){
			setTimeout(function(){
				clearTimeout(exp_t);
			},100);
		});
		$("#msg_text_content").click(function(){
			hide_exp($("#weixin_exp_main"));
		});
		$("#msg_text_content").blur(function(){
			hide_exp($("#weixin_exp_main"));
		});
		$("#weixin_exp_main *").focus(function(){
			setTimeout(function(){
				clearTimeout(exp_t);
			},100);
		});
		/*
		$("#weixin_exp_main *").blur(function(){
			hide_exp($("#weixin_exp_main"));
		});
		*/
	});

	var msg_type = 'text';//群发消息类型
	var max_length = 600;//最多输入字符个数
	var exp_t;//表情栏计时器

	//限制输入文字个数
	function words_limit(ob){
		var input_words = ob.html().replace(/<\/?br\/?>$/,'');
		var img_reg = /<img\s+src=".+?"\s*[\/]?>/ig;
		
		var img_arr = input_words.match(img_reg);
		var result = input_words.replace(img_reg,'');
		var words_length = result.length;
		if(img_arr!=undefined){
			words_length += img_arr.length*6;//每个图片当成6个字符计算
		}
		if(words_length>max_length){
			$("#left_words").html('已超出<font style="color:#B11516;font-weight:bold;">'+(words_length-max_length)+'</font>字');
			return false;
		}else{
			$("#left_words").html('还可以输入'+(max_length-words_length)+'字');
			return true;
		}
	}

	//显示隐藏表情栏
	function toggle_exp(){
		var exp_ob = $("#weixin_exp_main");
		if(exp_ob.is(":visible")){
			hide_exp(exp_ob);
		}else{
			show_exp(exp_ob);
		}
	}
	//显示表情栏
	function show_exp(exp_ob){
		clearTimeout(exp_t);
		exp_ob.fadeIn(500);
		exp_ob.focus();
	}
	//隐藏表情栏
	function hide_exp(exp_ob){
		exp_t = setTimeout(function(){
			exp_ob.fadeOut(500);
		},200);
	}

	//给字符串添加转义
	function add_slashes(str){
		return str.replace(/["']/g,"'");
	}
	
	/*
	*群发消息主处理
	*author zjq
	*/
	var is_sending = false;
	var totle_count = 0;
	var success_count = 0;
	var current_index = 0;
	var retry_times = 0;
	function start_send(){
		//判断群发是否正在进行
		if(is_sending){
			layer_alert('消息正在发送中！请稍后再试！');
			return false;
		}
		//检测群发数据是否有效
		var platform_id = $.trim($("#platform_id").val());
		var sex = parseInt($("#sex").val());
		var start_time = $.trim($("#start_time").val());
		var end_time = $.trim($("#end_time").val());
		if(start_time!='' && end_time!='' && strtotime(start_time)>=strtotime(end_time)){
			layer_tip($("#end_time"),'用户关注开始时间不能大于结束时间！');
			return false;
		}
		var province_id = parseInt($("#province_id").val());
		var city_id = parseInt($("#city_id").val());
		var material_id = parseInt($("#material_id").val());
		var msg_data = '';
		switch(msg_type){
		case 'text':
			var msg_text_content = $("#msg_text_content");
			var msg_text_content_val = $.trim(msg_text_content.html());
			if(msg_text_content_val==''){
				layer_tip(msg_text_content,'请填写您要发送的内容！');
				msg_text_content.focus();
				return false;
			}else{
				msg_data = '{"content":"'+add_slashes(msg_text_content_val)+'"}';
			}
			break;
		case 'image':
			break;
		case 'voice':
			break;
		case 'video':
			break;
		case 'news':
			break;
		case 'music':
			break;
		}
		
		//开始群发
		setTimeout(function(){
			is_sending = true;
			$.ajax({
				url:"do.php?act=weixin_mass_send",
				type:"POST",
				data:"material_id="+material_id+"&msg_data="+msg_data+"&msg_type="+msg_type+"&platform_id="+platform_id+"&sex="+sex+"&start_time="+start_time+"&end_time="+end_time+"&province_id="+province_id+"&city_id="+city_id+"&current_index="+current_index,
				dataType:"text",
				timeout:20000,//超时时间设置大一些，防止超时
				cache:false,
				async:true,
				success:function(result){
					try{
						var result_obj = eval('('+result+')');
					}catch(e){
						if(retry_times<10){
							console.log(result);
							$(".send_result_log").append('<p style="color:red;font-weight:bold;">结果解析失败！正在进行第'+(retry_times+1)+'次重试！</p>');
							is_sending = false;
							retry_times += 1;
							start_send();
						}else{
							$(".send_result_log").append('<p style="color:red;font-weight:bold;">已超出重试次数上限！群发进程结束</p>');
						}
						return;
					}
					retry_times = 0;
					var status = result_obj.status;
					var message = result_obj.message;
					if(status==-1){//群发结束
						$(".send_result_log").append('<p style="color:#E03B0A;font-weight:bold;">微信群发进程结束...</p>');
						var success_percent = totle_count==0?0:(100*success_count/totle_count).toFixed(2);
						$(".send_result_log").append('<p style="color:#E03B0A;font-weight:bold;">成功发送的消息比率为：'+success_percent+'%</p>');
						layer_alert(message);
						is_sending = false;
						totle_count = 0;
						success_count = 0;
						current_index = 0;
					}else{
						if(current_index==0){//群发开始的处理
							$(".send_result_log").empty();
							$("#percent_bar").width(0);
							$(".percent_num").text("0.00%");
							totle_count = result_obj.attach.totle_count;
							if(totle_count==0){
								layer_alert(message);
								is_sending = false;
								return;
							}else{
								$(".send_result_log").append('<p style="color:#E03B0A;font-weight:bold;">微信群发进程开始...</p>');
								$(".send_result_log").append('<p style="color:#E03B0A;font-weight:bold;">消息发送过程中请勿关闭此窗口！</p>');
								$(".send_result").show();
							}
						}
						if(current_index>=totle_count){//群发结束的处理
							var cost_time = result_obj.attach.cost_time;
							$(".send_result_log").append('<p style="color:#E03B0A;font-weight:bold;">微信群发进程结束'+(cost_time?'，耗时'+cost_time:'')+'...</p>');
							$(".send_result_log").append('<p style="color:#E03B0A;font-weight:bold;">成功'+success_count+',失败'+(totle_count-success_count)+',成功率：'+(100*success_count/totle_count).toFixed(2)+'%</p>');
							is_sending = false;
							totle_count = 0;
							success_count = 0;
							current_index = 0;
						}else{
							//显示进度条
							current_index += 1;
							var max_width = $(".percent_show").width();
							var current_percent = (100*current_index/totle_count).toFixed(2);
							var current_width = parseInt(max_width*current_percent/100);
							$("#percent_bar").width(current_width);
							$(".percent_num").text(current_percent+"%");
							
							if(status==1){
								success_count += 1;
								var log_color = 'style="color:#5AAA4B;"';
							}else{
								var log_color = '';
							}
							$(".send_result_log").append('<p '+log_color+'>'+result_obj.message+'</p>');
							is_sending = false;
							start_send();
						}
					}
				},
				error: function(XMLHttpRequest,textStatus,errorThrown){
					//alert(XMLHttpRequest.status);
					//alert(XMLHttpRequest.readyState);
					//alert(textStatus);
					if(textStatus=="timeout"){
						is_sending = false;
						start_send();
					}
	            }
			})
		},100);
		return false;
	}
</script>
</body>
</html>