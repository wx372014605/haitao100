<?php
//检测客户端是否是移动设备，如果是移动端返回true，否则false
function check_mobile(){
	global $_G;
	if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
	$mobile = array();
	static $mobilebrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
				'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
				'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
				'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
				'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
				'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
				'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(($v = dstrpos($useragent, $mobilebrowser_list, true))) {
		$_G['mobile'] = $v;
		return true;
	}
	$brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
	if(dstrpos($useragent, $brower)) return false;

	$_G['mobile'] = 'unknown';
	if($_GET['mobile'] === 'yes') {
		return true;
	} else {
		return false;
	}
}

function dstrpos($string, &$arr, $returnvalue = false) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}

//检测是否在微信内置浏览器访问
function is_weixin(){
	if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
	if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')!==false){
		return true;
	}else{
		return false;
	}
}

//获取微信浏览器的版本号
function get_weixin_version(){
	if(is_weixin()){
		$weixin_tag = strrchr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
		$tag_arr = explode('/', $weixin_tag);
		return floatval($tag_arr[1]);
	}else{
		return 0.0;
	}
}

//如果不在命令行运行，判断是否是移动设备
if(PHP_SAPI!='cli'){
	if(!check_mobile()){
		//header("location:".$comUrl);
		//exit();
	}
}
?>