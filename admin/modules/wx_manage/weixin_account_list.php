<?php 
//初始化分页类
$page = new Paging();

//获取搜索的帐号类型和帐号名称
$type = isset($_GET['type'])?intval($_GET['type']):'-1';
$account_name = isset($_GET['account_name'])?add_slashes($_GET['account_name']):'';

//获取公众帐号列表
$account_sql = "select * from weixin_account where 1";
if($type>=0){
	$account_sql .= " and type=$type";
}
if($account_name!=''){
	$account_sql .= " and account_name like '%$account_name%'";
}
$account_arr = $page->get_record($account_sql);
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
		<span>帐号管理</span>
		<span>&gt;&gt;</span>
		<span>帐号设置</span>
	</div>
	<div class="search_div">
		<form class="search_form" action="" method="get">
			<input type="hidden" name="menu_tag" value="weixin_account_list"/>
			<span class="search_tip">帐号类型：</span>
			<select id="type" name="type">
				<option value="-1">全部帐号</option>
				<option value="0" <?php if($type==0)echo "selected";?>>普通帐号</option>
				<option value="1" <?php if($type==1)echo "selected";?>>高级帐号</option>
			</select>
			<span class="search_tip">帐号名称：</span>
			<input type="text" id="account_name" name="account_name" value="<?php echo $account_name;?>" class="search_text"/>
			<input type="submit" value="查询" class="search_button"/>
		</form>
	</div>
	<div class="data_content">
		<div class="data_add">
			<a href="modules.php?menu_tag=weixin_account_add">添加帐号</a>
		</div>
		<table class="data_table">
			<tr>
				<th width="12%">平台id</th>
				<th width="8%">平台logo</th>
				<th width="10%">账号名称</th>
				<th width="15%">AppId</th>
				<th width="20%">AppSecret</th>
				<th width="17%">EncodingAesKey</th>
				<th width="8%">帐号类型</th>
				<th width="10%">操作</th>
			</tr>
			<?php if(empty($account_arr)){?>
			<tr><td colspan="7"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($account_arr as $val){?>
			<tr>
				<td><?php echo $val['platform_id'];?></td>
				<td><img width="80px" height="80px" src="<?php echo $val['account_logo']?$val['account_logo']:'images/nopic.png';?>" onerror="img_error(this)"/></td>
				<td><?php echo $val['account_name'];?></td>
				<td><?php echo $val['app_id'];?></td>
				<td><?php echo $val['app_secret'];?></td>
				<td><?php echo $val['encoding_aes_key'];?></td>
				<td>
					<?php if($val['type']==1){?>
					<font style="color:red;">高级帐号</font>
					<?php }else{?>
					<font style="color:green;">普通帐号</font>
					<?php }?>
				</td>
				<td>
					<a href="modules.php?menu_tag=weixin_menu_manage&account_id=<?php echo $val['account_id'];?>">自定义菜单</a><br/>
					<a href="modules.php?menu_tag=weixin_account_edit&account_id=<?php echo $val['account_id'];?>">修改</a>
					<a href="do.php?act=weixin_account_del&account_id=<?php echo $val['account_id'];?>" onclick="return href_confirm($(this),'是否要删除这个公众帐号？');">删除</a>
				</td>
			</tr>
			<?php }?>
			<?php }?>
		</table>
		<?php if(!empty($account_arr)){?>
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