<?php 
//初始化分页类
$page = new Paging();

//获取图片素材列表
$image_sql = "select * from weixin_material where material_type='image' and is_delete=0 order by material_id desc";
$image_arr = $page->get_record($image_sql);
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
<script type="text/javascript" src="../js/common_fun.js"></script>
<!-- 引入ajax无刷新上传文件插件 -->
<script type="text/javascript" src="../js/ajaxfileupload.js"></script>
<!-- 引入ajax无刷新上传文件插件 -->
<script type="text/javascript">
	//异步上传文件
	function ajaxFileUpload(){
		$.ajaxFileUpload({
			url:'do.php?act=weixin_image_upload',
			secureuri:false,
			fileElementId:'image_file',
			dataType: 'json',
			timeout:3000,
			cache:false,
			async:false,
			success: function (result_obj){
				$("#image_file").clearFileInput();
				if(result_obj.status==1){
					var attach = result_obj.attach;
					var material_id = attach.material_id;
					var uploaded_url = attach.uploaded_url;
					//图片列表html
					var image_list = '<div class="image_list">';
					image_list += '		<div class="image_pic">';
					image_list += '			<a href="'+uploaded_url+'" target="_blank">';
					image_list += '				<img src="'+uploaded_url+'" onerror="img_error(this)"/>';
					image_list += '			</a>';
					image_list += '		</div>';
					image_list += '		<div class="image_operate">';
					image_list += '			<a id="image_download" title="下载" href="'+uploaded_url+'" target="_blank"></a>';
					image_list += '			<a id="image_delete" title="删除" href="do.php?act=weixin_image_del&material_id='+material_id+'" onclick="return confirm(\'确定删除此素材？\');"></a>';
					image_list += '		</div>';
					image_list += '	</div>';
					$(".image_main").prepend(image_list).find(".no_data").remove();
				}else{
					layer_tip($("#image_file"),result_obj.message);
				}
			}
		})
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
</script>
</head>
<body>
<div class="content_main">
	<div class="data_content">
		<div class="image_upload">
			<a id="image_upload" href="javascript:;">
				<font>上传</font>
				<input type="file" id="image_file" name="image_file" onchange="ajaxFileUpload()"/>
			</a>
			<span class="image_upload_tip">图片只能上传jpg格式，大小不能超过1M</span>
		</div>
		<div class="image_main">
			<?php if(empty($image_arr)){?>
			<div class="no_data">暂无数据！</div>
			<?php }else{?>
				<?php foreach ($image_arr as $val){?>
				<?php $image_info = $weixin_handle->data_to_image($val['material_data']);?>
					<?php if($image_info){?>
					<div class="image_list">
						<div class="image_pic">
							<a href="<?php echo $image_info->image_url;?>" target="_blank">
								<img src="<?php echo $image_info->image_url;?>" onerror="img_error(this)"/>
							</a>
						</div>
						<div class="image_operate">
							<a class="image_download" title="下载" href="<?php echo $image_info->image_url;?>" target="_blank"></a>
							<a class="image_delete" title="删除" href="do.php?act=weixin_image_del&material_id=<?php echo $val['material_id'];?>" onclick="return confirm('确定删除此素材？');"></a>
						</div>
					</div>
					<?php }?>
				<?php }?>
			<?php }?>
		</div>
		<?php echo $page->get_page();?>
	</div>
</div>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
</body>
</html>