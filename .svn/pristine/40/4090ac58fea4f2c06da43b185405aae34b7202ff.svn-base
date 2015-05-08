<?php 
//初始化分页类
$page = new Paging();

//获取用户提交的网站列表
$site_sql = "select * from plugin_site_count order by submit_count desc";
$site_arr = $page->get_record($site_sql);
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
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>交易管理</span>
		<span>&gt;&gt;</span>
		<span>网站统计</span>
	</div>
	<div class="data_content">
		<table class="data_table">
			<tr>
				<th width="15%">网站id</th>
				<th width="30%">网站链接</th>
				<th width="20%">提交次数</th>
				<th width="20%">状态</th>
				<th width="15%">操作</th>
			</tr>
			<?php if(empty($site_arr)){?>
			<tr><td colspan="5"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($site_arr as $val){?>
			<tr>
				<td><?php echo $val['site_id'];?></td>
				<td><?php echo $val['site_domain'];?></td>
				<td><?php echo $val['submit_count'];?></td>
				<td>
					<?php if($val['is_added']==0){?>
					<font style="color:red;">未添加</font>
					<?php }else{?>
					<font style="color:green;">已添加</font>
					<?php }?>
				</td>
				<td>
					<a href="do.php?act=site_mark&site_id=<?php echo $val['site_id'];?>">标记为已添加</a>
				</td>
			</tr>
			<?php }?>
			<?php }?>
		</table>
		<?php if(!empty($site_arr)){?>
			<?php echo $page->get_page();?>
		<?php }?>
	</div>
</div>
<!-- layer弹出窗口 -->
<script type="text/javascript" src="../layer/layer.min.js"></script>
<script type="text/javascript" src="../js/layer_window.js"></script>
<!-- layer弹出窗口 -->
</body>
</html>