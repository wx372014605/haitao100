<?php
//引入配置文件和相关函数
require_once ('includes.php');
//引入新浪微博通用类
require_once ('class/sina_weibo.php');

$code = get_string('code');
if($code){
	echo '授权成功！ACCESS TOKEN：'.$sina_weibo->get_access_token($code);
}
?>