<?php
//引入配置文件和相关函数
require_once ('../includes.php');

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';

class NativeNotifyCallBack extends WxPayNotify
{
	public function unifiedorder($openId, $product_id)
	{
		/*
		 * 从数据库读取商品并生成订单
		 */
		//统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url(WEB_URL."wxpay/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetOpenid($openId);
		$input->SetProduct_id($product_id);
		$result = WxPayApi::unifiedOrder($input);
		write_log("unifiedorder:".json_encode($result),'wxpay.log');
		return $result;
	}
	
	public function NotifyProcess($data, &$msg)
	{
		//echo "处理回调";
		write_log("call back:".json_encode($data),'wxpay.log');
		
		if(!array_key_exists("openid", $data) ||
			!array_key_exists("product_id", $data))
		{
			$msg = "回调数据异常";
			return false;
		}
		 
		$openid = $data["openid"];
		$product_id = $data["product_id"];
		
		//统一下单
		$result = $this->unifiedorder($openid, $product_id);
		if(!array_key_exists("appid", $result) ||
			 !array_key_exists("mch_id", $result) ||
			 !array_key_exists("prepay_id", $result))
		{
		 	$msg = "统一下单失败";
		 	return false;
		 }
		
		$this->SetData("appid", $result["appid"]);
		$this->SetData("mch_id", $result["mch_id"]);
		$this->SetData("nonce_str", WxPayApi::getNonceStr());
		$this->SetData("prepay_id", $result["prepay_id"]);
		$this->SetData("result_code", "SUCCESS");
		$this->SetData("err_code_des", "OK");
		return true;
	}
}

$notify = new NativeNotifyCallBack();
$notify->Handle(true);