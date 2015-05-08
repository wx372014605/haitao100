<?php 
$now_time = time();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/common_fun.js"></script>
<script type="text/javascript">
	if (window.top == window) {
		window.top.location.href = 'index.php';
	}
	$(function(){
		var now_time = <?php echo $now_time;?>;
		setInterval(function(){
			now_time += 1;
			$("#server_time").text(timetostr(now_time));
		},1000);
	});
</script>
</head>
<body>
	<div class="content_main">
		<div class="location_bar">
			<span>当前位置</span>
			<span>&gt;&gt;</span>
			<span>服务器状态</span>
		</div>
		<div class="server_info">
			<p class="info_list">
				<span>当前时区：</span>
				<span><?php echo function_exists("date_default_timezone_get")?date_default_timezone_get():'未知';?></span>
			</p>
			<p class="info_list">
				<span>服务器时间：</span>
				<span id="server_time"><?php echo get_current_time();?></span>
			</p>
			<p class="info_list">
				<span>操作系统：</span>
				<span><?php echo php_uname('s').' '. php_uname('r');?>(<?php echo isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:""; ?>)</span>
			</p>
			<p class="info_list">
				<span>服务器信息：</span>
				<span><?php echo $_SERVER['SERVER_SOFTWARE'];?></span>
			</p>
			<p class="info_list">
				<span>php版本：</span>
				<span><?php echo PHP_VERSION;?></span>
			</p>
			<p class="info_list">
				<span>mysql版本：</span>
				<span><?php echo mysql_get_server_info();?></span>
			</p>
			<p class="info_list">
				<span>安全模式：</span>
				<span><?php echo (boolean) ini_get('safe_mode')?'<span style="color:green;">是</span>':'<span style="color:red;">否</span>';?></span>
			</p>
			<p class="info_list">
				<span>socket支持：</span>
				<span><?php echo (boolean) ini_get('fsockopen')?'<span style="color:green;">是</span>':'<span style="color:red;">否</span>';?></span>
			</p>
			<p class="info_list">
				<span>curl支持：</span>
				<span><?php echo function_exists('curl_init')?'<span style="color:green;">是</span>':'<span style="color:red;">否</span>';?></span>
			</p>
			<p class="info_list">
				<span>gd库支持：</span>
				<span><?php echo extension_loaded('gd')?'<span style="color:green;">是</span>':'<span style="color:red;">否</span>';?></span>
			</p>
			<p class="info_list">
				<span>memcache支持：</span>
				<span><?php echo $memcache_handle?'<span style="color:green;">是</span><a href="do.php?act=memcache_flush" style="color:#0382D2;margin:0 3px;" onclick="return confirm(\'系统将要清空所有内存缓存，是否继续？\');">清空缓存</a>':'<span style="color:red;">否</span>';?></span>
			</p>
			<p class="info_list">
				<span>文件缓存：</span>
				<span><?php echo is_writable(get_cache_path())?'<span style="color:green;">支持</span><a href="do.php?act=file_cache_flush" style="color:#0382D2;margin:0 3px;" onclick="return confirm(\'系统将要清空所有文件缓存，是否继续？\');">清空缓存</a>':'<span style="color:red;">不支持</span>';?></span>
			</p>
			<p class="info_list">
				<span>最大上传文件大小：</span>
				<span><?php echo ini_get('upload_max_filesize');?></span>
			</p>
			<p class="info_list">
				<span>最大post数据大小：</span>
				<span><?php echo ini_get('post_max_size');?></span>
			</p>
		</div>
	</div>
</body>
</html>