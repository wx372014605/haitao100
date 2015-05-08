<?php
//直接跳转页面
function go_url($url){
	echo "<script>location.replace('$url')</script>";
	exit();
}

//显示给用户提示然后自动跳转
function show_tip($status,$tip_str,$callback=""){
	global $baseUrl;//web访问根目录
	if($callback==""){
		$callback = get_pre_url();//获取上一个页面的地址
	}
	if($callback==""){
		$callback = "weixin_index.php";
	}
	$tip_str = urlencode($tip_str);//url地址编码，防止中文乱码
	$callback = urlencode($callback);//特殊符号url地址编码
	//header("location:".$baseUrl."act_tip.php?status=".$status."&tip_str=".$tip_str."&callback=".$callback);
	$tip_url = $baseUrl."act_tip.php?status=".$status."&tip_str=".$tip_str."&callback=".$callback;
	echo "<script>location.replace('$tip_url')</script>";
	exit();
}

//后台显示给用户提示然后自动跳转
function admin_tip($status,$tip_str,$callback=""){
	if($callback==""){
		$callback = get_pre_url();//获取上一个页面的地址
	}
	if($callback==""){
		$callback = "index.php";
	}
	$tip_str = urlencode($tip_str);//url地址编码，防止中文乱码
	$callback = urlencode($callback);//特殊符号url地址编码
	$tip_url = WEB_URL."admin/admin_tip.php?status=".$status."&tip_str=".$tip_str."&callback=".$callback;
	echo "<script>location.replace('$tip_url')</script>";
	exit();
}
?>