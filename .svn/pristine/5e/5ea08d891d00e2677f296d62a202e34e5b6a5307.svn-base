<?php 
$menu_index = get_number('menu_index');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-cn" lang="zh-cn" xmlns:wb="http://open.weibo.com/wb">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="robots" content="index, follow" />
	<meta name="keywords" content="海淘100,海淘,海淘插件,海淘助手,购物助手,海淘比价,海淘网站,亚马逊,美亚,日亚,德亚,价格曲线,降价提醒" />
	<title>海淘100 - 海淘购物助手</title>
	<meta name="description" content="海淘100是国内领先的海淘购物助手软件，基于多个浏览器平台，能够帮助用户在美国，英国，法国，德国，日本，加拿大亚马逊等海淘网站上，自动计算人民币到手价，展示海淘商品的价格曲线，自动关注热门商品并降价提醒，自动进行包裹跟踪，支持淘宝比价。是国内知名度最高，用户数量最多，用户口碑最好的海淘插件工具，帮助广大淘友简化海淘流程。" />
	<link href="./yi_haitao_files/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link type="text/css" href="./yi_haitao_files/slider.css" rel="stylesheet" />
	<link rel="stylesheet" href="./yi_haitao_files/v2.css" type="text/css" />
	<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="./js/jquery.js"></script>

    <script type="text/javascript">
        var ieMode = document.documentMode;
        if (ieMode) {
            document.documentElement.className += ' iemode';
        }
        document.documentElement.className += ' ie' + document.documentMode;
        $(function(){
        	$('#mainmenu li:eq(<?php echo $menu_index?>)').addClass('active');
        });
    </script>
    <!--    右边固定栏的css    -->
	<style type="text/css">
	<!--
	#right_nav{
		position: fixed;
		width: 70px;
		right: 20px;
		top: 100px;
		z-index: 99999;
	}
	#right_nav ul{
		border: 1px solid #CBC4DB;
		border-radius: 5px;
		background: #fff;
	}
	#right_nav .right_nav_list{
		border-bottom: 1px solid #CBC4DB;
		text-align: center;
	}
	#qq_weixin_li{
		position: relative;
	}
	#qq_weixin_li:hover #qq_weixin_main{
		display: block;
	}
	#qq_weixin_main{
		position: absolute;
		top: 50%;
		margin-top: -75px;
		right: 100%;
		margin-right: 10px;
		display: none;
	}
	#qq_weixin_main .qq_weixin_padding{
		position: relative;
		padding:5px;
		border:1px solid #AFB4B7;
		background: #fff;
		border-radius:5px;
	}
	#qq_weixin_main .right_border{
		display: block;
		width: 10px;
		height: 20px;
		background: url('./yi_haitao_files/images_v2/right_border.png');
		position: absolute;
		right: 0;
		top: 50%;
		margin-right: -10px;
		margin-top: -10px;
	}
	#qq_weixin_main img{
		width: 140px;
		height: 140px;
	}
	#right_nav .right_nav_list a{
		display: block;
		padding: 6px 3px;
		text-decoration: none;
	}
	#right_nav .right_nav_list a:hover{
		background: #FCDEA5;
	}
	#right_nav .right_nav_list a img{
		display: block;
		height: 30px;
		width: 30px;
		margin: 0 auto;
	}
	#right_nav .right_nav_list span{
		color: #5A8BF0;
		font-size: 14px;
		font-family: "Microsoft YaHei";
	}
	-->
	</style>
</head>
<body id="page_bg" class="home">
	<a name="up" id="up"></a>
	<!--
    <div id="wrapper">
        <div id="global">
            <div class="content_c">
                <div id="topmenu">
                <!--  
                <a class="highlight" href="http://www.ehtao.net/faq/other/reinstall.html">解禁&amp;启用</a>
                -->
               <!-- <span>海外网站访问不了？</span>
                <a href="./yi_haitao_files/fg.zip" style="color:#C2090F;">点此下载翻墙神器</a>
                <span>|</span>
                <a href="http://weibo.com/haitao100" target="_blank" rel="nofollow">新浪微博</a>
                <span>|</span>
                <a href="#" target="_blank" onClick="return false;" rel="nofollow" style="margin-right:0px;">腾讯微博</a>
                </div>
            </div>
        </div>-->
        
		<!--     右边固定栏目     -->
		<div id="right_nav">
			<ul>
				<li class="right_nav_list">
					<a href="./yi_haitao_files/fg.zip">
						<img src="./yi_haitao_files/images_v2/fan.png" />
						<span>翻墙神器</span>
					</a>
				</li>
				<li class="right_nav_list">
					<a href="http://weibo.com/haitao100" target="_blank">
						<img src="./yi_haitao_files/images_v2/sina_weibo.png" />
						<span>新浪微博</span>
					</a>
				</li>
				<!--  
				<li class="right_nav_list">
					<a href="#" target="_blank" onClick="return false;" style="margin-right:0px;">
						<img src="./yi_haitao_files/images_v2/qq_weibo.png" />
						<span>腾讯微博</span>
					</a>
				</li>
				-->
				<li id="qq_weixin_li" class="right_nav_list">
					<a href="#" target="_blank" onClick="return false;" style="margin-right:0px;">
						<img src="./yi_haitao_files/images_v2/qq_weixin.png" />
						<span>微信平台</span>
					</a>
					<div id="qq_weixin_main">
						<div class="qq_weixin_padding">
							<img src="./yi_haitao_files/images_v2/weixin_qr_code.jpg"/>
							<i class="right_border"></i>
						</div>
					</div>
				</li>
				<li id="qq_weixin_li" class="right_nav_list">
					<a href="index.php?app=my_order&menu_index=3" target="_blank" style="margin-right:0px;">
						<img src="./yi_haitao_files/images_v2/my_order.png" />
						<span>我的订单</span>
					</a>
				</li>
				<li id="qq_weixin_li" class="right_nav_list">
					<a href="index.php?app=my_message&menu_index=3" target="_blank" style="margin-right:0px;">
						<img src="./yi_haitao_files/images_v2/my_message.png" />
						<span>我的消息</span>
					</a>
				</li>
				<li class="right_nav_list" style="border-bottom:0;">
					<a href="#">
						<img src="./yi_haitao_files/images_v2/top.png" />
						<span>返回顶部</span>
					</a>
				</li>
			</ul>
		</div>
		<!--     右边固定栏目     -->
		
        <div id="header" style="opacity: 1;">
            <div class="content_c">
                <div id="logo">
                	<a title="海淘100" href="index.php">
                		<img src="./yi_haitao_files/images_v2/logo.png"/>
					</a>
                </div>
                <ul class="menu" id="mainmenu">
                    <li><a href="index.php"><span>首页</span></a>
                    </li>
                    <li><a href="index.php?app=function_intro&menu_index=1"><span>使用指南</span></a>
                    </li>
                    <li><a href="index.php?app=recruitment&menu_index=2"><span>招贤纳士</span></a>
                    </li>
                    <li class="parent item60"><a><span>我的海淘</span></a>
                        <ul style="display: none;">
                            <li><a href="index.php?app=my_order&menu_index=3"><span>我的运单</span></a>
                            </li>
                            <li><a href="index.php?app=my_message&menu_index=3"><span>我的消息</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- 新浪微博加关注 -->
                <!--  
                <wb:follow-button uid="3087371879" type="red_4" style="float:right"></wb:follow-button>
                -->
                <div class="clr"></div>
            </div>
        </div>