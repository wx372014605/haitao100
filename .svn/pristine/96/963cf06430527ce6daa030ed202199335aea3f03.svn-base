<?php 
//获取是否是第一条消息的标记
$is_top = get_number('is_top');

//获取news_id
$news_id = get_number('news_id');
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
<!-- 引入ajax无刷新上传文件插件 -->
<script type="text/javascript" src="../js/ajaxfileupload.js"></script>
<!-- 引入ajax无刷新上传文件插件 -->
<script type="text/javascript">
	$(function(){
		$(".news_style").change(function(){
			if($(this).val()==0){
				$("#news_url").show();
				$("#news_content").hide();
			}else{
				$("#news_url").hide();
				$("#news_content").show();
			}
		});
		//页面加载完成初始化
		var checked_style = $(".news_style:checked").val();
		if(checked_style==0){
			$("#news_url").show();
			$("#news_content").hide();
		}else{
			$("#news_url").hide();
			$("#news_content").show();
		}
	});

	//异步上传文件
	function ajaxFileUpload(){
		$.ajaxFileUpload({
			url:'do.php?act=weixin_newsImg_upload',
			secureuri:false,
			fileElementId:'picurl',
			dataType: 'json',
			timeout:3000,
			cache:false,
			async:false,
			success: function (result_obj){
				if(result_obj.status==1){
					//删除旧图片节省空间
					var old_uploaded_url = $("#uploaded_url").val();
					delete_pic_action(old_uploaded_url);
					//显示新上传的图片
					var uploaded_url = result_obj.attach.uploaded_url;
					$("#uploaded_url").val(uploaded_url);
					$("#uploaded_pic").attr("src",uploaded_url);
					$("#uploaded_p").show();
				}else{
					alert(result_obj.message);
				}
			}
		})
	}

	//删除图片操作
	function delete_pic_action(pic_url){
		if(pic_url=="")return;
		$.ajax({
			url:"do.php?act=ajax_file_delete",
			type:"POST",
			data:"file_url="+pic_url,
			dataType:"text",
			timeout:3000,
			cache:false
		})
	}

	//删除已经上传的图片
	function delete_uploaded_pic(){
		if(confirm('是否要删除这张图片？')){
			var uploaded_url = $("#uploaded_url").val();
			delete_pic_action(uploaded_url);
			$("#uploaded_p").hide();
			$("#uploaded_url").val("");
			$("#uploaded_pic").attr("src","");
			$("#picurl").clearFileInput();
		}
	}

	//清空file HTML控件的值
    $.fn.clearFileInput = function(){
        return this.each(function(){
            if (/MSIE/.test(navigator.userAgent)){
                $(this).replaceWith($(this).clone(true));
            }else{
                $(this).val('');
            }
        });
    };

    //保存显示修改的结果
    function modify_show(){
        var news_title = $("#news_title");
        var news_title_val = $.trim(news_title.val());
        if(news_title_val==""){
            layer_tip(news_title,'请输入消息标题！');
            news_title.focus();
            return false;
        }
        var uploaded_url = $("#uploaded_url");
        var uploaded_url_val = $.trim(uploaded_url.val());
        if(uploaded_url_val==""){
        	layer_tip($("#picurl"),'请选择封面图片！');
            return false;
        }
        var news_type = $(".news_style:checked").val();
        if(news_type=="0"){
            var news_link = $("#news_link");
            var news_link_val = $.trim(news_link.val());
            if(news_link_val==""){
                layer_tip(news_link,'请输入消息的链接！');
                news_link.focus();
                return false;
            }
            if(!my_regexp['url'].test(news_link_val)){
            	layer_tip(news_link,'消息的链接格式不正确！');
            	news_link.focus();
                return false;
            }
        }else{
            var news_content = ue.getContent();
            if(news_content==""){
                layer_tip($("#news_content"),'请输入消息的内容！');
                return false;
            }
        }
        //展示修改后的结果
        $("#list<?php echo $news_id;?>",parent.document).attr("data-ok","1");
        <?php if($is_top==1){?>
        $(".top_news_img",parent.document).empty().append('<img src="'+uploaded_url_val+'"/>');
        $(".top_news_title span",parent.document).text(news_title_val);
        <?php }else{?>
        $("#list<?php echo $news_id;?> .sub_news_img",parent.document).empty().append('<img src="'+uploaded_url_val+'"/>');
        $("#list<?php echo $news_id;?> .sub_news_title",parent.document).text(news_title_val);
        <?php }?>
        return false;
    }
</script>
</head>
<body>
<div class="content_main">
	<div class="content_padding">
		<form action="" onsubmit="return modify_show()" enctype="multipart/form-data">
			<p>标题</p>
			<p><input type="text" id="news_title" name="news_title" class="material_text"/></p>
			<p>
				封面
				<?php if($is_top==1){?>
				<font class="tip_font" style="margin-left:10px;">(大图片建议尺寸：400像素 * 220像素)</font>
				<?php }else{?>
				<font class="tip_font" style="margin-left:10px;">(小图片建议尺寸：100像素 * 100像素)</font>
				<?php }?>
			</p>
			<div class="news_content_div">
				<input id="picurl" name="picurl" onchange="ajaxFileUpload()" type="file">
				<input id="uploaded_url" name="uploaded_url" value="" type="hidden">
				<p id="uploaded_p">
					<?php if($is_top==1){?>
					<img id="uploaded_pic" alt="上传之后的图片" src="" width="300px" height="165px">
					<?php }else{?>
					<img id="uploaded_pic" alt="上传之后的图片" src="" width="100px" height="100px">
					<?php }?>
					<a href="javascript:;" onclick="delete_uploaded_pic()">删除</a>
				</p>
			</div>
			<p>
				<input checked="checked" name="news_type" value="0" type="radio" class="news_style">
				<span class="radio_span">链接</span>
				<input name="news_type" value="1" type="radio" class="news_style">
				<span class="radio_span">正文</span>
			</p>
			<div class="news_content_div" id="news_url">
				<input type="text" id="news_link" name="news_link" class="material_text"/>
			</div>
			<div class="news_content_div" id="news_content" style="width:800px;height:200px;display:none;"></div>
			<p><input type="submit" value="确定" id="submit_button" class="submit_button"/></p>
		</form>
	</div>
</div>
<!-- 引入百度编辑器 -->
<script type="text/javascript" charset="utf-8" src="../ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="../ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- 引入百度编辑器 -->
<script type="text/javascript">
	var ue = UE.getEditor('news_content',{
		'toolbars':[[
		             'source', '|', 'undo', 'redo', '|',
		             'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat', 'formatmatch', 'autotypeset', 'pasteplain', '|', 'forecolor', 'backcolor', 'selectall', 'cleardoc', '|',
		             'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
		             'fontfamily', 'fontsize', '|',
		             'indent', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
		             'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
		             'insertimage', 'emotion', 'background', '|',
		             'date', 'time', 'spechars', '|',
		             'preview', 'searchreplace', 'help', 'drafts'
		         ]]
	});
</script>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
</body>
</html>