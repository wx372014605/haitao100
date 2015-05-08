<?php 
//初始化分页类
$page = new Paging();
$page->record_num = 12;//每页最多显示12条记录

//获取图文消息列表
$news_sql = "select * from weixin_material where material_type='news' and is_delete=0 order by material_id desc";
$news_arr = $page->get_record($news_sql);

//将图文消息分成3列
$left_arr = array();
$center_arr = array();
$right_arr = array();
foreach ($news_arr as $key=>$val){
	if($key%3==0){
		$left_arr[] = $val;
	}else if($key%3==1){
		$center_arr[] = $val;
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
								<div class="weixin_news_box2">
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
									<div class="weixin_news_bar">
										<a title="编辑" class="weixin_news_opera edit_opera" href="modules.php?menu_tag=weixin_news_edit&material_id='.$val['material_id'].'"></a>
										<a  title="删除" class="weixin_news_opera delete_opera" href="do.php?act=weixin_news_del&material_id='.$val['material_id'].'" onclick="return confirm(\'是否要删除这条消息？\');"></a>
									</div>
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
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/material.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
</head>
<body>
<div class="content_main">
	<div class="weixin_news_main">
		<div class="weixin_news_left">
			<div class="weixin_news_box">
				<div class="material_add_main">
					<a href="modules.php?menu_tag=weixin_news_add"></a>
				</div>
			</div>
			<?php echo get_news_html($left_arr);?>
		</div>
		<div class="weixin_news_center">
			<?php echo get_news_html($center_arr);?>
		</div>
		<div class="weixin_news_right">
			<?php echo get_news_html($right_arr);?>
		</div>
	</div>
	<div class="weixin_news_page"><?php echo $page->get_page();?></div>
</div>
</body>
</html>