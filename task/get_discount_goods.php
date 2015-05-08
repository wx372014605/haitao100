<?php
/**
 * 每天定时获取优惠商品列表
 * @author zjq
 * @from 什么值得买
 * v1.0
 */
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');
//引入phpQuery
require_once ($webRoot.'class/phpQuery.php');

//获取美元汇率
$exchange_rate = get_exchange_rate('USD', 'CNY');
if(!$exchange_rate){
	return;
}

$site_url = 'http://haitao.smzdm.com/';//从什么值得买抓取优惠商品列表
$list_result = curl_get_contents($site_url,10);

$list_doc = phpQuery::newDocument($list_result);
$discount_goods_arr = Array();
foreach($list_doc['.leftWrap .list_preferential'] as $goods_list){
	$price_html = pq($goods_list)->find('.red')->html();
	if(preg_match('/约￥(\d+(:?\.\d+)?)/', $price_html, $price_arr)){
		$goods_price = floatval(sprintf("%.2f", $price_arr[1]));
	}else if(preg_match('/\$(\d+(:?\.\d+)?)/', $price_html, $price_arr)){
		$goods_price = floatval(sprintf("%.2f", $exchange_rate*$price_arr[1]));
	}else{
		continue;
	}
	$goods_url = pq($goods_list)->find('.buy a')->attr('href');
	$goods_url = get_true_url($goods_url);
	$goods_img = pq($goods_list)->find('.picLeft img')->attr('src');
	$goods_name = pq($goods_list)->find('.itemName a')->html();
	$goods_name = trim(preg_replace('/<[^>]+>[^<]+<\/[^>]+>/', '', $goods_name));
	$site_name = pq($goods_list)->find('.mall')->text();
	$discount_goods_arr[] = array(
		'goods_name'=>$goods_name,
		'goods_url'=>$goods_url,
		'goods_price'=>$goods_price,
		'goods_img'=>$goods_img,
		'site_name'=>$site_name
	);
	if(count($discount_goods_arr)>=10){
		break;
	}
}

//缓存优惠商品列表
set_cache('discount_goods', $discount_goods_arr, 3600*24);

//获取商品真实地址
function get_true_url($goods_url){
	//write_log(curl_get_contents($goods_url));
	//通过js实现跳转，代码相当复杂^ ^
	return $goods_url;
}
?>