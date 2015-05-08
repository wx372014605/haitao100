<?php 
//引入配置文件和相关函数
require_once ('../includes.php');

//获取成功和失败的标记
if(isset($_GET['status']) and intval($_GET['status'])==1){
	$status = 1;
}else{
	$status = 0;
}

//提示文字的颜色
if($status==0){
	$tip_color = 'red';
}else{
	$tip_color = '#009900';
}
//获取提示内容
$tip_str = isset($_GET['tip_str'])?add_slashes($_GET['tip_str']):"";
$tip_str = urldecode($tip_str);//url地址编码，防止中文乱码
//获取回调页面
$callback_url = isset($_GET['callback'])?$_GET['callback']:"index.php";
$callback_url = urldecode($callback_url);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="3;url=<?php echo $callback_url;?>" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script type="text/javascript">
	
</script>
</head>
<body>
	<div class="admin_tip">
		<div class="admin_tip_title">系统提示</div>
		<div class="admin_tip_content">
			<div class="admin_tip_str" style="color:<?php echo $tip_color;?>"><?php echo $tip_str;?></div>
			<div class="admin_tip_jump"><a href="<?php echo $callback_url;?>">如果您的浏览器没有自动跳转，请点击这里！</a></div>
		</div>
	</div>
</body>
</html>