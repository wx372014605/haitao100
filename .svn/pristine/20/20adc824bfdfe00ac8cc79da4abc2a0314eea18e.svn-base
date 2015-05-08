<?php
//英语翻译成中文
function en_to_zh($str){
	$fanyi_url = 'http://fanyi.baidu.com/v2transapi';
	$post_data = array(
		'from'	=>	'en',
		'to'	=>	'zh',
		'query'	=>	$str,
		'transtype'	=>	'trans'
	);
	$fanyi_result = curl_post_contents($fanyi_url, $post_data);
	$fanyi_obj = json_decode($fanyi_result);
	$trans_result = $fanyi_obj->trans_result;
	$status = $trans_result->status;
	if($status==0){
		$data = $trans_result->data;
		return $data[0]->dst;
	}else{
		return '';
	}
}
?>