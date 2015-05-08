<?php 
//引入配置文件和相关函数
require_once ('config.php');
require_once ('includes.php');

//获取成功和失败的标记
if(isset($_GET['status']) and intval($_GET['status'])==1){
	$status = 1;
}else{
	$status = 0;
}
//获取提示内容
$tip_str = isset($_GET['tip_str'])?add_slashes($_GET['tip_str']):"";
$tip_str = urldecode($tip_str);//url地址编码，防止中文乱码
//获取回调页面
$callback_url = isset($_GET['callback'])?$_GET['callback']:"index.php";
$callback_url = urldecode($callback_url);
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="5;url=<?php echo $callback_url;?>" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta http-equiv="HandheldFriendly" content="true" />
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/act_tip.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
	$().ready(function(){
		var time_ob = $(".second");
		var left_time = parseInt(time_ob.text());//跳转剩余时间(秒)
		var t;
		t = setInterval(function(){
			left_time -= 1;
			if(left_time>=0){
				time_ob.text(left_time);
			}else{
				clearInterval(t);
				location.replace("<?php echo $callback_url;?>");
			}
		},1000);
	});
</script>
</head>
<body>
<div>
	<div class="padding_main">
		<div class="top_height"></div>
		<div class="info_main">
			<div class="tip_img">
				<img src="images/<?php echo $status==1?'act_success.png':'act_fail.png';?>" width="49px" height="60px"/>
			</div>
			<div class="tip_info">
				<p><?php echo $tip_str;?></p>
				<p>系统将在<span class="second">3</span>秒内返回！</p>
				<p><a href="<?php echo $callback_url;?>" id="manual_link">如果浏览器没有自动跳转，请点击这里</a></p>
			</div>
		</div>
	</div>
</div>
</body>
</html>