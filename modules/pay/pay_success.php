<?php 
//获取订单号
$shopping_num = get_string('shopping_num');
if($shopping_num==''){
	die('获取订单号失败！');
}

//获取订单详细信息
$order_sql = "select od.trade_num,od.status_id,od.goods_url,od.goods_name,od.receive_name,od.goods_num,od.receive_mobile,
od.receive_address,od.pay_time,od.order_remarks,site.site_name,site.site_url,pro.area_name as pro_name,city.area_name as city_name,
dis.area_name as dis_name,pm.payment_name from transport_order as od join plugin_site as site on od.site_id=site.site_id join areas as pro 
on od.receive_province=pro.area_id join areas as city on od.receive_city=city.area_id join areas as dis
on od.receive_district=dis.area_id join payment as pm on od.payment_id=pm.payment_id where od.shopping_num='$shopping_num' limit 1";
$order_arr = $mysql_handle->getRow($order_sql);
if($order_arr and $order_arr['status_id']!=3){
	die('非法操作！');
}
?>
<?php require 'modules/index_header.php';?>
<div id="content">
	<div class="content_c">
		<div class="pay_result_main">
			<div id="pay_success" class="pay_result">
				<img src="images/pay_success.png"/>
				<span>恭喜您付款成功！</span>
			</div>
			<?php if($order_arr){?>
			<table id="pay_info_table">
				<tr>
					<td width="12%">订单号：</td>
					<td width="38%"><?php echo $shopping_num;?></td>
					<td width="12%">交易号：</td>
					<td width="38%"><?php echo $order_arr['trade_num'];?></td>
				</tr>
				<tr>
					<td>商品名称：</td>
					<td><a href="<?php echo $order_arr['goods_url'];?>" target="_blank"><?php echo $order_arr['goods_name'];?></a></td>
					<td>购买数量：</td>
					<td><?php echo $order_arr['goods_num'];?></td>
				</tr>
				<tr>
					<td>支付金额：</td>
					<td>￥0.01</td>
					<td>付款时间：</td>
					<td><?php echo $order_arr['pay_time'];?></td>
				</tr>
				<tr>
					<td>支付方式：</td>
					<td><?php echo $order_arr['payment_name'];?></td>
					<td>来源网站：</td>
					<td><a href="<?php echo $order_arr['site_url'];?>" target="_blank"><?php echo $order_arr['site_name'];?></a></td>
				</tr>
				<tr>
					<td>收货地址：</td>
					<td colspan="3"><?php echo $order_arr['pro_name'].'省'.$order_arr['city_name'].'市'.$order_arr['dis_name'].$order_arr['receive_address'].'【'.$order_arr['receive_name'].'】收 '.$order_arr['receive_mobile'];?></td>
				</tr>
				<?php if($order_arr['order_remarks']){?>
				<tr>
					<td>订单备注：</td>
					<td colspan="3"><?php echo $order_arr['order_remarks'];?></td>
				</tr>
				<?php }?>
			</table>
			<?php }?>
		</div>
	</div>
</div>

<!-- 引入网站通用底部文件 -->
<?php require 'modules/index_footer.php';?>