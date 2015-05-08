<?php 
if(!is_super()){
	admin_tip(0, '非法操作！');
}
$page = new Paging();

//获取管理员列表
$admin_sql = "select admin.admin_id,admin.admin_name,admin.email,admin.add_time,admin.group_id,gp.group_name from admins as admin 
join admin_group as gp on admin.group_id=gp.group_id";
$admin_arr = $page->get_record($admin_sql);
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
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>帐号管理</span>
		<span>&gt;&gt;</span>
		<span>管理员管理</span>
	</div>
	<div class="data_content">
		<div class="data_add">
			<a href="modules.php?menu_tag=admin_add">添加管理员</a>
		</div>
		<table class="data_table">
			<tr>
				<th width="15%">ID</th>
				<th width="20%">管理员名称</th>
				<th width="20%">组名称</th>
				<th width="25%">添加时间</th>
				<th width="20%">操作</th>
			</tr>
			<?php if(empty($admin_arr)){?>
			<tr><td colspan="5"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($admin_arr as $val){?>
			<tr>
				<td><?php echo $val['admin_id'];?></td>
				<td><?php echo $val['admin_name'];?></td>
				<td><?php echo $val['group_name'];?></td>
				<td><?php echo $val['add_time'];?></td>
				<td>
					<a href="modules.php?menu_tag=admin_edit&admin_id=<?php echo $val['admin_id'];?>">修改</a>
					<?php if($val['group_id']>1){?>
					<a href="do.php?act=admin_del&admin_id=<?php echo $val['admin_id'];?>" onclick="return href_confirm($(this),'是否要删除这个管理员？');">删除</a>
					<?php }?>
				</td>
			</tr>
			<?php }?>
			<?php }?>
		</table>
		<?php if(!empty($admin_arr)){?>
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