<?php 
//获取素材id
$material_id = get_number('material_id');
if($material_id==0){
	admin_tip(0, '获取素材id失败！');
}

//获取素材详细信息
$material_sql = "select material_type,material_data from weixin_material where material_id=$material_id limit 1";
$material_arr = $mysql_handle->getRow($material_sql);
if($material_arr==false){
	admin_tip(0, '获取素材详细信息失败！');
}

$news_arr = $weixin_handle->data_to_news($material_arr['material_data']);
if(empty($news_arr)){
	admin_tip(0, '图文消息解析失败！');
}
$news_count = count($news_arr);
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
		
		//添加数据到iframe
		var material_data = '<?php echo str_replace("'", "\\'", $material_arr['material_data']);?>';
		var material_obj = eval('('+material_data+')');
		var news_arr = material_obj.item;
		//优先加载第一个iframe并显示内容
		iframe_load_data(0,news_arr[0]);
		//延时加载暂不显示的iframe
		setTimeout(function(){
			for(var i=1;i<news_arr.length;i++){
				iframe_load_data(i,news_arr[i]);
			}
		},1000);
		
		//加载数据到iframe
		function iframe_load_data(iframe_index,info_obj){
			var iframe_obj = document.getElementById('news'+(iframe_index+1));
			var iframe_src = 'modules.php?menu_tag=weixin_news_content&news_id='+(iframe_index+1);
			if(iframe_index==0){
				iframe_src += '&is_top=1';
			}
			iframe_obj.src = iframe_src;
			$(iframe_obj).bind('load',function(){
				var iframe_window = iframe_obj.contentWindow;
				var iframe_doucment = iframe_window.document;
				$("#news_title",iframe_doucment).val(info_obj.title);
				$("#uploaded_p",iframe_doucment).show().find('#uploaded_pic').attr('src',info_obj.picurl);
				$("#uploaded_url",iframe_doucment).val(info_obj.picurl);
				if(info_obj.url!=''){
					$(".news_style:first",iframe_doucment).attr("checked",true);
					$("#news_link",iframe_doucment).val(info_obj.url);
					$("#news_url",iframe_doucment).show();
					$("#news_content",iframe_doucment).hide();
				}else{
					$(".news_style:eq(1)",iframe_doucment).attr("checked",true);
					$("#news_url",iframe_doucment).hide();
					$("#news_content",iframe_doucment).show();
					iframe_window.ue.setContent(info_obj.description);
				}
			});
		}
	});

	//消息编号id
	var news_id = <?php echo $news_count;?>;
	
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

	//修改图文消息主处理
	function edit_message(){
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
	<form action="do.php?act=weixin_news_edit" method="post" onsubmit="return edit_message();">
		<input type="hidden" name="material_id" value="<?php echo $material_id;?>"/>
		<input type="hidden" name="material_type" value="news"/>
		<input type="hidden" id="material_data" name="material_data" value=""/>
		<div class="weixin_news_main">
			<div class="news_list_main">
				<?php foreach ($news_arr as $key=>$val){?>
				<?php if($key==0){?>
				<div id="list<?php echo $key+1;?>" class="news_list top_news" data-ok="1">
					<div class="content_padding">
						<div id="top_news_main">
							<div class="top_news_img"><img src="<?php echo $val->picurl?>" /></div>
							<div class="top_news_title"><span><?php echo $val->title?></span></div>
							<div class="operate_back"></div>
							<div class="top_operate_main">
								<a class="appmsg_edit_a" title="编辑图文消息" style="margin-top:68px;" href="javascript:;" onclick="appmsg_edit(1)"></a>
							</div>
						</div>
					</div>
				</div>
				<?php }else{?>
				<div id="list<?php echo $key+1;?>" class="sub_news news_list" data-ok="1">
					<div class="content_padding">
						<div class="sub_news_main">
							<div class="sub_news_title"><?php echo $val->title?></div>
							<div class="sub_news_img"><img src="<?php echo $val->picurl?>" /></div>
						</div>
					</div>
					<div class="operate_back"></div>
						<div class="sub_operate_main">
							<a class="appmsg_edit_a" title="编辑图文消息" href="javascript:;" onclick="appmsg_edit(<?php echo $key+1;?>)"></a>
							<a class="appmsg_delete_a" title="删除图文消息" href="javascript:;" onclick="appmsg_delete(<?php echo $key+1;?>)"></a>
						</div>
				</div>
				<?php }?>
				<?php }?>
				<div class="appmsg_add">
					<a class="appmsg_add_a" title="添加图文消息" href="javascript:;" onclick="appmsg_add()"></a>
				</div>
			</div>
			<div class="news_content_main">
				<?php foreach ($news_arr as $key=>$val){?>
				<iframe name="news<?php echo $key+1;?>" id="news<?php echo $key+1;?>" frameborder="0" src=""></iframe>
				<?php }?>
			</div>
		</div>
		<div class="weixin_news_submit">
			<input class="submit_button weixin_news_button" type="submit" value="确认修改">
			<a class="submit_button weixin_news_button" href="modules.php?menu_tag=weixin_news_manage">取消</a>
		</div>
	</form>
</div>
</body>
</html>