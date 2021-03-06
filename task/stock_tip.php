<?php
/**
 * 每天定时抓取页面商品缺货信息并发消息提醒用户
 * @author zjq
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

//获取有缺货提醒的商品列表
$goods_sql = "select goods_id,site_id,user_id,goods_name,goods_url from favorite_goods where stock_tip=1";
$goods_arr = $mysql_handle->getRs($goods_sql);
foreach ($goods_arr as $val){
	$goods_id = $val['goods_id'];
	$user_id = $val['user_id'];
	$goods_url = $val['goods_url'];
	$goods_name = $val['goods_name'];
	$site_id = $val['site_id'];
	if(!out_of_stock($site_id,$goods_url)){
		//发送消息提醒
		$msg_title = '您关注的海淘商品【'.sub_str($goods_name,40).'】已经到货啦，赶快去看看吧！';
		$msg_content = '';
		$msg_link = $goods_url;
		$send_result = send_system_message($user_id,$msg_title,$msg_content,$msg_link);
		
		//更新提醒发送状态
		if($send_result){
			$stock_sql = "update favorite_goods set stock_tip=0 where goods_id=$goods_id limit 1";
			$result = $mysql_handle->query($stock_sql);
		}
	}
}

//判断该商品是否缺货
function out_of_stock($site_id,$goods_url){
	$result = curl_get_contents($goods_url,60);
	if(!$result){
		return false;
	}
	$result_doc = phpQuery::newDocument($result);
	switch ($site_id){
		case 1://amazon
			/*
			* 根据包含in stock的元素是否存在
			*/
			$aloha_availability = $result_doc['#availability-container .aloha-availability-green'];
			if($aloha_availability->length()>0){
				return false;
			}
			
			$availability = $result_doc['#availability .a-color-success'];
			if($availability->length()>0){
				return false;
			}
			
			/*
			* 根据加入购物车按钮是否可用
			*/
			$add_to_cart_button = $result_doc['#add-to-cart-button:visible'];
			if($add_to_cart_button->length()>0){
				return false;
			}
			$bb_to_cfg_button = $result_doc['#bb_to_cfg_button:visible'];
			if($bb_to_cfg_button->length()>0){
				return false;
			}
			$buy_box_submit = $result_doc['#buy-box-submit:visible'];
			if($buy_box_submit->length()>0){
				return false;
			}
			
			/*
			* 返回true
			*/
			return true;
		case 5://eaby
			/*
			* 根据立即购买按钮是否存在
			*/
			$binBtn_btn = $result_doc['#binBtn_btn'];
			if($binBtn_btn->length()>0){
				return false;
			}else{
				return true;
			}
	}
}
?>