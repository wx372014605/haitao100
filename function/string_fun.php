<?php
/**
 * 截取字符串的函数
 *
 * @param	string		$str		被截取的字符串
 * @param	int			$length		截取的长度
 * @param	bool		$append		是否附加省略号
 * @param	string		$charset	编码设置，utf8,gbk
 *
 * @return  string
 */
function sub_str($str, $length, $append = true, $charset='utf8') {
	$str = trim($str);
	$strlength = strlen($str);
	$charset = strtolower($charset);

	if ($charset == 'utf8') {
		$l = 0;
		$i=0;
		while ($i < $strlength) {
			if (ord($str{$i}) < 0x80) { 
				$l++; $i++;
			} else if (ord($str{$i}) < 0xe0) {
				$l++; $i += 2; 
			} else if (ord($str{$i}) < 0xf0) { 
				$l += 2; $i += 3; 
			} else if (ord($str{$i}) < 0xf8) {
				$l += 1; $i += 4; 
			} else if (ord($str{$i}) < 0xfc) { 
				$l += 1; $i += 5; 
			} else if (ord($str{$i}) < 0xfe) { 
				$l += 1; $i += 6; 
			}

			if ($l >= $length) { 
				$newstr = substr($str, 0, $i);
				break;
			}
		}
		if($l < $length) {
			return $str;
		}
	} elseif($charset == 'gbk') {
		if ($length == 0 || $length >= $strlength) {
			return $str;
		}
		while ($i <= $strlength) {
			if (ord($str{$i}) > 0xa0) { 
				$l += 2; $i += 2;
			} else {
				$l++; $i++;
			}

			if ($l >= $length) { 
				$newstr = substr($str, 0, $i);
				break;
			}
		}
	}

	if ($append && $str != $newstr) {
		$newstr .= '..';
	}

	return $newstr;
}

/**
 * 给字符串添加转义
 *
 * @param	string		$str		要转义的字符串
 * @param   bollean		$force		强制添加转义
 * 
 * @return  string
 */
function add_slashes($str,$force=false){
	$str = trim($str);
	if($force or get_magic_quotes_gpc()){
		return $str;
	}else{
		return addslashes($str);
	}
}

/**
 * 给字符串去除转义
 *
 * @param	string		$str		要去除转义的字符串
 * @param   bollean		$force		强制去除转义
 * 
 * @return  string
 */
function strip_slashes($str,$force=false){
	$str = trim($str);
	if($force or get_magic_quotes_gpc()){
		return stripslashes($str);
	}else{
		return $str;
	}
}

/**
 * 去除字符串中的特殊字符
 *
 * @param	string		$str		原字符串
 *
 * @return  string
 */
function replace_special_chars($str){
	return shtmlspecialchars(add_slashes($str));
}

/**
 * 去除html标签
 * @param string
 * @return string
 * @author zjq
 */
function strip_html_tags($str){
	return preg_replace('/<[^>]+>/', '', $str);
}

/**
 * 去除字符串中的换行符
 * @author fly
 * @param string
 * @return string
 */
function strip_wraps($str){
	//return str_replace(PHP_EOL, '', $str);//因为不知道数据来源会出问题
	return str_replace(array("\r\n","\r","\n"), '', $str);
}

/**
 * 将字符串中的换行符替换成PHP_EOL(为了不让换行符丢失)
 * @author fly
 * @param string
 * @return string
 */
function wraps_encode($str){
	return str_replace(array("\r\n","\r","\n"), 'PHP_EOL', $str);

}

/**
 * 将字符串中的PHP_EOL还原成换行符
 * @author fly
 * @param string
 * @return string
 */
function wraps_decode($str){
	return str_replace('PHP_EOL', PHP_EOL, $str);
}

/**
 * 将中文字符串分割为数组
 * @author fly
 * @param string
 * @return array
 */
function str_to_array($str,$encoding='utf8'){
	$result_arr = array();
	$start = 0;
	while(true){
		$tmp = mb_substr($str, $start, 1, $encoding);
		$start += 1;
		if($tmp){
			$result_arr[] = $tmp;
		}else{
			break;
		}
	}
	return $result_arr;
}

/**
 * 替换特殊html字符串
 * @param $string or $array
 */
function shtmlspecialchars($string) {  
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}
?>