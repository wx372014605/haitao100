<?php 
//初始化分页类
$page = new Paging();

//获取来源网站id
$site_id = get_number('site_id');

//获取订单状态id
$status_id = get_number('status_id');

//获取订单号
$shopping_num = get_string('shopping_num');

//获取用户id
$user_id = get_number('user_id');

//获取省份
$province_id = get_number('province_id');

//获取城市
$city_id = get_number('city_id');

//获取地区
$district_id = get_number('district_id');

//获取来源网站列表
$site_sql = "select site_id,site_name from plugin_site where is_available=1";
$site_arr = $mysql_handle->getRs($site_sql);

//获取订单状态列表
$status_sql = "select * from order_status";
$status_arr = $mysql_handle->getRs($status_sql);

//获取订单列表
$order_sql = "select od.order_id,od.status_id,od.shopping_num,od.package_id,od.shipping_num,od.goods_url,od.goods_img,od.goods_name,od.goods_price,od.goods_num,
od.receive_name,od.receive_mobile,od.receive_address,od.attach_service1,od.attach_service2,od.attach_service3,od.attach_service4,od.order_remarks,od.add_time,
pro.area_name as pro_name,city.area_name as city_name,dis.area_name as dis_name,os.status_name,taddress.address_name as taddress_name 
from transport_order as od join areas as pro on od.receive_province=pro.area_id join areas as city on od.receive_city=city.area_id 
join areas as dis on od.receive_district=dis.area_id join order_status as os on od.status_id=os.status_id join transport_address 
as taddress on od.taddress_id=taddress.address_id where 1";
if($site_id>0){
	$order_sql .= " and od.site_id=$site_id";
}
if($status_id>0){
	$order_sql .= " and od.status_id=$status_id";
}
if($shopping_num!=''){
	$order_sql .= " and od.shopping_num='$shopping_num'";
}
if($user_id>0){
	$order_sql .= " and od.user_id=$user_id";
}
if($district_id>0){//优先判断地区
	$order_sql .= " and od.receive_district=$district_id";
}else if($city_id>0){
	$order_sql .= " and od.receive_city=$city_id";
}else if($province_id>0){
	$order_sql .= " and od.receive_province=$province_id";
}
$order_sql .= " order by od.package_id desc,od.order_id desc";
$order_arr = $page->get_record($order_sql);

//获取各状态时的操作
function get_opera($order_id,$status_id){
	$opera_html = '';
	if($status_id==1){
		$opera_html .= '<a href="do.php?act=order_storage&order_id='.$order_id.'">入库</a>';
	}
	return $opera_html;
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
<link rel="stylesheet" type="text/css" href="css/trade.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/area.js"></script>
<script type="text/javascript" src="../js/config.js"></script>
<script type="text/javascript">
	$(function(){
		//省市地区选择
		var area_box = load_area_widget('area_box',
			{
				'province_id':<?php echo $province_id;?>,
				'city_id':<?php echo $city_id;?>,
				'district_id':<?php echo $district_id;?>
			}
		);
		area_box.find('span').addClass('search_tip');
		//全选反选订单
		$('#check_all').click(function(){
			$('.order_check').attr('checked',$(this).is(':checked'));
		});
		//绑定显示商品图片事件
		$(".goods_link").hover(
			function(){
				$(this).css({'z-index':1});
				var goods_img = $(this).find('.goods_img');
				var img_height = goods_img.outerHeight();
				var document_top = $(document).scrollTop()+$(window).height();
				var absolute_top = $(this).offset().top+10;
				if(absolute_top+img_height>document_top){
					goods_img.css({'top':(20-img_height)+'px'});
				}else{
					goods_img.css({'top':'10px'});
				}
				goods_img.show();
			},
			function(){
				$(this).css({'z-index':0});
				$(this).find('.goods_img').hide();
			}
		);
		//订单入库
		$('#order_storage').click(function(){
			var order_id_arr = new Array();
			$('.order_check:checked').each(function(){
				order_id_arr.push($(this).val());
			});
			if(order_id_arr.length>0){
				var order_id = order_id_arr.join(',');
				location.href = 'do.php?act=order_storage&order_id='+order_id;
			}
		});
	});

	//表单验证
	function check_form(){
		var user_id = $('#user_id');
		var user_id_val = $.trim(user_id.val());
		if(user_id_val!='' && !my_regexp.number.test(user_id_val)){
			layer_tip(user_id,'用户id必须为数字！');
			user_id.focus();
			return false;
		}
	}
</script>
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>交易管理</span>
		<span>&gt;&gt;</span>
		<span>订单设置</span>
	</div>
	<div class="search_div">
		<form class="search_form" action="" method="get" onsubmit="return check_form()">
			<input type="hidden" name="menu_tag" value="transport_order"/>
			<span class="search_tip">订单来源：</span>
			<select id="site_id" name="site_id">
				<option value="0">全部网站</option>
				<?php foreach ($site_arr as $val){?>
				<option value="<?php echo $val['site_id'];?>" <?php if($site_id==$val['site_id'])echo 'selected';?>><?php echo sub_str($val['site_name'],20);?></option>
				<?php }?>
			</select>
			<span class="search_tip">订单状态：</span>
			<select id="status_id" name="status_id">
				<option value="0">全部</option>
				<?php foreach ($status_arr as $val){?>
				<option value="<?php echo $val['status_id'];?>" <?php if($status_id==$val['status_id'])echo 'selected';?>><?php echo $val['status_name'];?></option>
				<?php }?>
			</select>
			<span class="search_tip">订单号：</span>
			<input type="text" id="shopping_num" name="shopping_num" value="<?php echo $shopping_num;?>" class="search_text"/>
			<span class="search_tip">用户id：</span>
			<input type="text" id="user_id" name="user_id" value="<?php echo $user_id>0?$user_id:'';?>" class="search_text" style="width:80px;"/>
			<span id="area_box"></span>
			<input type="submit" value="查询" class="search_button"/>
		</form>
	</div>
	<div class="data_content" style="padding:3px;">
		<table class="data_table">
			<tr>
				<th width="4%">&nbsp;</th>
				<th width="12%">运单号</th>
				<th width="14%">商品</th>
				<th width="6%">价格</th>
				<th width="6%">数量</th>
				<th width="14%">收货信息</th>
				<th width="6%">增值服务</th>
				<th width="10%">备注</th>
				<th width="10%">转运地址</th>
				<th width="6%">下单时间</th>
				<th width="6%">订单状态</th>
				<th width="6%">操作</th>
			</tr>
			<?php if(empty($order_arr)){?>
			<tr><td colspan="12"><div class="no_data">暂无数据！</div></td></tr>
			<?php }else{?>
			<?php foreach ($order_arr as $key=>$val){?>
			<?php $pre_package_id = $key==0?'':$order_arr[$key-1]['package_id'];?>
			<?php $current_package_id = $val['package_id'];?>
			<?php $next_package_id = isset($order_arr[$key+1]['package_id'])?$order_arr[$key+1]['package_id']:'';?>
			<?php if($pre_package_id!=$current_package_id && $current_package_id!=''){?>
			<tbody class="package_tbody"><tr><td colspan="12" class="package_td">合包单号：<?php echo $current_package_id;?></td></tr>
			<?php }?>
			<tr>
				<td><input class="order_check" type="checkbox" name="order_check" value="<?php echo $val['order_id'];?>"/></td>
				<td><?php echo $val['shipping_num'];?></td>
				<td>
					<a class="goods_link" href="<?php echo $val['goods_url'];?>" title="<?php echo $val['goods_name'];?>" target="_blank">
						<?php echo $val['goods_name'];?>
						<img class="goods_img" src="<?php echo $val['goods_img'];?>"/>
					</a>
				</td>
				<td><span class="goods_price"><?php echo $val['goods_price'];?></span></td>
				<td><?php echo $val['goods_num'];?></td>
				<td><?php echo $val['pro_name'].'省'.$val['city_name'].$val['dis_name'].$val['receive_address'].'【<span class="receive_name">'.$val['receive_name'].'</span>】收　'.$val['receive_mobile'];?></td>
				<td>
					<?php if($val['attach_service1'])echo '<p>取出小票</p>';?>
					<?php if($val['attach_service2'])echo '<p>入库拍照</p>';?>
					<?php if($val['attach_service3'])echo '<p>分包</p>';?>
					<?php if($val['attach_service4'])echo '<p>合包</p>';?>
				</td>
				<td><?php echo $val['order_remarks'];?></td>
				<td><?php echo $val['taddress_name'];?></td>
				<td><?php echo $val['add_time'];?></td>
				<td><?php echo $val['status_name'];?></td>
				<td><?php echo get_opera($val['order_id'],$val['status_id']);?></td>
			</tr>
			<?php if($next_package_id!=$current_package_id && $current_package_id!=''){?>
			</tbody>
			<?php }?>
			<?php }?>
			<tr class="opera_tr">
				<td class="check_td"><input id="check_all" type="checkbox" name="check_all"/></td>
				<td colspan="11" class="button_td">
					<a id="order_storage" class="table_button" href="javascript:;">批量入库</a>
				</td>
			</tr>
			<?php }?>
		</table>
		<?php if(!empty($order_arr)){?>
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