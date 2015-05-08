<?php 
//引入配置文件和相关函数
require_once ('../includes.php');

//判断管理员是否已经登录
if(!admin_login()){
	go_url('admin_login.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/menu.css" />
<script type="text/javascript">
	if (window.top == window) {
		window.top.location.href = 'index.php';
	}
</script>
</head>
<body>
<div class="top_main">
	<div class="left_border"></div>
	<div class="right_border"></div>
	<div class="center_border">
		<div class="center_content">
			<a class="system_title" target="main_content" href="modules.php?menu_tag=start"><img src="images/logo.png"/><span>后台管理系统</span></a>
			<div class="login_state">
				<span>欢迎回来<font class="horizontal_margin"><?php echo get_session('admin_name');?></font></span>
				<a class="login_out" href="login_out.php" target="_top" title="退出系统"></a>
			</div>
		</div>
	</div>
</div>
</body>
</html>