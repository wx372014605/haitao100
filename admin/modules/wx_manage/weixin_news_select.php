<?php 
//初始化分页类
$page = new Paging();
$page->record_num = 10;//每页最多显示10条记录

//获取图文消息列表
$news_sql = "select * from weixin_material where material_type='news' and is_delete=0 order by material_id desc";
$news_arr = $page->get_record($news_sql);

//将图文消息分成2列
$left_arr = array();
$right_arr = array();
foreach ($news_arr as $key=>$val){
	if($key%2==0){
		$left_arr[] = $val;
	}else{
		$right_arr[] = $val;
	}
}

//获取消息列表html
function get_news_html($data_arr){
	global $weixin_handle;
	$news_html = '';
	foreach ($data_arr as $val){
		$news_html .= '
								<div class="weixin_news_box" material_id="'.$val['material_id'].'">
		';
		$news_list = $weixin_handle->data_to_news($val['material_data']);
		if(count($news_list)==1){
			$news_html .= '
									<div class="weixin_news_padding">
										<div class="single_news_list">
											<div class="weixin_news_title">'.sub_str($news_list[0]->title, 70).'</div>
											<div class="weixin_news_date">'.$val['add_time'].'</div>
											<div class="top_news_pic">
												<img src="'.$news_list[0]->picurl.'" onerror="img_error(this)"/>
											</div>
											<div class="weixin_news_content">'.get_abstract($news_list[0]->description).'</div>
										</div>
									</div>
			';
		}else{
			foreach ($news_list as $key=>$list){
				if($key==0){
					$news_html .= '
									<div class="weixin_news_padding">
										<div class="top_news_list">
											<div class="weixin_news_date">'.$val['add_time'].'</div>
											<div class="top_news_pic">
												<img src="'.$list->picurl.'" onerror="img_error(this)"/>
											</div>
											<div class="top_news_title"><span>'.sub_str($list->title, 80).'</span></div>
										</div>
									</div>
					';
				}else{
					$news_html .= '
									<div class="weixin_news_list">
										<div class="sub_news_pic">
											<img src="'.$list->picurl.'" onerror="img_error(this)"/>
										</div>
										<div class="sub_news_caption">'.sub_str($list->title, 80).'</div>
									</div>
					';
				}
			}
		}
		$news_html .= '
									<div class="shadow_box"></div>
									<div class="weixin_news_selected"></div>
								</div>
		';
	}
	return $news_html;
}
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
			layer_alert('请选择您要发送的图文消息！');
			return false;
		}
		if(index>=0){
			$("#material_id",parent.document).val(material_id);
			parent.msg_type = 'news';
			$("#msg_type",parent.document).val('news');
			$(".weixin_news_box").each(function(){
				if(material_id==($(this).attr('material_id'))){
					var news_html = $(this).prop('outerHTML');
					var news_ob = $(news_html).css({'margin-left':0}).removeClass("selected_material");
					news_ob.find(".shadow_box").remove();
					$("#msg_content_box",parent.document).empty().append(news_ob.prop('outerHTML')).show();
					$(".msg_class_ul li",parent.document).removeClass('selected_li');
					$("#news_message",parent.document).addClass('selected_li');
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
		//选择图文消息素材
		$(".weixin_news_box").click(function(){
			material_id = $(this).attr('material_id');
			$(".weixin_news_box").removeClass("selected_material");
			$(this).addClass("selected_material");
		});
	});
</script>
</head>
<body>
<div class="content_main">
	<div class="weixin_news_top">
		<a class="weixin_news_add" href="modules.php?menu_tag=weixin_material_manage" target="_parent"><i></i>新建图文消息</a>
	</div>
	<div class="weixin_news_main">
		<div class="weixin_news_left">
			<?php echo get_news_html($left_arr);?>
		</div>
		<div class="weixin_news_right">
			<?php echo get_news_html($right_arr);?>
		</div>
	</div>
	<div class="weixin_news_page"><?php echo $page->get_page();?></div>
</div>
<div class="bottom_height"></div>
<div class="bottom_bar">
	<a class="bottom_button" id="button_ok" href="javascript:;" onclick="window_ok()">确定</a>
	<a class="bottom_button" id="button_cancle" href="javascript:;" onclick="window_cancle()">取消</a>
</div>
</body>
</html>