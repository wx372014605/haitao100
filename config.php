<?php
//设置编码
header("content-type:text/html;charset=utf-8");

//设置时区
date_default_timezone_set("PRC");

//百度地图api密钥
define("BAIDU_AK",'A15c7b22246c84000a92a7a3824f622a');

//爱查快递api密钥(2000次/天)
define("ACKD_KEY", '102128');
define("ACKD_SECRET", '1af7070526b35f575aac482feb1be562');

//站点配置
define("WEB_ROOT",str_replace("\\","/",dirname(__FILE__))."/");

//如果不在命令行运行，定义web访问根目录
if(PHP_SAPI!='cli'){
	$HTTP_HOST = $_SERVER['HTTP_HOST'];
	define("WEB_URL","http://".$HTTP_HOST."/haitao100/");
}else{
	define("WEB_URL","http://localhost/haitao100/");
}

//mysql配置
define("MYSQL_HOST",'localhost');
define("MYSQL_USER",'root');
define("MYSQL_PASS",'123456');
define("MYSQL_DB",'haitao100');
define("MYSQL_PORT",'3306');

//session设置
define("SESSION_PREFIX", "HF_");//设置session前缀

//网站标题
define("WEB_TITLE","海淘100 - 海淘购物助手");
//网站关键字
define("WEB_KEYWORDS","海淘100,海淘,海淘插件,海淘助手,购物助手,海淘比价,海淘网站,亚马逊,美亚,日亚,德亚,价格曲线,降价提醒");
//网站描述
define("WEB_DESCRIPTION","海淘100是国内领先的海淘购物助手软件，基于多个浏览器平台，能够帮助用户在美国，日本等海淘网站上，自动计算人民币到手价，展示海淘商品的价格曲线，自动关注热门商品并降价提醒，自动进行包裹跟踪，支持淘宝比价。是国内知名度最高，用户数量最多，用户口碑最好的海淘插件工具，帮助广大淘友简化海淘流程。");
?>