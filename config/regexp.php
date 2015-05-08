<?php
//定义网站通用正则表达式
$my_regexp = array(
	"platform_id"=>"/^gh_\w{12}$/",//微信公众平台platform_id
	"app_id"=>"/^wx\w{16}$/",//微信app_id
	"app_secret"=>"/^\w{32}$/",//微信app_secret
	"encoding_aes_key"=>"/^\w{43}$/",//微信消息加密解密encoding_aes_key
	"account"=>"/^[a-zA-Z][\w_-]{4,17}$/",//注册帐号
	"password"=>"/^\w{5,18}$/",//注册密码
	"mobile"=>"/^\d{11}$/",//手机号码
	"email"=>"/^\w[\w\._-]+@[\w_-]+(\.[\w_-]+)+$/",//邮箱地址
	"url"=>"/^(http|https|ftp):(\/\/|\\\\)[^\s]+/",//url地址
	"number"=>"/^\d+$/", //纯数字
);
?>