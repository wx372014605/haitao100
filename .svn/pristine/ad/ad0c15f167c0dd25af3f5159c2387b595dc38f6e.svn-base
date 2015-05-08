<?php
//获取商品链接
$goods_url = get_string('goods_url',0);
if($goods_url==''){
	die('获取商品链接失败！');
}

//获取商品预览图片
$goods_img = get_string('goods_img');
if($goods_img==''){
	die('获取商品预览图片失败！');
}

//获取商品名称
$goods_name = get_string('goods_name');
if($goods_name==''){
	die('获取商品名称失败！');
}

//获取商品价格
$goods_price = floatval(get_string('goods_price'));
if($goods_price==0){
	die('获取商品价格失败！');
}

$class_id = get_number('class_id');
$site_tag = get_string('site_tag');
$goods_index = get_number('goods_index');
if($class_id==0 or $site_tag==''){
	die('参数错误！');
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-cn" lang="zh-cn" xmlns:wb="http://open.weibo.com/wb">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="robots" content="index, follow" />
	<meta name="keywords" content="海淘100,海淘,海淘插件,海淘助手,购物助手,海淘比价,海淘网站,亚马逊,美亚,日亚,德亚,价格曲线,降价提醒" />
	<title>海淘100 - 海淘购物助手</title>
	<meta name="description" content="海淘100是国内领先的海淘购物助手软件，基于多个浏览器平台，能够帮助用户在美国，英国，法国，德国，日本，加拿大亚马逊等海淘网站上，自动计算人民币到手价，展示海淘商品的价格曲线，自动关注热门商品并降价提醒，自动进行包裹跟踪，支持淘宝比价。是国内知名度最高，用户数量最多，用户口碑最好的海淘插件工具，帮助广大淘友简化海淘流程。" />
	<link href="./yi_haitao_files/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="./yi_haitao_files/v2.css" type="text/css" />
	<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/javascript" src="./js/common_fun.js"></script>
	<!-- layer弹出窗口 -->
	<script type="text/javascript" src="./layer/layer.min.js"></script>
	<script type="text/javascript" src="./js/layer_window.js"></script>
	<!-- layer弹出窗口 -->
	<script type="text/javascript">
		$(function(){
			var goods_url = '<?php echo $goods_url;?>';
			var eval_submitting = false;//正在进行评价提交的标记
			var eval_page = 1;//默认显示第一页
			var is_end = false;//是否加载结束的标记
			var is_loading = false;//是否正在加载内容，防止重复加载
			var eval_type = 0;//评论类型

			//获取评论列表
			function get_eval_list(){
				//已经加载结束或正在加载
				if(is_end||is_loading){
					return;
				}
				is_loading = true;
				$.ajax({
		        	url:'interface.php?act=get_goods_eval_list',
		        	type:"GET",
		        	data:'goods_url='+encodeURIComponent(goods_url)+'&page='+eval_page+'&page_size=10',
		        	dataType:"json",
		        	timeout:5000,
		        	cache:false,
		        	async:true,
		        	success:function(result_obj){
		            	if(result_obj.status==1){
		            		var message = result_obj.message;
							var attach = result_obj.attach;
							var eval_arr = attach.eval_arr;
							var eval_length = eval_arr.length;
							if(eval_length>0){
								var eval_html = '';
								for(var i=0;i<eval_length;i++){
									var eval_list = eval_arr[i];
									eval_html += '<div class="eval_list">'+
													'<div class="eval_user_main">'+
														'<p>用户'+eval_list.user_id+'</p>'+
													'</div>'+
													'<div class="eval_content_main">'+
														'<p class="eval_content">'+eval_list.eval_message+'</p>'+
														'<p class="eval_time">'+eval_list.add_time+'</p>'+
													'</div>'+
												'</div>';
								}
								$('#this_goods_eval .no_eval_info').hide();
								if(eval_page==1){
									$('#eval_list_main').empty();
								}
								$('#eval_list_main').append(eval_html).show();
								eval_page += 1;
								var page_count = attach.page_count;
								if(eval_page>page_count){
									is_end = true;
								}
							}else{
								if(eval_page==1){
									$('#this_goods_eval .no_eval_info').show();
									$('#eval_list_main').empty().hide();
								}
								is_end = true;
							}
		            	}else{
		            		layer_alert(result_obj.message);
		            	}
		            	is_loading = false;
		        	}
	            });
			}

			//获取其他商品的评论
			function get_other_eval(){
				$.ajax({
		        	url:'interface.php?act=get_goods_eval_top',
		        	type:"GET",
		        	data:'',
		        	dataType:"json",
		        	timeout:5000,
		        	cache:false,
		        	async:true,
		        	success:function(result_obj){
		            	if(result_obj.status==1){
		            		var message = result_obj.message;
							var attach = result_obj.attach;
							var eval_arr = attach.eval_arr;
							var eval_length = eval_arr.length;
							if(eval_length>0){
								var eval_html = '';
								for(var i=0;i<eval_length;i++){
									var eval_list = eval_arr[i];
									eval_html += '<div class="eval_list">'+
													'<div class="eval_goods_img">'+
														'<a href="'+eval_list.goods_url+'" target="_blank">'+
															'<img src="'+eval_list.goods_img+'"/>'+
														'</a>'+
													'</div>'+
													'<div class="eval_content_main">'+
														'<p class="eval_content">【用户'+eval_list.user_id+'】点评了商品<a href="'+eval_list.goods_url+'" target="_blank">'+eval_list.goods_name+'</a>'+eval_list.eval_message+'</p>'+
														'<p class="eval_time">'+eval_list.add_time+'</p>'+
													'</div>'+
												'</div>';
								}
								$('#other_goods_eval .no_eval_info').hide();
								$('#other_eval_list').empty().append(eval_html).show();
							}else{
								$('#other_goods_eval .no_eval_info').show();
								$('#other_eval_list').empty().hide();
							}
		            	}else{
		            		layer_alert(result_obj.message);
		            	}
		        	}
	            });
			}
			
			//提交评论
			$('#eval_submit_button').click(function(){
				if(eval_submitting){
					return false;
				}else{
					eval_submitting = true;
				}
				var eval_message = $('#eval_message');
				var eval_message_val = $.trim(eval_message.val());
				if(eval_message_val==''){
					layer_tip(eval_message,'请填写评价内容！',{'guide':2});
					eval_message.focus();
					eval_submitting = false;
					return false;
				}
				var eval_level_input = $('#goods_eval_add .eval_level_input:checked');
				var eval_level_val = eval_level_input.val();
				var goods_url = '<?php echo $goods_url;?>';
				var goods_img = '<?php echo $goods_img;?>';
				var goods_name = '<?php echo $goods_name;?>';
				var goods_price = '<?php echo $goods_price;?>';
				var class_id = <?php echo $class_id;?>;
				var site_tag = '<?php echo $site_tag?>';
				var goods_index = <?php echo $goods_index?>;

				$.ajax({
		        	url:'interface.php?act=goods_eval_submit',
		        	type:"POST",
		        	data:'goods_url='+encodeURIComponent(goods_url)+'&goods_img='+goods_img+'&goods_name='+goods_name+'&goods_price='+goods_price+'&class_id='+class_id+'&site_tag='+site_tag+'&goods_index='+goods_index+'&eval_level='+eval_level_val+'&eval_message='+encodeURIComponent(eval_message_val),
		        	dataType:"json",
		        	timeout:5000,
		        	cache:false,
		        	async:true,
		        	success:function(result_obj){
		            	if(result_obj.status==1){
		            		var goods_eval_form = $('#goods_eval_form');
		            		goods_eval_form[0].reset();
							
		            		eval_page = 1;
		            		is_end = false;
		            		$('#goods_eval_type li:first').click();
		            		get_eval_list();
		            		get_other_eval();
		            	}else{
		            		layer_alert(result_obj.message);
		            	}
		            	eval_submitting = false;
		        	}
	            });
			});
			//切换评论类型
			$('#goods_eval_type li').click(function(){
				$('#goods_eval_type li').removeClass('current_li');
				$(this).addClass('current_li');
				eval_type = $(this).attr('eval_type');
				var show_div = $(this).attr('show_div');
				$('#goods_eval_list .goods_eval_tab').hide();
				$('#'+show_div).show();
				var loaded = $(this).attr('loaded');
				if(loaded=='false'){
					var load_fun = $(this).attr('load_fun');
					eval(load_fun+'()');
					$(this).attr('loaded','true');
				}
			});

			//初次加载评论
			$('#goods_eval_type li:first').click();
			//随滚动条滚动加载评论
			$(window).scroll( function(){
				if(eval_type==0){
					var window_height = $(window).height();
					var scroll_top = $(window).scrollTop();
					var totalHeight = window_height + scroll_top;//网页可视区域+滚动条高度
					if(totalHeight >= $(document).height()-2){
						get_eval_list();
					}
				}
			});
		});
	</script>
</head>
<body>
<div id="goods_eval_info">
	<div id="goods_eval_add">
		<form id="goods_eval_form" action="interface.php?act=goods_eval_submit" method="post">
			<div class="eval_level_main">
				<label>
					<input class="eval_level_input" name="eval_level" value="0" checked="checked" type="radio">
					<i id="good_eval_level"></i>
				</label>
				<label>
					<input class="eval_level_input" name="eval_level" value="1" type="radio">
					<i id="normal_eval_level"></i>
				</label>
				<label>
					<input class="eval_level_input" name="eval_level" value="2" type="radio">
					<i id="bad_eval_level"></i>
				</label>
			</div>
			<div class="eval_content_box">
				<textarea id="eval_message" name="eval_message" placeholder="在此处输入评价，你的评价对其他买家有很大帮助！"></textarea>
			</div>
			<div class="eval_submit">
				<a id="eval_submit_button">提交评价</a>
			</div>
		</form>
	</div>
	<div id="goods_eval_list">
		<ul id="goods_eval_type">
			<li class="current_li" show_div="this_goods_eval" eval_type="0" loaded="false" load_fun="get_eval_list">热门评论</li>
			<li show_div="other_goods_eval" eval_type="1" loaded="false" load_fun="get_other_eval">其他商品的评论</li>
		</ul>
		<div id="this_goods_eval" class="goods_eval_tab">
			<div class="no_eval_info">还没有用户对此商品进行点评！</div>
			<div id="eval_list_main"></div>
		</div>
		<div id="other_goods_eval" class="goods_eval_tab">
			<div class="no_eval_info">还没有用户的点评！</div>
			<div id="other_eval_list"></div>
		</div>
	</div>
</div>
</body>
</html>