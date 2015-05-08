<?php 
//引入配置文件和相关函数
require_once ('includes.php');

//获取图片url
$img_url = get_string('img_url');
//从远程下载图片
if($img_url){
	$img_content = curl_get_contents($img_url);
	echo $img_content;
}
?>