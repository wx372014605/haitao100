<?php 
//初始化分页类
$page = new Paging();

//获取招聘信息列表
$recruitment_sql = "select * from company_recruitment";
$recruitment_arr = $page->get_record($recruitment_sql);
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
		<span>公司管理</span>
		<span>&gt;&gt;</span>
		<span>招聘管理</span>
	</div>
	<div class="data_content">
		<div class="data_add">
			<a href="modules.php?menu_tag=recruitment_add">添加招聘信息</a>
		</div>
		<table class="data_table">
			<tr>
				<th width="15%">职位名称</th>
				<th width="15%">招聘人数</th>
				<th width="25%">内容摘要</th>
				<th width="15%">状态</th>
				<th width="15%">添加时间</th>
				<th width="15%">操作</th>
			</tr>
			<?php if(empty($recruitment_arr)){?>
			<tr><td colspan="6"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($recruitment_arr as $val){?>
			<tr>
				<td><?php echo $val['job_name'];?></td>
				<td><?php echo $val['recruitment_num'];?></td>
				<td><?php echo get_abstract($val['requirement_content']);?></td>
				<td>
					<?php if($val['is_available']==0){?>
					<font style="color:red;">不显示</font>
					<?php }else{?>
					<font style="color:green;">显示</font>
					<?php }?>
				</td>
				<td><?php echo $val['add_time'];?></td>
				<td>
					<a href="modules.php?menu_tag=recruitment_edit&recruitment_id=<?php echo $val['recruitment_id'];?>">修改</a>
					<a href="do.php?act=recruitment_del&recruitment_id=<?php echo $val['recruitment_id'];?>" onclick="return href_confirm($(this),'是否要删除这个职位？');">删除</a>
				</td>
			</tr>
			<?php }?>
			<?php }?>
		</table>
		<?php if(!empty($recruitment_arr)){?>
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