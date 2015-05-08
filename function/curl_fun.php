<?php 
//用php curl获取远程网页内容
function curl_get_contents($url,$time_limit=5){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); //从证书中检查SSL加密算法是否存在
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT,$time_limit);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		return false;
		/*
		echo curl_error($ch);exit();
		*/
	}
	curl_close($ch);
	return $result;
}

//通过curl post数据到远程网页
function curl_post_contents($url,$data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		return false;
		/*
		echo curl_error($ch);exit();
		*/
	}
	curl_close($ch);
	return $result;
}
?>