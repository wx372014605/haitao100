<?php
/**
 * 每天定时获取海淘商品列表
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

$class_map = array(
	'1'	=> array(
				'http://haitao.smzdm.com/fenlei/diannaoshuma'
			),
	'2'	=> array(
				'http://haitao.smzdm.com/fenlei/fushixiebao'
			),
	'3'	=> array(
				'http://haitao.smzdm.com/fenlei/gehuhuazhuang'
			),
	'4'	=> array(
				'http://haitao.smzdm.com/fenlei/muyingyongpin'
			),
	'5'	=> array(
				'http://haitao.smzdm.com/fenlei/riyongbaihuo'
			),
	'6'	=> array(
				'http://haitao.smzdm.com/fenlei/shipinbaojian'
			),
	'7'	=> array(
				'http://haitao.smzdm.com/fenlei/tushuyinxiang'
			),
	'8'	=> array(
				'http://haitao.smzdm.com/fenlei/bangongshebei'
			),
	'9'	=> array(
				'http://haitao.smzdm.com/fenlei/jiajujiazhuang'
			),
	'10'=> array(
				'http://haitao.smzdm.com/fenlei/yundonghuwai'
			),
	'11'=> array(
				'http://haitao.smzdm.com/fenlei/qicheyongpin'
			),
	'12'=> array(
				'http://haitao.smzdm.com/fenlei/lipinzhongbiao'
			),
	'13'=> array(
				'http://haitao.smzdm.com/fenlei/qitafenlei'
			)
);
$site_tag = 'smzdm';
$site_domain = 'http://haitao.smzdm.com';
foreach ($class_map as $class_id=>$url_arr){
	$goods_arr = Array();
	foreach ($url_arr as $site_url){
		$list_result = curl_get_contents($site_url,10);
		$list_doc = phpQuery::newDocument($list_result);
		foreach($list_doc['.leftWrap .list_preferential'] as $goods_list){
			$price_html = pq($goods_list)->find('.red')->html();
			if(preg_match('/约￥(\d+(:?\.\d+)?)/', $price_html, $price_arr)){
				$goods_price = floatval(sprintf("%.2f", $price_arr[1]));
			}else if(preg_match('/(\d+(:?\.\d+)?)元/', $price_html, $price_arr)){
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
			$info_url = pq($goods_list)->find('.itemName a')->attr('href');
			$goods_count = count($goods_arr);
			$goods_arr[] = array(
				'goods_name'=>$goods_name,
				'goods_url'=>$goods_url,
				'goods_price'=>$goods_price,
				'goods_img'=>$goods_img,
				'site_name'=>$site_name,
				'info_url'=>$info_url,
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
	//write_log(curl_get_contents($goods_url));
	//通过js实现跳转，代码相当复杂^ ^
	return $goods_url;
}
?>