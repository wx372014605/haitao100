<?php
//引入配置文件和相关函数
require_once ('includes.php');

$wechatObj = new wechatCallbackapiTest();
$wechatObj->init();

class wechatCallbackapiTest
{
	private $token = 'HF_haitao100';//自定义微信token
	private $post_str = '';//收到的post参数
	private $weixin_crypt = false;//微信加密解密类句柄
	
	//自动确定是回复消息还是微信接入验证
	function init(){
		$this->post_str = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:'';
		if($this->post_str){
			$this->responseMsg();
		}else{
			$this->valid();
		}
	}
	
	private function valid(){
        $echoStr = isset($_GET["echostr"])?$_GET["echostr"]:'';

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    private function responseMsg(){
		//get post data, May be due to the different environments
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		global $weixin_handle;
		
		/****记录日志，用于调试程序****/
		//write_log(serialize($this->post_str),'weixin.log');
		//write_log(serialize($_GET),'weixin.log');
		/****记录日志，用于调试程序****/
		
      	//extract post data
		if (!empty($this->post_str)){
			$postObj = simplexml_load_string($this->post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
			$toUsername = $postObj->ToUserName;//公众帐号flatform_id
			$encrypt_msg = isset($postObj->Encrypt)?$postObj->Encrypt:'';//消息密文
			
			/*
			 * 解密微信消息
			 */
			$timestamp = get_string('timestamp');
			$nonce = get_string('nonce');
			$encrypt_type = strtolower(get_string('encrypt_type'));
			$msg_signature = get_string('msg_signature');
			if($encrypt_msg and $encrypt_type=='aes' and $msg_signature){
				$account_info = $weixin_handle->get_account_info($toUsername);
				if($account_info){
					$app_id = $account_info['app_id'];
					$app_secret = $account_info['app_secret'];
					$encoding_aes_key = $account_info['encoding_aes_key'];
					if($encoding_aes_key){
						//引入微信加密解密类
						require_once (WEB_ROOT.'class/weixin_crypt.php');
						$this->weixin_crypt = new WeixinCrypt($app_id,$encoding_aes_key,$this->token);
						$this->post_str = $this->weixin_crypt->decrypt($encrypt_msg, $toUsername, $msg_signature, $timestamp, $nonce);
						if($this->post_str){
							$postObj = simplexml_load_string($this->post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
						}else{
							$error_info = $this->weixin_crypt->get_error_info();
							$error_code = $error_info['error_code'];
							$error_message = $error_info['error_message'];
							write_log('微信消息解码发生错误，错误码：'.$error_code.'，错误信息：'.$error_message,'weixin.log');
						}
					}
				}
			}
			
			$fromUsername = $postObj->FromUserName;//用户open_id
			$msgtype = $postObj->MsgType;//消息类型
			
			/****根据接收的消息类型分别处理****/
			switch($msgtype){
				case "voice"://语音消息
					//获取语音消息信息
					$recognition = $postObj->Recognition;
					
					//当作文本消息来处理哦
					$postObj->Content = $recognition;
				case "text"://文本消息
					$keyword = trim($postObj->Content);//用户发送的消息内容
					if(!empty($keyword))
					{
						//回复文本消息
						$contentStr = $weixin_handle->simsimHttp($keyword);
						$this->replyText($toUsername,$fromUsername,$contentStr);
					}else{
						echo "Input something...";
					}
					break;
				case "image"://图片消息
					break;
				case "location"://地理位置消息
					break;
				case "link "://链接消息
					break;
				case "video"://视频消息
					break;
				case "event"://事件推送消息
					$event = $postObj->Event;
					switch ($event){
						case "subscribe"://关注事件
							//更新用户信息
							$weixin_handle->update_user_info($fromUsername,array("platform_id"=>$toUsername,"subscribe"=>"1"));
							//回复文本消息
							$contentStr = "Welcome to wechat world!";
							$this->replyText($toUsername,$fromUsername,$contentStr);
							break;
						case "unsubscribe"://取消关注事件
							//更新用户信息
							$weixin_handle->update_user_info($fromUsername,array("subscribe"=>"0"));
							break;
					}
				
			}
        }else {
        	echo "";
        	exit;
        }
    }
    
	//回复文本消息
	private function replyText($fromUsername,$toUsername,$content){
		$textTpl = "<xml>
					<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
					<CreateTime><![CDATA[".time()."]]></CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[$content]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";
		if($this->weixin_crypt){
			$textTpl = $this->weixin_crypt->encrypt($textTpl);
		}
		echo $textTpl;
		
		//记录聊天记录
		$talk_data = array(
			"content"	=>	$content,
		);
		//talk_record($fromUsername, $toUsername, "text", $talk_data, 1);
		exit();
	}
	
	//回复图文消息
	private function replyNews($fromUsername,$toUsername,$news_list){
		$newsTpl = "<xml>
				 <ToUserName><![CDATA[".$toUsername."]]></ToUserName>
				 <FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
				 <CreateTime><![CDATA[".time()."]]></CreateTime>
				 <MsgType><![CDATA[news]]></MsgType>
				 <ArticleCount>".count($news_list)."</ArticleCount>
				 <Articles>";
		foreach ($news_list as $val){
			$newsTpl .= "<item>
						 <Title>".$val['title']."</Title> 
						 <Description>".$val['description']."</Description>
						 <PicUrl>".$val['picurl']."</PicUrl>
						 <Url>".$val['url']."</Url>
					 </item>";
		}
		$newsTpl .= "</Articles>
				 <FuncFlag>0</FuncFlag>
				 </xml>";
		if($this->weixin_crypt){
			$newsTpl = $this->weixin_crypt->encrypt($newsTpl);
		}
		echo $newsTpl;
		
		//记录聊天记录
		//talk_record($fromUsername, $toUsername, "news", $news_list, 1);
		exit();
	}
	
	//回复音乐消息
	private function replyMusic($fromUsername,$toUsername,$title,$description,$musicurl,$hqmusicurl){
		$musicTpl = "<xml>
				 <ToUserName><![CDATA[".$toUsername."]]></ToUserName>
				 <FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
				 <CreateTime><![CDATA[".time()."]]></CreateTime>
				 <MsgType><![CDATA[music]]></MsgType>
				 <Music>
				 <Title><![CDATA[".$title."]]></Title>
				 <Description><![CDATA[".$description."]]></Description>
				 <MusicUrl><![CDATA[".$musicurl."]]></MusicUrl>
				 <HQMusicUrl><![CDATA[".$hqmusicurl."]]></HQMusicUrl>
				 </Music>
				 <FuncFlag>0</FuncFlag>
				 </xml>";
		if($this->weixin_crypt){
			$musicTpl = $this->weixin_crypt->encrypt($musicTpl);
		}
		echo $musicTpl;
		
		//记录聊天记录
		$talk_data = array(
			"title"			=>	$title,
			"description"	=>	$description,
			"musicurl"		=>	$musicurl,
			"hqmusicurl"	=>	$hqmusicurl,
		);
		//talk_record($fromUsername, $toUsername, "music", $talk_data, 1);
		exit();
	}
	
	//回复图片消息(高级接口)
	private function replyImg($fromUsername,$toUsername,$media_id){
		$imgTpl = "<xml>
				<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
				<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
				<CreateTime>".time()."</CreateTime>
				<MsgType><![CDATA[image]]></MsgType>
				<Image>
				<MediaId><![CDATA[".$media_id."]]></MediaId>
				</Image>
				</xml>";
		if($this->weixin_crypt){
			$imgTpl = $this->weixin_crypt->encrypt($imgTpl);
		}
		echo $imgTpl;
		exit();
	}
	
	//回复语音消息(高级接口)
	private function replyVoice($fromUsername,$toUsername,$media_id){
		$voiceTpl = "<xml>
					<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
					<CreateTime>".time()."</CreateTime>
					<MsgType><![CDATA[voice]]></MsgType>
					<Voice>
					<MediaId><![CDATA[".$media_id."]]></MediaId>
					</Voice>
					</xml>";
		if($this->weixin_crypt){
			$voiceTpl = $this->weixin_crypt->encrypt($voiceTpl);
		}
		echo $voiceTpl;
		exit();
	}
	
	//回复视频消息(高级接口)
	private function replyVideo($fromUsername,$toUsername,$media_id,$title,$description){
		$videoTpl = "<xml>
					<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
					<CreateTime>".time()."</CreateTime>
					<MsgType><![CDATA[video]]></MsgType>
					<Video>
					<MediaId><![CDATA[".$media_id."]]></MediaId>
					<Title><![CDATA[".$title."]]></Title>
					<Description><![CDATA[".$description."]]></Description>
					</Video> 
					</xml>";
		if($this->weixin_crypt){
			$videoTpl = $this->weixin_crypt->encrypt($videoTpl);
		}
		echo $videoTpl;
		exit();
	}
	
	//验证签名是否正确
	private function checkSignature(){
        $signature = get_string('signature');
        $timestamp = get_string('timestamp');
        $nonce = get_string('nonce');
        
		$tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
?>