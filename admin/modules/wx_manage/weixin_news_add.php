<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/material.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/config.js"></script>
<script type="text/javascript">
	$(function(){
		$("#top_news_main").hover(
			function(){
				$(this).find(".operate_back").show();
				$(this).find(".top_operate_main").show();
			},
			function(){
				$(this).find(".operate_back").hide();
				$(this).find(".top_operate_main").hide();
			}
		);
		$(".sub_news").live("mouseover", function(){
			$(this).find(".operate_back").show();
			$(this).find(".sub_operate_main").show();
		});
		$(".sub_news").live("mouseout", function(){
			$(this).find(".operate_back").hide();
			$(this).find(".sub_operate_main").hide();
		});
		
		//加载图文消息内容编辑iframe
		var top_iframe = '<iframe name="news1" id="news1" frameborder="0" src="modules.php?menu_tag=weixin_news_content&is_top=1&news_id=1"></iframe>';
		$(".news_content_main").append(top_iframe);
	});

	//消息编号id
	var news_id = 1;
	
	//增加图文消息数目
	function appmsg_add(){
		if($(".sub_news").length>=7){
			alert("你最多添加8条图文！");
			return;
		}
		news_id += 1;
		var news_str = '<div class="sub_news news_list" id="list'+news_id+'" data-ok="0">';
		news_str +=	'		<div class="content_padding">';
		news_str +=	'			<div class="sub_news_main">';
		news_str +=	'				<div class="sub_news_title">标题</div>';
		news_str +=	'				<div class="sub_news_img">缩略图</div>';
		news_str +=	'			</div>';
		news_str +=	'		</div>';
		news_str +=	'		<div class="operate_back"></div>';
		news_str +=	'		<div class="sub_operate_main">';
		news_str +=	'			<a class="appmsg_edit_a" title="编辑图文消息" href="javascript:;" onclick="appmsg_edit('+news_id+')"></a>';
		news_str +=	'			<a class="appmsg_delete_a" title="删除图文消息" href="javascript:;" onclick="appmsg_delete('+news_id+')"></a>';
		news_str +=	'		</div>';
		news_str +=	'	</div>';
		$(".appmsg_add").before(news_str);
		
		var sub_iframe = $('<iframe name="news'+news_id+'" id="news'+news_id+'" frameborder="0" src=""></iframe>');
		$(".news_content_main iframe").hide();
		$(".news_content_main").append(sub_iframe);
		sub_iframe.attr('src','modules.php?menu_tag=weixin_news_content&news_id='+news_id+'');

		//父iframe高度自适应
		$("#material_content",parent.document).height($(document).height());
	}

	//编辑图文消息内容
	function appmsg_edit(news_id){
		var iframe_id = "news"+news_id;
		$(".news_content_main iframe").hide();
		$("#"+iframe_id).show();
	}

	//删除图文消息内容
	function appmsg_delete(news_id){
		if(confirm('是否要删除这个条目？')){
			$("#list"+news_id).remove();
			var iframe_ob = $("#news"+news_id);
			if(iframe_ob.is(":visible")){
				$("#news1").show();
			}
			iframe_ob.remove();
		}
	}

	//添加图文消息主处理
	function add_message(){
		//存储图文消息数据的字符串
		var material_data = '{"item":[';
		//验证通过的标记
		var check_pass = true;
		$(".news_list").each(function(index,elem){
			var list_ob = $(elem);
			var list_id = list_ob.attr("id");
			var news_id = "news"+list_id.replace("list","");
			var data_ok = list_ob.attr("data-ok");
			var iframe_window = document.getElementById(news_id).contentWindow;
			var iframe_doucment = iframe_window.document;
			if(data_ok!="1"){
				$(".news_content_main iframe").hide();
				$("#"+news_id).show();
				iframe_window.layer_tip($(iframe_doucment).find("#submit_button"),"第"+(index+1)+"个图文消息没有保存！");
				check_pass = false;
				return false;
			}else{
				material_data += '{';
				var news_title = $("#news_title",iframe_doucment);
				var news_title_val = $.trim(news_title.val());
		        if(news_title_val==""){
		            $(".news_content_main iframe").hide();
					$("#"+news_id).show();
					iframe_window.layer_tip(news_title,"请输入消息标题！");
					news_title.focus();
					check_pass = false;
					return false;
		        }
		        material_data += '"title":"'+news_title_val.replace(/["']/g,"\\'")+'"';
		        var uploaded_url = $("#uploaded_url",iframe_doucment);
		        var uploaded_url_val = $.trim(uploaded_url.val());
		        if(uploaded_url_val==""){
		            $(".news_content_main iframe").hide();
					$("#"+news_id).show();
					iframe_window.layer_tip($("#picurl",iframe_doucment),"请选择封面图片！");
					check_pass = false;
		            return false;
		        }
		        material_data += ',"picurl":"'+uploaded_url_val+'"';
		        var news_type = $(".news_style:checked",iframe_doucment).val();
		        if(news_type=="0"){
		            var news_link = $("#news_link",iframe_doucment);
		            var news_link_val = $.trim(news_link.val());
		            if(news_link_val==""){
		                $(".news_content_main iframe").hide();
						$("#"+news_id).show();
						iframe_window.layer_tip(news_link,"请输入消息的链接！");
						news_link.focus();
						check_pass = false;
						return false;
		            }
		            if(!my_regexp['url'].test(news_link_val)){
		            	$(".news_content_main iframe").hide();
						$("#"+news_id).show();
						iframe_window.layer_tip(news_link,"消息的链接格式不正确！");
						news_link.focus();
						check_pass = false;
						return false;
		            }
		            material_data += ',"description":"","url":"'+news_link_val.replace(/["']/g,"\\'")+'"';
		        }else{
		            var news_content = iframe_window.ue.getContent();
		            if(news_content==""){
		                $(".news_content_main iframe").hide();
						$("#"+news_id).show();
						iframe_window.layer_tip($("#news_content",iframe_doucment),"请输入消息的内容！");
						check_pass = false;
						return false;
		            }
		            news_content = news_content.replace(/["']/g,"\\'");
		            material_data += ',"description":"'+news_content+'","url":""';
		        }
		        material_data += '},';
			}
		});
		material_data = material_data.replace(/,$/,'');
		material_data += ']}';
		
		if(!check_pass){
			return false;
		}
		$("#material_data").val(material_data);
	}
</script>
</head>
<body>
<div class="content_main">
	<form action="do.php?act=weixin_news_add" method="post" onsubmit="return add_message();">
		<input type="hidden" name="material_type" value="news"/>
		<input type="hidden" id="material_data" name="material_data" value=""/>
		<div class="weixin_news_main">
			<div class="news_list_main">
				<div id="list1" class="top_news news_list" data-ok="0">
					<div class="content_padding">
						<div id="top_news_main">
							<div class="top_news_img">封面图片</div>
							<div class="top_news_title"><span>标题</span></div>
							<div class="operate_back"></div>
							<div class="top_operate_main">
								<a class="appmsg_edit_a" title="编辑图文消息" style="margin-top:68px;" href="javascript:;" onclick="appmsg_edit(1)"></a>
							</div>
						</div>
					</div>
				</div>
				<div class="appmsg_add">
					<a class="appmsg_add_a" title="添加图文消息" href="javascript:;" onclick="appmsg_add()"></a>
				</div>
			</div>
			<div class="news_content_main"></div>
		</div>
		<div class="weixin_news_submit">
			<input class="submit_button weixin_news_button" type="submit" value="确认添加">
			<a class="submit_button weixin_news_button" href="modules.php?menu_tag=weixin_news_manage">取消</a>
		</div>
	</form>
</div>
</body>
</html>