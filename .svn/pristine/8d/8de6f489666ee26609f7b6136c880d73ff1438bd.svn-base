<?php
/**
 * 微欣消息加密解密类
 * @author zjq
 * @version 1.0
 */

class WeixinCrypt{
	private $app_id;
	private $encoding_aes_key;
	private $token;
	private $error_info;
	
	function __construct($app_id, $encoding_aes_key, $token){
		$this->app_id = $app_id;
		$this->encoding_aes_key = $encoding_aes_key;
		$this->token = $token;
		$this->error_info = array(
			"error_code"	=>	"0",
			"error_message"	=>	""
		);
	}
	
	//获取返回码对应的错误信息
	private function get_error_message($error_code){
		$message_arr = array(
			'40001'	=>	'签名验证错误',
			'40002'	=>	'xml解析失败',
			'40003'	=>	'sha加密生成签名失败',
			'40004'	=>	'encodingAesKey非法',
			'40005'	=>	'appid校验错误',
			'40006'	=>	'aes加密失败',
			'40007'	=>	'aes解密失败',
			'40008'	=>	'解密后得到的buffer非法',
			'40009'	=>	'base64加密失败',
			'40010'	=>	'base64解密失败',
			'40011'	=>	'生成xml失败'
		);
		return $message_arr[$error_code];
	}
	
	//获取微信接口的错误信息
	function get_error_info(){
		return $this->error_info;
	}
	
	//设置微信接口的错误信息
	private function set_error_info($error_code){
		$error_message = $this->get_error_message($error_code);
		$this->error_info = array(
			"error_code"	=>	$error_code,
			"error_message"	=>	$error_message
		);
	}
	
	/**
	 * 随机生成16位字符串
	 * @return string 生成的字符串
	 */
	private function get_rand_str(){

		$str = "";
		$str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($str_pol) - 1;
		for ($i = 0; $i < 16; $i++) {
			$str .= $str_pol[mt_rand(0, $max)];
		}
		return $str;
	}
	
	/**
	 * 消息加密
	 * @param string $reply_msg 公众平台待回复用户的消息，xml格式的字符串
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	function encrypt($reply_msg){
		//开始加密
		try {
			//获得16位随机字符串，填充到明文之前
			$random = $this->get_rand_str();
			$text = $random . pack("N", strlen($reply_msg)) . $reply_msg . $this->app_id;
			// 网络字节序
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$crypt_key = base64_decode($this->encoding_aes_key . "=");
			$iv = substr($crypt_key, 0, 16);
			//使用自定义的填充方式对明文进行补位填充
			$pkc_encoder = new PKCS7Encoder;
			$text = $pkc_encoder->encode($text);
			mcrypt_generic_init($module, $crypt_key, $iv);
			//加密
			$encrypted = mcrypt_generic($module, $text);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
			
			//使用BASE64对加密后的字符串进行编码
			$encrypted = base64_encode($encrypted);
		} catch (Exception $e) {
			$this->set_error_info('40006');
			return false;
		}
		
		$timestamp = time();//获取当前时间戳
		$nonce = mt_rand(1000, 99999).mt_rand(1000, 99999);//生成8到10位的随机数
		
		//生成安全签名
		$signature = $this->get_sha1($encrypted, $this->token, $timestamp, $nonce);
		if(!$signature){
			return false;
		}

		//生成发送的xml
		$format = <<<EOF
<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>
EOF;
		return sprintf($format, $encrypted, $signature, $timestamp, $nonce);
	}
	
	/**
	 * 消息解密
	 * @param string $encrypt_msg 密文消息
	 * @param string $to_username 消息的接收方，也即公众帐号
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	function decrypt($encrypt_msg, $to_username, $msg_signature, $timestamp, $nonce){
		//验证安全签名
		$signature = $this->get_sha1($encrypt_msg, $this->token, $timestamp, $nonce);
		if(!$signature){
			return false;
		}
		if ($signature != $msg_signature) {
			$this->set_error_info('40001');
			return false;
		}
		
		//开始解密
		try {
			//使用BASE64对需要解密的字符串进行解码
			$ciphertext_dec = base64_decode($encrypt_msg);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$crypt_key = base64_decode($this->encoding_aes_key . "=");
			$iv = substr($crypt_key, 0, 16);
			mcrypt_generic_init($module, $crypt_key, $iv);

			//解密
			$decrypted = mdecrypt_generic($module, $ciphertext_dec);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
		} catch (Exception $e) {
			$this->set_error_info('40007');
			return false;
		}
		
		try {
			//去除补位字符
			$pkc_encoder = new PKCS7Encoder;
			$result = $pkc_encoder->decode($decrypted);
			//去除16位随机字符串,网络字节序和AppId
			if (strlen($result) < 16){
				return false;
			}
			$content = substr($result, 16, strlen($result));
			$len_list = unpack("N", substr($content, 0, 4));
			$xml_len = $len_list[1];
			$xml_content = substr($content, 4, $xml_len);
			$from_appid = substr($content, $xml_len + 4);
		} catch (Exception $e) {
			$this->set_error_info('40008');
			return false;
		}
		if ($from_appid != $this->app_id){
			$this->set_error_info('40005');
			return false;
		}
		return $xml_content;
	}
	
	/**
	 * 用SHA1算法生成安全签名
	 * @param string $encrypt_msg 密文消息
	 * @param string $token 票据
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	private function get_sha1($encrypt_msg, $token, $timestamp, $nonce)
	{
		//排序
		try {
			$array = array($encrypt_msg, $token, $timestamp, $nonce);
			sort($array, SORT_STRING);
			$str = implode($array);
			return sha1($str);
		} catch (Exception $e) {
			$this->set_error_info('40003');
			return false;
		}
	}
}


/**
 * PKCS7Encoder class
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class PKCS7Encoder
{
	public static $block_size = 32;

	/**
	 * 对需要加密的明文进行填充补位
	 * @param $text 需要进行填充补位操作的明文
	 * @return 补齐明文字符串
	 */
	function encode($text)
	{
		$block_size = PKCS7Encoder::$block_size;
		$text_length = strlen($text);
		//计算需要填充的位数
		$amount_to_pad = PKCS7Encoder::$block_size - ($text_length % PKCS7Encoder::$block_size);
		if ($amount_to_pad == 0) {
			$amount_to_pad = PKCS7Encoder::block_size;
		}
		//获得补位所用的字符
		$pad_chr = chr($amount_to_pad);
		$tmp = "";
		for ($index = 0; $index < $amount_to_pad; $index++) {
			$tmp .= $pad_chr;
		}
		return $text . $tmp;
	}

	/**
	 * 对解密后的明文进行补位删除
	 * @param decrypted 解密后的明文
	 * @return 删除填充补位后的明文
	 */
	function decode($text)
	{

		$pad = ord(substr($text, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		return substr($text, 0, (strlen($text) - $pad));
	}

}
?>