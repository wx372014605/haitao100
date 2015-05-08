<?php 
//初始化分页类
$page = new Paging();
$page->record_num = 5;//每页最多显示5条记录

//获取图片消息列表
$image_sql = "select * from weixin_material where material_type='image' and is_delete=0 order by material_id desc";
$image_arr = $page->get_record($image_sql);
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
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
<script>
	if(window.parent!=window){
		//获取当前窗体索引
		var index = parent.layer.getFrameIndex(window.name);
	}else{
		var index = -1;
	}

	//确定的处理
	function window_ok(){
		if(material_id==0){
			layer_alert('请选择您要发送的图片消息！');
			return false;
		}
		if(index>=0){
			$("#material_id",parent.document).val(material_id);
			parent.msg_type = 'image';
			$("#msg_type",parent.document).val('image');
			$(".weixin_image_list").each(function(){
				if(material_id==($(this).attr('material_id'))){
					var image_html = $(this).prop('outerHTML');
					var image_ob = $(image_html).removeClass("image_hover").addClass("image_border");
					image_ob.find(".image_check").remove();
					$("#msg_content_box",parent.document).empty().append(image_ob.prop('outerHTML')).show();
					$(".msg_class_ul li",parent.document).removeClass('selected_li');
					$("#image_message",parent.document).addClass('selected_li');
					$("#msg_text_main",parent.document).hide();
					return false;
				}
			});
			parent.layer.close(index); //执行关闭
		}
	}
	
	//取消的处理
	function window_cancle(){
		if(index>=0){
			parent.layer.close(index); //执行关闭
		}
	}

	var material_id = 0;//初始化素材id为0
	$(function(){
		//选择图片消息素材
		$(".weixin_image_list").click(function(){
			material_id = $(this).attr('material_id');
			$(".weixin_image_list").removeClass('check_list');
			$(this).addClass('check_list');
		});
	});
</script>
</head>
<body>
<div class="content_main">
	<div class="weixin_image_top">
		<a class="weixin_image_add" href="modules.php?menu_tag=weixin_material_manage&material_type=image" target="_parent"><i></i>新建图片消息</a>
	</div>
	<div class="weixin_image_main">
		<?php foreach ($image_arr as $key=>$val){?>
			<?php $image_info = $weixin_handle->data_to_image($val['material_data']);?>
			<?php if($image_info){?>
			<div class="weixin_image_list image_hover" material_id="<?php echo $val['material_id'];?>">
				<div class="image_check">
					<img class="checked_img" src="images/selected2.png"/>
				</div>
				<div class="image_cover">
					<img class="image_pic" src="<?php echo $image_info->image_url?$image_info->image_url:'images/cd_cover.jpg'?>" onerror="img_error(this)"/>
				</div>
				<div class="image_time"><?php echo $val['add_time'];?></div>
			</div>
			<?php }?>
		<?php }?>
	</div>
	<div class="weixin_image_page"><?php echo $page->get_page();?></div>
</div>
<div class="bottom_height"></div>
<div class="bottom_bar">
	<a class="bottom_button" id="button_ok" href="javascript:;" onclick="window_ok()">确定</a>
	<a class="bottom_button" id="button_cancle" href="javascript:;" onclick="window_cancle()">取消</a>
</div>
</body>
</html>