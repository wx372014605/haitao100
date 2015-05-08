<?php 
//初始化分页类
$page = new Paging();

//获取公众账号平台id
$platform_id = get_string('platform_id');

//获取微信昵称
$nickname = get_string('nickname');

//获取性别
$sex = get_number('sex');

//获取省份
$province_id = get_number('province_id');

//获取城市
$city_id = get_number('city_id');

//获取公众帐号列表
$account_sql = "select platform_id,account_name from weixin_account";
$account_arr = $mysql_handle->getRs($account_sql);

//获取用户列表
$user_sql = "select uf.*,account.account_name from weixin_user_info as uf left join weixin_account as account on uf.platform_id=account.platform_id where 1";
if($platform_id!=''){
	$user_sql .= " and uf.platform_id='$platform_id'";
}
if($nickname!=''){
	$user_sql .= " and uf.nickname like '%$nickname%'";
}
if($sex>0){
	$user_sql .= " and uf.sex=$sex";
}
if($city_id>0){//优先判断城市
	$city_sql = "select area_name from areas where area_id=$city_id limit 1";
	$city_arr = $mysql_handle->getRow($city_sql);
	if($city_arr){
		$city_name = str_replace('市', '', $city_arr['area_name']);
		$user_sql .= " and uf.city='$city_name'";
	}
}else if($province_id>0){
	$province_sql = "select area_name from areas where area_id=$province_id limit 1";
	$province_arr = $mysql_handle->getRow($province_sql);
	if($province_arr){
		$province_name = str_replace('省', '', $province_arr['area_name']);
		$user_sql .= " and uf.province='$province_name'";
	}
}
$user_arr = $page->get_record($user_sql);

//获取url参数
$url_query = get_url_query('menu_tag');
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
<style type="text/css">
	.user_ico{display:inline-block;*display:inline;position:relative;z-index:0;}
	.user_ico .small_img{vertical-align:top;}
	.user_ico .big_img{display:none;border:2px solid #F4F5F9;position:absolute;left:60px;top:60px;z-index:999;}
</style>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/area.js"></script>
<script type="text/javascript">
	$(function(){
		//省市地区选择
		var area_box = load_area_widget('area_box',
			{
				'province_id':<?php echo $province_id;?>,
				'city_id':<?php echo $city_id;?>,
				'show_district':false
			}
		);
		area_box.find('span').addClass('search_tip');

		//绑定显示大头像事件
		$(".user_ico").hover(
			function(){
				$(this).css({'z-index':1});
				var small_img = $(this).find('.small_img');
				var big_img = $(this).find('.big_img');
				var this_height = big_img.outerHeight();
				var document_top = $(document).scrollTop()+$(window).height();
				var absolute_top = small_img.offset().top+60;
				if(absolute_top+this_height>document_top){
					big_img.css({'top':(20-this_height)+'px'});
				}else{
					big_img.css({'top':'60px'});
				}
				big_img.show();
			},
			function(){
				$(this).css({'z-index':0});
				$(this).find('.big_img').hide();
			}
		);
	});
</script>
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
			<input type="hidden" name="menu_tag" value="weixin_user_info"/>
			<span class="search_tip">公众帐号：</span>
			<select id="platform_id" name="platform_id">
				<option value="">全部帐号</option>
				<?php foreach ($account_arr as $val){?>
				<option value="<?php echo $val['platform_id'];?>" <?php if($platform_id==$val['platform_id'])echo 'selected';?>><?php echo sub_str($val['account_name'],20);?></option>
				<?php }?>
			</select>
			<span class="search_tip">微信昵称：</span>
			<input type="text" id="nickname" name="nickname" value="<?php echo $nickname;?>" class="search_text"/>
			<span class="search_tip">性别：</span>
			<select id="sex" name="sex">
				<option value="0">全部</option>
				<option value="1" <?php if($sex==1)echo 'selected';?>>男</option>
				<option value="2" <?php if($sex==2)echo 'selected';?>>女</option>
			</select>
			<span id="area_box"></span>
			<input type="submit" value="查询" class="search_button"/>
		</form>
	</div>
	<div class="data_content">
		<div class="data_add">
			<a href="do.php?act=weixin_user_export&<?php echo $url_query;?>">导出</a>
		</div>
		<table class="data_table">
			<tr>
				<th width="15%">所属平台</th>
				<th width="15%">头像</th>
				<th width="15%">昵称</th>
				<th width="10%">性别</th>
				<th width="10%">国家</th>
				<th width="10%">省份</th>
				<th width="10%">城市</th>
				<th width="15%">关注时间</th>
			</tr>
			<?php if(empty($user_arr)){?>
			<tr><td colspan="8"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($user_arr as $val){?>
			<tr>
				<td><?php echo $val['account_name']?$val['account_name']:'未登录平台';?></td>
				<td>
					<div class="user_ico">
						<img class="small_img" width="80px" height="80px" src="<?php echo $val['headimgurl']?WEB_URL . $val['headimgurl']:'images/nopic.png';?>" onerror="this.src='images/nopic.png'"/>
						<img class="big_img" width="240px" height="240px" src="<?php echo $val['headimgurl']?WEB_URL . $val['headimgurl']:'images/nopic.png';?>" onerror="this.src='images/nopic.png'"/>
					</div>
				</td>
				<td><?php echo $val['nickname'];?></td>
				<td>
					<?php if($val['sex']==1){?>
					男
					<?php }else if($val['sex']==2){?>
					女
					<?php }else{?>
					未知
					<?php }?>
				</td>
				<td><?php echo $val['country'];?></td>
				<td><?php echo $val['province'];?></td>
				<td><?php echo $val['city'];?></td>
				<td><?php echo $val['subscribe_time'];?></td>
			</tr>
			<?php }?>
			<?php }?>
		</table>
		<?php if(!empty($user_arr)){?>
			<?php echo $page->get_page();?>
		<?php }?>
	</div>
</div>
</body>
</html>