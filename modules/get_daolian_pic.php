<?php 
//引入配置文件和相关函数
require_once ('includes.php');

//获取图片url
$img_url = get_string('img_url');
//从远程下载图片
if($img_url){
	$img_content = curl_get_contents($img_url);
	$header_info = get_headers($img_url,true);
	$content_type = $header_info['Content-Type'];
	if(preg_match('/^image\//', $content_type)){
		$img_handle = imagecreatefromstring($img_content);
		header('Content-Type: '.$content_type);
		switch ($content_type){
			case 'image/png':
				imagepng($img_handle);
				break;
			case 'image/gif':
				imagepng($img_handle);
				break;
			default:
				imagejpeg($img_handle);
				break;
		}
	}else{
		echo $img_content;
	}
}
?>