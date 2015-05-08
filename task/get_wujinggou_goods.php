<?php
/**
 * 每天定时获取海淘商品列表
 * @author zjq
 * @from 无境购
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

$class_map = array(
	'1'	=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-350/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-350/page/3/'
			),
	'2'	=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-499/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-499/page/3/'
			),
	'3'	=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-502/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-502/page/3/'
			),
	'4'	=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-487/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-487/page/3/'
			),
	'5'	=> array(
				''
			),
	'6'	=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-496/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-496/page/3/'
			),
	'7'	=> array(
				''
			),
	'8'	=> array(
				''
			),
	'9'	=> array(
				''
			),
	'10'=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-494/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-494/page/3/'
			),
	'11'=> array(
				''
			),
	'12'=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-498/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-498/page/3/'
			),
	'13'=> array(
				'https://www.paypal-wujinggou.com/cuxiaotuijian-518/page/2/',
				'https://www.paypal-wujinggou.com/cuxiaotuijian-518/page/3/'
			)
);
$site_tag = 'wujinggou';
$site_domain = 'https://www.paypal-wujinggou.com';
foreach ($class_map as $class_id=>$url_arr){
	$goods_arr = Array();
	foreach ($url_arr as $site_url){
		$list_result = curl_get_contents($site_url,10);
		$list_doc = phpQuery::newDocument($list_result);
		foreach($list_doc['#boxes .box'] as $goods_list){
			//商品已经过期
			$expires_obj = pq($goods_list)->find('.expires');
			if($expires_obj->length()==1){
				continue;
			}
			//商品获取不到价格
			$price_obj = pq($goods_list)->find('.con_title1 span font');
			if($price_obj->length()==0){
				continue;
			}
			$price_html = $price_obj->html();
			$price_html = preg_replace('/,/', '', $price_html);//有些价格用,分隔
			if(preg_match('/约￥(\d+(:?\.\d+)?)/', $price_html, $price_arr)){
				$goods_price = floatval(sprintf("%.2f", $price_arr[1]));
			}else if(preg_match('/\$(\d+(:?\.\d+)?)/', $price_html, $price_arr)){
				$goods_price = floatval(sprintf("%.2f", $exchange_rate*$price_arr[1]));
			}else{
				continue;
			}
			$goods_url = pq($goods_list)->find('.pro_img1 a')->attr('href');
			$goods_url = get_true_url($goods_url);
			$goods_img = pq($goods_list)->find('.pro_img1 img')->attr('src');
			$goods_name = pq($goods_list)->find('.con_title1 .conName a')->html();
			$goods_name = trim(preg_replace('/<[^>]+>[^<]+<\/[^>]+>/', '', $goods_name));
			$site_name = pq($goods_list)->find('.con_title1 .extra-info b:first')->text();
			$goods_count = count($goods_arr);
			$goods_arr[] = array(
				'goods_name'=>$goods_name,
				'goods_url'=>$goods_url,
				'goods_price'=>$goods_price,
				'goods_img'=>$goods_img,
				'site_name'=>$site_name,
				'favorite_count'=>0,
				'eval_count'=>0,
				'site_tag'=>$site_tag,
				'goods_index'=>$goods_count
			);
			if($goods_count>=12){
				break;
			}
		}
	}
	$class_goods_arr[$class_id] = $goods_arr;
}

//缓存优惠商品列表
$haitao_goods = get_cache('haitao_goods');
$haitao_goods[$site_tag] = $class_goods_arr;
set_cache('haitao_goods', $haitao_goods, 3600*24);

//获取商品真实地址
function get_true_url($goods_url){
	global $site_domain;
	if(substr($goods_url, 0, 1)=='/'){
		return $site_domain.$goods_url;
	}else{
		//通过商品详情页面抓取直达链接
		$info_result = curl_get_contents($goods_url,10);
		if($info_result){
			$info_doc = phpQuery::newDocument($info_result);
			$goods_url = $site_domain.$info_doc->find('.zhida a')->attr('href');
		}
		return $goods_url;
	}
}
?>