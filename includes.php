<?php
//引入网站配置文件
require ('config.php');
//引入网站通用正则表达式
require (WEB_ROOT.'config/regexp.php');
//引入数据库操作类
require (WEB_ROOT.'class/mysql.php');
//引入memcache操作类
require (WEB_ROOT.'class/memcache.php');
//引入自定义session处理类
require (WEB_ROOT.'class/session.php');
//引入分页类
require (WEB_ROOT.'class/paging.php');
//引入微信通用类
require (WEB_ROOT.'class/weixin.php');
//引入通用函数库
require (WEB_ROOT.'function/common_fun.php');
//引入curl相关函数
require (WEB_ROOT.'function/curl_fun.php');
//引入字符串处理函数
require (WEB_ROOT.'function/string_fun.php');
//引入检测手机浏览器函数
require (WEB_ROOT.'function/check_mobile.php');
//引入检测用户登录状态函数
require (WEB_ROOT.'function/check_login.php');
//引入页面跳转函数
require (WEB_ROOT.'function/url_fun.php');
//引入导出文件函数库
require (WEB_ROOT.'function/export_fun.php');
//引入自定义网站错误处理函数
require (WEB_ROOT.'function/error_fun.php');
?>