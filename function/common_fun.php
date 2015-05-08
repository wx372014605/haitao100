<?php 
//获取当前时间
function get_current_time($time_str=''){
	if($time_str){
		return date("Y-m-d H:i:s",strtotime($time_str));
	}else{
		return date("Y-m-d H:i:s");
	}
}

//获取当前日期
function get_current_date($time_str=''){
	if($time_str){
		return date("Y-m-d",strtotime($time_str));
	}else{
		return date("Y-m-d");
	}
}

//获取当前日期前的日期列表
function get_date_range($days){
	$date_range = array();
	$start_stamp = strtotime("-".$days." day");//开始的时间戳
	$now_stamp = time();//当前时间戳
	while($start_stamp<$now_stamp){
		$date_range[] = date("Y-m-d",$start_stamp);
		$start_stamp += 86400;
	}
	return $date_range;
}

//获取时间字符串中的日期
function get_date($time_str){
	$time_arr = explode(" ", $time_str);
	return $time_arr[0];
}

//获取时间字符串中的日期，并格式化输出
function get_date_format($time_str){
	$date_str = preg_replace("/\s.*$/", '', $time_str);
	$date_arr = explode("-", $date_str);
	return $date_arr[0].'年'.$date_arr[1].'月'.$date_arr[2].'日';
}

//将秒数转换成时间
function second_to_time($second){
	$time_str = '';
	$hour = floor($second/3600);
	if($hour>0){
		$time_str .= $hour."时";
	}
	$minute = floor($second%3600/60);
	if($minute>0){
		$time_str .= $minute."分";
	}
	$second = floor($second%60);
	if($second>0){
		$time_str .= $second."秒";
	}
	return $time_str;
}

//获取客户端真实ip地址
function get_ip(){
	if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknow")){
		$ip = getenv("HTTP_CLIENT_IP");
	}else if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknow")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknow")){
		$ip = getenv("REMOTE_ADDR");
	}else if(isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"],"unknow")){
		$ip = $_SERVER["REMOTE_ADDR"];
	}else{
		$ip = "unknow";
	}
	return $ip;
}

//获取上一个页面的地址
function get_pre_url(){
	if(isset($_SERVER['HTTP_REFERER'])){
		return $_SERVER['HTTP_REFERER'];
	}else{
		return '';
	}
}

//获取当前页面的地址
function get_current_url(){
	return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	#return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
}

//设置session
function set_session($session_key,$session_value){
	$_SESSION[SESSION_PREFIX.$session_key] = $session_value;
}

//获取session
function get_session($session_key){
	if(isset($_SESSION[SESSION_PREFIX.$session_key])){
		return $_SESSION[SESSION_PREFIX.$session_key];
	}else{
		return false;
	}
}

//删除session
function delete_session($session_key){
	unset($_SESSION[SESSION_PREFIX.$session_key]);
}

//处理post提交的字符串类型的参数
function post_string($parm_key,$tag=1){
	if(isset($_POST[$parm_key])){
		if($tag){
			return replace_special_chars($_POST[$parm_key]);
		}else{
			return add_slashes($_POST[$parm_key]);
		}
	}else{
		return "";
	}
}

//处理post提交的数字类型的参数
function post_number($parm_key){
	if(isset($_POST[$parm_key])){
		return intval($_POST[$parm_key]);
	}else{
		return 0;
	}
}

//处理get提交的字符串类型的参数
function get_string($parm_key,$tag=1){
	if(isset($_GET[$parm_key])){
		if($tag){
			return replace_special_chars($_GET[$parm_key]);
		}else{
			return add_slashes($_GET[$parm_key]);
		}
	}else{
		return "";
	}
}

//处理get提交的数字类型的参数
function get_number($parm_key){
	if(isset($_GET[$parm_key])){
		return intval($_GET[$parm_key]);
	}else{
		return 0;
	}
}

//传入错误代码和错误提示内容，转化为json格式输出
function ajax_return($error_code,$message,$attach_info=array()){
	$result_arr = array(
		"status" =>	$error_code,
		"message" => $message,
		"attach" => $attach_info
	);
	echo json_encode($result_arr);
	exit();
}

//生成订单号
function create_shopping_num($prefix=''){
	$micro_time = microtime();
	$micro_arr = explode(' ', $micro_time);
	$shopping_num = $micro_arr[1] . sprintf("%04d", $micro_arr[0]*1000) . mt_rand(100000,999999);
	return $shopping_num;
}

//创建目录
function create_dir($dir_name){
	if(is_dir(WEB_ROOT.$dir_name)){
		return true;
	}else{
		return @mkdir(WEB_ROOT.$dir_name, 0777, true);
	}
}

/**
 * 
 * 清空目录
 * @param $dir_name 待清空的目录
 * @param $delete_self 是否删除自身
 */
function empty_dir($dir_name,$delete_self=false){
	if(is_dir($dir_name)){
		$dir_handle = opendir($dir_name);
		while(($file=readdir($dir_handle))!==false){
			if($file!='.' and $file!='..'){
				$child_file = $dir_name.'/'.$file;
				if(is_dir($child_file)){
					empty_dir($child_file,true);
				}else{
					@unlink($child_file);
				}
			}
		}
		closedir($dir_handle);
		if($delete_self){
			return @rmdir($dir_name);
		}else{
			return true;
		}
	}else{
		return true;
	}
}

//读取文件
function read_file($file_name){
	if(file_exists(WEB_ROOT.$file_name)){
		return @file_get_contents(WEB_ROOT.$file_name);
	}else{
		return false;	
	}
}

//写入文件
function write_file($file_name,$contents){
	$file = @fopen(WEB_ROOT.$file_name, "w");
	if($file){
		$result = @fwrite($file, $contents);
		fclose($file);
		return $result;
	}else{
		return false;
	}
}

//删除文件
function delete_file($file_name){
	if(file_exists(WEB_ROOT.$file_name)){
		return @unlink(WEB_ROOT.$file_name);
	}else{
		return true;
	}
}

//获取文件扩展名
function get_extend_name($file_name){
	$file_name = preg_replace("/\?.*$/", "", $file_name);
	$path_arr = explode(".", $file_name);
	return end($path_arr);
}

//写日志
function write_log($content,$log_file='system.log'){
	$log_dir = WEB_ROOT.'log/';
	if(!is_dir($log_dir)){
		mkdir($log_dir, 0777, true);
	}
	$log_file = $log_dir.$log_file;
	$log_handle = fopen($log_file, "a");
	if($log_handle){
		$result = fwrite($log_handle, '【'.get_current_time().'】'.$content."\r\n");
		fclose($log_handle);
		if($result){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//获取内容摘要(去除html标签)
function get_abstract($content,$words_num=180){
	$content = preg_replace("/<[^>]*?[\/]?\s*>/", '', $content);
	return sub_str($content, $words_num);
}

//获取url参数
function get_url_query($except_key=''){
	$url_query = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
	if($url_query){
		if($except_key){
			$result_arr = array();
			$query_arr = explode("&", $url_query);
			foreach ($query_arr as $val){
				$tmp_arr = explode("=", $val);
				$query_key = $tmp_arr[0];
				$query_value = isset($tmp_arr[1])?$tmp_arr[1]:'';
				$result_arr[$query_key] = $query_value;
			}
			if(isset($result_arr[$except_key])){
				unset($result_arr[$except_key]);
			}
			$result_query = '';
			foreach ($result_arr as $key=>$val){
				$result_query .= $key.'='.$val.'&';
			}
			$result_query = preg_replace('/&$/', '', $result_query);
			return $result_query;
		}else{
			return $url_query;
		}
	}else{
		return '';
	}
}

//写入缓存文件
function write_cache($content,$cache_name){
	$cache_dir = 'cache/';
	create_dir($cache_dir);
	$cache_file = $cache_dir.$cache_name;
	return write_file($cache_file,$content);
}
//读取缓存文件
function read_cache($cache_name){
	$cache_dir = 'cache/';
	$cache_file = $cache_dir.$cache_name;
	return read_file($cache_file);
}

//获取缓存文件详细信息
function get_cache_info($cache_name){
	$cache_file = get_cache_path($cache_name);
	if(file_exists($cache_file)){
		$file = @fopen($cache_file, "r");
		if($file){
			$cache_info = fstat($file);
			fclose($file);
			return $cache_info;
		}else{
			return array();
		}
	}else{
		return array();
	}
}

//获取缓存文件绝对路径
function get_cache_path($cache_name=''){
	$cache_dir = WEB_ROOT.'cache/';
	$cache_file = $cache_dir.$cache_name;
	return $cache_file;
}

//删除缓存文件
function delete_cache($cache_name){
	$cache_dir = 'cache/';
	$cache_file = $cache_dir.$cache_name;
	return delete_file($cache_file);
}

/**
 * 
 * 设置缓存(在memcache不支持时自动启用文件缓存)
 * @param $key_list 缓存主键
 * @param $cache_value 缓存值
 * @param $expires_in 多少秒过期
 */
function set_cache($key_name,$cache_value,$expires_in){
	global $memcache_handle;
	if(empty($key_name)){
		return false;
	}else{
		if($memcache_handle){
			//memcache过期时间用秒数表示不能超过30天(2592000秒 )
			if($expires_in>30*86400){
				$expires_in += time();
			}
			$result = $memcache_handle->set($key_name,$cache_value,$expires_in);
		}else{
			$key_first = substr(md5($key_name), 0, 1);
			$cache_file = 'cache_'.$key_first.'.tmp';
			$cache_data = unserialize(read_cache($cache_file));
			$cache_data[$key_name] = array(
				'data'=>$cache_value,
				'expires_time'=>time()+$expires_in
			);
			$new_data = serialize($cache_data);
			$result = write_cache($new_data, $cache_file);
		}
		return $result;
	}
}

/**
 * 
 * 读取缓存
 * @param $key_name
 */
function get_cache($key_name){
	global $memcache_handle;
	if(empty($key_name)){
		return false;
	}else{
		if($memcache_handle){
			$result = $memcache_handle->get($key_name);
		}else{
			$key_first = substr(md5($key_name), 0, 1);
			$cache_file = 'cache_'.$key_first.'.tmp';
			$cache_data = unserialize(read_cache($cache_file));
			if(isset($cache_data[$key_name])){
				$expires_time = $cache_data[$key_name]['expires_time'];
				if($expires_time>=time()){
					return $cache_data[$key_name]['data'];
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		return $result;
	}
}

//给用户发送消息
function send_system_message($user_id,$msg_title,$msg_content,$msg_link='',$msg_type=0){
	global $mysql_handle;
	$send_time = get_current_time();
	$message_sql = "insert into system_message (msg_type,user_id,msg_title,msg_content,msg_link,send_time) 
	values ($msg_type,$user_id,'$msg_title','$msg_content','$msg_link','$send_time')";
	$result = $mysql_handle->query($message_sql);
	return $result;
}

/**
* 各币种(币别)ISO标准代码(编码)如下
人民币Chinese Yuan Renminbi.CNY
港元Hong Kong Dollar.HKD
台币Taiwan Dollar.TWD
欧元Euro.EUR
美元US Dollar.USD
英镑British Pound.GBP
澳元Australian Dollar.AUD
韩元South-Korean Won.KRW
日元Japanese Yen.JPY

阿尔巴尼亚列克Albanian Lek.ALL
阿尔及利亚第纳尔Algerian Dinar.DZD
阿根廷比索Argentine Peso.ARS
阿鲁巴岛弗罗林Aruba Florin.AWG
澳元Australian Dollar.AUD
埃及镑Egyptian Pound.EGP
埃塞俄比亚比尔Ethiopian Birr.ETB
澳门元Macau Pataca.MOP
阿曼里亚尔Omani Rial.OMR
阿联酋迪拉姆UAE Dirham.AED
巴哈马元Bahamian Dollar.BSD
巴林第纳尔Bahraini Dinar.BHD
巴巴多斯元Barbados Dollar.BBD
白俄罗斯卢布Belarus Ruble.BYR
伯利兹元Belize Dollar.BZD
百慕大元Bermuda Dollar.BMD
不丹卢比Bhutan Ngultrum.BTN
玻利维亚诺Bolivian Boliviano.BOB
博茨瓦纳普拉Botswana Pula.BWP
巴西里亚伊Brazilian Real.BRL
保加利亚列瓦Bulgarian Lev.BGN
布隆迪法郎Burundi Franc.BIF
冰岛克朗Iceland Krona.ISK
巴基斯坦卢比Pakistani Rupee.PKR
巴拿马巴尔博亚Panama Balboa.PAB
巴布亚新几内亚基那Papua New Guinea Kina.PGK
巴拉圭瓜拉尼Paraguayan Guarani.PYG
波兰兹罗提Polish Zloty.PLN
朝鲜圆North Korean Won.KPW
多哥非洲共同体法郎CFA Franc BCEAO.XOF
丹麦克朗Danish Krone.DKK
多米尼加比索Dominican Peso.DOP
佛得角埃斯库多Cape Verde Escudo.CVE
福克兰群岛镑Falkland Islands Pound.FKP
斐济元Fiji Dollar.FJD
菲律宾比索Philippine Peso.PHP
刚果中非共同体法郎CFA Franc BEAC.XAF
哥伦比亚比索Colombian Peso.COP
哥斯达黎加科朗Costa Rica Colon.CRC
古巴比索Cuban Peso.CUP
格林纳达东加勒比元East Caribbean Dollar.XCD
冈比亚达拉西Gambian Dalasi.GMD
圭亚那元Guyana Dollar.GYD
海地古德Haiti Gourde.HTG
洪都拉斯伦皮拉Honduras Lempira.HNL
哈萨克斯坦腾格Kazakhstan Tenge.KZT
柬埔寨利尔斯Cambodia Riel.KHR
加拿大元Canadian Dollar.CAD
捷克克朗Czech Koruna.CZK
吉布提法郎Dijibouti Franc.DJF
几内亚法郎Guinea Franc.GNF
科摩罗法郎Comoros Franc.KMF
克罗地亚库纳Croatian Kuna.HRK
肯尼亚先令Kenyan Shilling.KES
科威特第纳尔Kuwaiti Dinar.KWD
卡塔尔利尔Qatar Rial.QAR
老挝基普Lao Kip.LAK
拉脱维亚拉图Latvian Lats.LVL
黎巴嫩镑Lebanese Pound.LBP
莱索托洛提Lesotho Loti.LSL
利比里亚元Liberian Dollar.LRD
利比亚第纳尔Libyan Dinar.LYD
立陶宛里塔斯Lithuanian Litas.LTL
列斯荷兰盾Neth Antilles Guilder.ANG
罗马尼亚新列伊Romanian New Leu.RON
卢旺达法郎Rwanda Franc.RWF
孟加拉塔卡Bangladesh Taka.BDT
马其顿第纳尔Macedonian Denar.MKD
马拉维克瓦查Malawi Kwacha.MWK
马来西亚林吉特Malaysian Ringgit.MYR
马尔代夫卢非亚Maldives Rufiyaa.MVR
毛里塔尼亚乌吉亚Mauritania Ougulya.MRO
毛里求斯卢比Mauritius Rupee.MUR
墨西哥比索Mexican Peso.MXN
摩尔多瓦列伊Moldovan Leu.MDL
蒙古图格里克Mongolian Tugrik.MNT
摩洛哥道拉姆Moroccan Dirham.MAD
缅甸元Myanmar Kyat.MMK
秘鲁索尔Peruvian Nuevo Sol.PEN
纳米比亚元Namibian Dollar.NAD
尼泊尔卢比Nepalese Rupee.NPR
尼加拉瓜科多巴Nicaragua Cordoba.NIO
尼日利亚奈拉Nigerian Naira.NGN
挪威克朗Norwegian Kroner.NOK
南非兰特South African Rand.ZAR
瑞典克朗Swedish Krona.SEK
瑞士法郎Swiss Franc.CHF
萨尔瓦多科朗El Salvador Colon.SVC
萨摩亚塔拉Samoa Tala.WST
圣多美多布拉Sao Tome Dobra.STD
沙特阿拉伯里亚尔Saudi Arabian Riyal.SAR
塞舌尔法郎Seychelles Rupee.SCR
塞拉利昂利昂Sierra Leone Leone.SLL
所罗门群岛元Solomon Islands Dollar.SBD
索马里先令Somali Shilling.SOS
斯里兰卡卢比Sri Lanka Rupee.LKR
圣赫勒拿群岛磅St Helena Pound.SHP
斯威士兰里兰吉尼Swaziland Lilageni.SZL
土耳其新里拉New Turkish Lira.TRY
太平洋法郎Pacific Franc.XPF
坦桑尼亚先令Tanzanian Shilling.TZS
泰国铢Thai Baht.THB
汤加潘加Tonga Pa'anga.TOP
特立尼达和多巴哥元Trinidad&amp;Tobago Dollar.TTD
突尼斯第纳尔Tunisian Dinar.TND
文莱元Brunei Dollar.BND
危地马拉格查尔Guatemala Quetzal.GTQ
乌克兰赫夫米Ukraine Hryvnia.UAH
乌拉圭新比索Uruguayan New Peso.UYU
瓦努阿图瓦图Vanuatu Vatu.VUV
越南盾Vietnam Dong.VND
匈牙利福林Hungarian Forint.HUF
新西兰元New Zealand Dollar.NZD
新加坡元Singapore Dollar.SGD
叙利亚镑Syrian Pound.SYP
印度卢比Indian Rupee.INR
印度尼西亚卢比(盾)Indonesian Rupiah.IDR
伊朗里亚尔Iran Rial.IRR
伊拉克第纳尔Iraqi Dinar.IQD
以色列镑Israeli Shekel.ILS
牙买加元Jamaican Dollar.JMD
约旦第纳尔Jordanian Dinar.JOD
也门里亚尔Yemen Riyal.YER
智利比索Chilean Peso.CLP
直布罗陀镑Gibraltar Pound.GIP
赞比亚克瓦查Zambian Kwacha.ZMK
铜价盎司Copper Ounces.XCP
金价盎司Gold Ounces.XAU
钯价盎司Palladium Ounces.XPD
铂价盎司Platinum Ounces.XPT
银价盎司Silver Ounces.XAG
*/
/*
function get_exchange_rate($from,$to){
	//引入phpQuery
	require_once (WEB_ROOT.'class/phpQuery.php');
	
	$url = 'http://www.123cha.com/hl/?from='.$from.'&to='.$to.'&q=1';
	$result = curl_get_contents($url);
	if($result){
		$result_doc = phpQuery::newDocument($result);
		return floatval($result_doc['#contain #left .tb tr:eq(2) td:eq(1)']->html());
	}else{
		write_log('获取汇率接口异常，请尽快修复！','exchange.log');
		return 0.0000;
	}
}
*/
function get_exchange_rate($from,$to){
	//引入phpQuery
	require_once (WEB_ROOT.'class/phpQuery.php');
	
	$url = 'http://huobiduihuan.51240.com/?f='.$from.'&t='.$to.'&j=1';
	$result = curl_get_contents($url);
	if($result){
		$result_doc = phpQuery::newDocument($result);
		return $result_doc['#main_content table:eq(1) tr:first td:eq(2) span:first']->html();
	}else{
		write_log('获取汇率接口异常，请尽快修复！','exchange.log');
		return 0.0000;
	}
}
?>