<?php 
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行

//获取最新购买的商品列表
$order_sql = "select od.goods_sign,od.goods_url,od.goods_img,od.goods_name,od.goods_price,od.zh_goods_name,site.site_name from 
transport_order as od join plugin_site as site on od.site_id=site.site_id where od.is_delete=0 order by od.order_id desc limit 15";
$order_arr = $mysql_handle->getRs($order_sql);

//获取最新浏览的商品列表
$history_sql = "select gh.*,site.site_name from goods_view_history as gh 
join plugin_site as site on gh.site_id=site.site_id";
if($order_arr){
	$sign_list = '';
	foreach ($order_arr as $val){
		$sign_list .= "'".$val['goods_sign']."',";
	}
	$sign_list = preg_replace('/,$/', '', $sign_list);
	$history_sql .= " where gh.goods_sign not in ($sign_list)";
}
$history_sql .= " order by gh.update_time desc limit 15";
$history_arr = $mysql_handle->getRs($history_sql);

//获取插件支持的网站列表
$site_sql = "select site_id,site_name,site_url,site_logo from plugin_site where is_support=1 and is_available=1";
$site_arr = $mysql_handle->getRs($site_sql);

//获取最新商品评论
$eval_sql = "select goods_name,goods_url,goods_img,eval_message from goods_eval order by eval_id desc limit 6";
$eval_arr = $mysql_handle->getRs($eval_sql);


//获取海淘商品分类
$class_sql = "select class_id,class_name from goods_class";
$class_arr = $mysql_handle->getRs($class_sql);
$class_name = array();
foreach ($class_arr as $val){
	$class_name[$val['class_id']] = $val['class_name'];
}

//获取海淘商品列表
$goods_cached = true;//是否已经缓存的标记
$haitao_goods = get_cache('haitao_goods');
if(!$haitao_goods){
	curl_get_contents(WEB_URL.'task/get_haitaobei_goods.php',0);
	curl_get_contents(WEB_URL.'task/get_smzdm_goods.php',0);
	curl_get_contents(WEB_URL.'task/get_dealam_goods.php',0);
	$goods_cached = false;
}else{
	if(!isset($haitao_goods['haitaobei'])){
		curl_get_contents(WEB_URL.'task/get_haitaobei_goods.php',0);
		$goods_cached = false;
	}
	if(!isset($haitao_goods['smzdm'])){
		curl_get_contents(WEB_URL.'task/get_smzdm_goods.php',0);
		$goods_cached = false;
	}
	if(!isset($haitao_goods['dealam'])){
		curl_get_contents(WEB_URL.'task/get_dealam_goods.php',0);
		$goods_cached = false;
	}
}
if(!$goods_cached){
	$haitao_goods = get_cache('haitao_goods');
}

$haitao_result = array();
foreach ($haitao_goods as $site_tag=>$val){
	foreach ($val as $class_id=>$goods_arr){
		if(isset($haitao_result[$class_id]) and $haitao_result[$class_id]){
			$haitao_result[$class_id] = array_merge($haitao_result[$class_id],$goods_arr);
		}else{
			$haitao_result[$class_id] = $goods_arr;
		}
	}
}

//格式化输出价格
function get_format_price($price){
	$price_arr = explode('.', $price);
	$price1 = $price_arr[0];
	$price2 = isset($price_arr[1])?$price_arr[1]:'00';
	$price_html = '<span class="haitao_goods_price" true_price="'.$price.'">￥<font>'.$price1.'</font>.'.$price2.'</span>';
	return $price_html;
}

//获取商品图片url地址(破解图片防盗链)
function get_goods_img($goods_img){
	if(preg_match('/^http:\/\/ym?\.zdmimg\.com/',$goods_img)){
		return WEB_URL.'index.php?app=get_daolian_pic&img_url='.urlencode($goods_img);
	}else{
		return $goods_img;
	}
}
?>
<?php require 'modules/index_header.php';?>
	<!-- 提交网站 -->
	<div id="window_back"></div>
	<div id="site_window_main">
		<div class="site_window_content">
			<img id="window_close_img" title="关闭窗口" src="./yi_haitao_files/images_v2/close.png"/>
			<div class="site_window_list"><span>网站主页：</span><input id="site_domain" name="site_domain"/></div>
			<div class="site_window_list"><a id="site_button" href="javascript:;">提交网站</a></div>
		</div>
	</div>
	<!-- 提交网站 -->
	
	
	
	<!--------------------------------修改后的banner--------------------------------------------->
	<div id="bn">
		<div class="mian"> 
			<div class="left">
				<ul>
					<li><a href="javascript:;" scroll_id="haitao_goods1">电器数码</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods2">服饰鞋包</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods3">美容化妆</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods4">母婴用品</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods5">日用百货</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods6">食品保健</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods7">图书音像</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods8">办公设备</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods9">家居装饰</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods10">运动户外</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods11">汽车用品</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods12">手表首饰</a></li>
					<li><a href="javascript:;" scroll_id="haitao_goods13">其他分类</a></li>
				</ul>
			</div>
			<span class="tu">
				<a href="#" class="lianjie"><img src="yi_haitao_files/images_v2/banners/01.jpg" height="390" width="600" /></a>
				<a href="#" class="lianjie" style="display:none"><img src="yi_haitao_files/images_v2/banners/02.jpg" height="390" width="600" /></a>
				<a href="#" class="lianjie" style="display:none"><img src="yi_haitao_files/images_v2/banners/03.jpg" height="390" width="600" /></a>
				<a href="#" class="lianjie" style="display:none"><img src="yi_haitao_files/images_v2/banners/04.jpg" height="390" width="600" /></a> 
				<a href="#" class="lianjie" style="display:none"><img src="yi_haitao_files/images_v2/banners/05.jpg" height="390" width="600" /></a>
				<a href="#" class="lianjie" style="display:none"><img src="yi_haitao_files/images_v2/banners/06.jpg" height="390" width="600" /></a>
			</span>
			<div class="right">
				<ul class="r_top">
					<li class="r_left"><div class="current_tab" show_tab="r_foot1"><a href="javascript:;">插件下载</a></div></li>
					<li class="r_right"><div show_tab="r_foot2"><a href="javascript:;">使用指南</a></div></li>
				</ul>
				<div id="r_foot1" class="r_foot">
					<div class="hide1">
						<ul>
							<li class="logo1" id="logo1">
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.xpi">Firefox插件下载</a></p>
							</li>
							<li class="logo1" id="logo2">
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">Chrome插件下载</a></p>
							</li>
							<li class="logo1" id="logo3">
								<p><a href="#">Safari插件下载</a></p>
							</li>
						</ul>
					</div>
					<div class="hide2">
						<ul>
							<li id="logo4">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">遨游</a></p>
							</li>
							<li id="logo5">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">猎豹</a></p>
							</li>
							<li id="logo6">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">淘宝</a></p>
							</li>
							<li id="logo7">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">UC</a></p>
							</li>
							<li id="logo8">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">百度</a></p>
							</li>
							<li id="logo9">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">Opera</a></p>
							</li>
							<li id="logo10">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">枫树</a></p>
							</li>
							<li id="logo11">
								<p><i></i></p>
								<p><a href="./yi_haitao_files/plugin/haitao_0.9.0.crx">搜狗</a></p>
							</li>
						</ul>
					</div>
				</div>
				<div id="r_foot2" class="r_foot">
					<ul>
						<li><a href="index.php?app=quickuse&menu_index=1" target="_blank">快速使用指南</a></li>
						<li><a href="index.php?app=daoshoujia&menu_index=1" target="_blank">自动计算人民币到手价</a></li>
						<li><a href="index.php?app=mianzhuce&menu_index=1" target="_blank">免注册</a></li>
						<li><a href="index.php?app=install_chrome&menu_index=1&lmenu_index=1" target="_blank">Chrome（谷歌浏览器）本地安装指南</a></li>
						<li><a href="index.php?app=install_opera&menu_index=1&lmenu_index=1" target="_blank">Opera（欧朋浏览器）安装指南</a></li>
						<li><a href="index.php?app=install_baidu&menu_index=1&lmenu_index=1" target="_blank">百度浏览器安装指南</a></li>
						<li><a href="index.php?app=install_uc&menu_index=1&lmenu_index=1" target="_blank">UC浏览器（电脑版）安装指南</a></li>
						<li><a href="http://weibo.com/haitao100" target="_blank">新浪微博</a></li>
						<li><a href="#" target="_blank" onclick="return false;">微信平台</a></li>
						<li><a href="index.php?app=contact_us&menu_index=2" target="_blank">联系我们</a></li>
					</ul>
				</div>
			</div>
		</div>	
		<div class="clr"></div>
		<div id="hao">
			<a class="xu">1</a> 
			<a class="xu">2</a>
			<a class="xu">3</a> 
			<a class="xu">4</a> 
			<a class="xu">5</a>
			<a class="xu">6</a> 
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			$('#bn .left a').click(function(){
				var scroll_id = $(this).attr('scroll_id');
				var header_height = $('#header').outerHeight();
				$("html,body").animate({scrollTop: $("#"+scroll_id).offset().top-header_height-60}, 500);
			});
			
			$('#bn .right .r_top div').hover(function(){
				$('#bn .right .r_top div').removeClass('current_tab');
				$(this).addClass('current_tab');
				var show_tab = $(this).attr('show_tab');
				$('#bn .right .r_foot').hide();
				$('#'+show_tab).show();
			});
		});
	</script>

<!--        <div id="banner">
            <div class="module-banner">

                <div class="flexslider" id="banners-slider">
                    <ul class="slides">
                             //这里有开头注释
                        <li data-downbtn-bgc="#2EA9FF" style="width: 100%; float: left; margin-right: -100%; display: list-item; background: url(./yi_haitao_files/images_v2/banners/1.jpg) 50% 50% no-repeat scroll rgb(255, 255, 255);">
                            <a href="#" target="_blank" onclick="return false;" class="img" style=""></a>
                        </li>   //这里有结尾注释
                        
                        <li data-downbtn-bgc="#2EA9FF" style="width: 100%; float: left; margin-right: -100%; display: none; background: url(./yi_haitao_files/images_v2/banners/2.jpg) 50% 50% no-repeat scroll rgb(255, 255, 255);">
                            <a href="#" target="_blank" onclick="return false;" class="img" style=""></a>
                        </li>    
                        <li data-downbtn-bgc="#08d7ae" style="width: 100%; float: left; margin-right: -100%; display: none; background: url(./yi_haitao_files/images_v2/banners/4.jpg) 50% 50% no-repeat scroll rgb(255, 255, 255);">
                            <a href="#" target="_blank" onclick="return false;" class="img" style="" rel="nofollow"></a>
                        </li>                   
                        <li data-downbtn-bgc="#08d7ae" style="width: 100%; float: left; margin-right: -100%; display: none; background: url(./yi_haitao_files/images_v2/banners/3.jpg) 50% 50% no-repeat scroll rgb(0, 86, 156);">
                            <a href="#" target="_blank" onclick="return false;" class="img" style="" rel="nofollow"></a>
                        </li>                       
                    </ul>
                </div>

                <div class="download-zone"></div>
                <div class="download">
                    <div class="content_c">
                        <div class="downbtn">
                            <a id="firefox_browser" class="main_browser_list" href="./yi_haitao_files/plugin/haitao_0.9.0.xpi">
                            <i></i>
                            <span>Firefox插件下载</span>
                            </a>
                             <a id="chrome_browser" class="main_browser_list" href="./yi_haitao_files/plugin/haitao_0.9.0.crx">
                            <i></i>
                            <span>Chrome插件下载</span>
                            </a>
                             <a id="safari_browser" class="main_browser_list" href="#">
                            <i></i>
                            <span>Safari插件下载</span>
                            </a>
                        </div>
                        <div class="clr"></div>
                    <ol class="flex-control-nav"><li><a class="active">1</a></li><li><a class="">2</a></li><li><a class="">3</a></li><!--  <li><a class="">4</a></li>               //这里有结尾注释
					</ol></div>
                </div>

                <div class="hidden">
                    <iframe id="downloadAction"></iframe>
                </div>

                <script type="text/javascript">
                    $(function() {
                        $('#banners-slider').flexslider({
                            controlsContainer: '.download .content_c',
                            start: function(slider) {
                                /*console.log('currentSlide='+slider.currentSlide+' nextSlide='+slider.nextSlide);*/
                                //$('.downbtn a').css('background-color', $('.slides li').eq(slider.currentSlide).attr('data-downbtn-bgc'));
                            },
                            before: function(slider) {
                                /*console.log('currentSlide='+slider.currentSlide+' nextSlide='+slider.nextSlide);*/
                                //$('.downbtn a').css('background-color', $('.slides li').eq(slider.nextSlide).attr('data-downbtn-bgc'));
                            }
                        });
                    });
                </script>

            </div>

            <div class="clr"></div>
        </div>

        <div class="downother">
            <div class="branch">
                <ul>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100搜狗版" rel="nofollow">
                            <i class="browser_icon icon_sogou"></i>
                            <p>搜狗</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100枫树版" rel="nofollow">
                            <i class="browser_icon icon_fengshu"></i>
                            <p>枫树</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100Opera版">
                            <i class="browser_icon icon_opera"></i>
                            <p>Opera</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100百度版">
                            <i class="browser_icon icon_baidu"></i>
                            <p>百度</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100UC版">
                            <i class="browser_icon icon_uc"></i>
                            <p>UC</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100淘宝浏览器版">
                            <i class="browser_icon icon_taobao"></i>
                            <p>淘宝</p>
                        </a>
                    </li>
                    <!-- 
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100Safari版" rel="nofollow">
                            <i class="browser_icon icon_safari"></i> 
                            <p>Safari</p>
                        </a>
                    </li> 
                    <li>
                        <a href="#" target="_blank" title="海淘100IE版" rel="nofollow">
                            <i class="browser_icon icon_ie"></i>
                            <p>IE</p>
                        </a>
                    </li>          //这里有结尾注释
                    
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100猎豹版" rel="nofollow">
                            <i class=" browser_icon icon_liebao"></i>
                            <p>猎豹</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.crx" target="_blank" title="海淘100傲游版" rel="nofollow">
                            <i class="browser_icon icon_maxthon"></i>
                            <p>傲游</p>
                        </a>
                    </li>
                    <!--  
                    <li>
                        <a href="#" target="_blank" title="海淘100360极速版" rel="nofollow">
                            <i class="browser_icon icon_360jisu"></i>
                            <p>360极速</p>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" title="海淘100360安全版" rel="nofollow">
                            <i class="browser_icon icon_360se"></i>
                            <p>360安全</p>
                        </a>
                    </li>
                    <li>
                        <a href="./yi_haitao_files/plugin/haitao_0.9.0.xpi" target="_blank" title="海淘100火狐版" rel="nofollow">
                            <i class="browser_icon icon_firefox"></i>
                            <p>火狐</p>
                        </a>
                    </li>
                           //这里有结尾注释
                </ul>

            </div>
        </div>
        <div class="clr"></div>-->
        <?php $index=0;?>
        <?php foreach ($haitao_result as $class_id=>$goods_arr){?>
        <?php $index+=1;?>
        <div id="haitao_goods<?php echo $index;?>" class="haitao_goods_rec" pic_loaded="false" allow_move="true" class_id="<?php echo $class_id?>">
        	<div class="content_c">
	        	<h2 class="haitao_goods_class">
	        		<i><?php echo $index;?>F</i>
	        		<span><?php echo $class_name[$class_id];?></span>
	        		<?php $lunbo_length = ceil(count($goods_arr)/8);?>
	        		<ul class="lunbo_position_bar">
	        			<?php for($i=0;$i<$lunbo_length;$i++){?>
	        			<li<?php if($i==0)echo ' class="lunbo_current"';?>></li>
	        			<?php }?>
	        		</ul>
	        	</h2>
	        	<div class="haitao_goods_main">
	        		<ul class="haitao_goods_ul">
	        			<?php $goods_index = 0;?>
	        			<?php while($goods_li = array_slice($goods_arr, $goods_index, 8)){?>
	        				<li class="haitao_goods_li">
		        				<?php foreach ($goods_li as $list){?>
		        				<div class="haitao_goods_list" site_tag="<?php echo $list['site_tag'];?>" goods_index="<?php echo $list['goods_index'];?>">
		        					<div class="haitao_goods_padding">
		        						<div class="haitao_goods_border">
				        					<div class="haitao_goods_img">
					        					<a href="<?php echo $list['goods_url'];?>" target="_blank"><img src="images/default.png" original="<?php echo get_goods_img($list['goods_img']);?>" onerror="this.src='images/nopic.png'"/></a>
					        					<?php if($list['site_name']){?>
					        					<span><i class="goods_before_ico"></i><?php echo $list['site_name'];?><i class="goods_after_ico"></i></span>
					        					<?php }?>
					        				</div>
					        				<div class="haitao_goods_list_bottom">
						        				<div class="haitao_goods_name"><a href="<?php echo $list['goods_url'];?>" target="_blank"><?php echo $list['goods_name'];?></a></div>
						        				<div class="haitao_goods_bottom">
						        					<?php echo get_format_price($list['goods_price'])?>
						        					<span class="haitao_goods_operate">
						        						<a class="haitao_goods_favorite"><?php echo $list['favorite_count'];?></a>
						        						<a class="haitao_goods_eval"><?php echo $list['eval_count'];?></a>
						        					</span>
						        				</div>
						        			</div>
					        			</div>
		        					</div>
		        				</div>
		        				<?php }?>
		        			</li>
	        				<?php $goods_index += 8;?>
	        			<?php }?>
	        		</ul>
	        		<a class="haitao_move_ico haitao_move_left"></a>
	        		<a class="haitao_move_ico haitao_move_right"></a>
	        	</div>
        	</div>
        </div>
        <?php }?>
		<?php if($history_arr){?>
		<div id="goods_history" class="goods_main">
			<div class="content_c">
				<h2 class="goods_list_title">其他用户也在关注<div class="bottom_border"></div></h2>
				<div class="goods_list_main">
					<?php foreach ($history_arr as $val){?>
					<div class="goods_list">
						<div class="goods_list_padding">
							<div class="goods_list_border">
								<div class="goods_list_padding2">
									<div class="goods_list_img">
										<a href="<?php echo $val['goods_url'];?>" target="_balnk">
											<img src="<?php echo $val['goods_img'];?>" onerror="this.src='images/nopic.png'"/>
										</a>
									</div>
									<h3 class="goods_list_name">
										<a href="<?php echo $val['goods_url'];?>" target="_balnk"><?php echo $val['goods_name'];?></a>
									</h3>
									<h3 class="goods_list_zh_name"><?php echo $val['zh_goods_name'];?></h3>
									<div class="goods_list_info">
										<span class="goods_list_price">¥<?php echo $val['goods_price'];?></span>
										<span class="goods_list_source">来源网站：<?php echo $val['site_name'];?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if($order_arr){?>
		<div id="goods_order" class="goods_main">
			<div class="content_c">
				<h2 class="goods_list_title">看看大家都买了什么<div class="bottom_border"></div></h2>
				<div class="goods_list_main">
					<?php foreach ($order_arr as $val){?>
					<div class="goods_list">
						<div class="goods_list_padding">
							<div class="goods_list_border">
								<div class="goods_list_padding2">
									<div class="goods_list_img">
										<a href="<?php echo $val['goods_url'];?>" target="_balnk">
											<img src="<?php echo $val['goods_img'];?>" onerror="this.src='images/nopic.png'"/>
										</a>
									</div>
									<h3 class="goods_list_name">
										<a href="<?php echo $val['goods_url'];?>" target="_balnk"><?php echo $val['goods_name'];?></a>
									</h3>
									<h3 class="goods_list_zh_name"><?php echo $val['zh_goods_name'];?></h3>
									<div class="goods_list_info">
										<span class="goods_list_price">¥<?php echo $val['goods_price'];?></span>
										<span class="goods_list_source">来源网站：<?php echo $val['site_name'];?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if(!empty($eval_arr)){?>
		<div class="content_c">
			<div id="goods_eval">
				<h2>热门晒单</h2>
				<div id="goods_eval_man">
					<ul class="goods_eval_ul">
						<?php foreach ($eval_arr as $key=>$val){?>
						<?php if($key>0 and $key%2==0)echo '</ul><ul class="goods_eval_ul">';?>
						<li>
							<div class="goods_eval_img">
								<a href="<?php echo $val['goods_url'];?>" target="_blank">
									<img src="<?php echo $val['goods_img'];?>"/>
								</a>
							</div>
							<div class="goods_eval_info">
								<p class="goods_eval_name">
									<a href="<?php echo $val['goods_url'];?>" target="_blank"><?php echo $val['goods_name'];?></a>
								</p>
								<p class="goods_eval_content">
									<a href="<?php echo $val['goods_url'];?>" target="_blank"><?php echo $val['eval_message'];?></a>
								</p>
							</div>
						</li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<?php }?>
		<div id="sites">
			<div class="content_c">
				<h2>已支持的海淘网站</h2>
				<ul class="site_ul">
					<?php foreach ($site_arr as $val){?>
					<li class="site_li">
						<a href="<?php echo $val['site_url'];?>" target="_blank">
							<img src="<?php echo $val['site_logo'];?>"/>
							<span><?php echo $val['site_name'];?></span>
						</a>
					</li>
					<?php }?>
				</ul>
				<div class="site_submit"><span>如果上述列表中没有您需要的网站，请<a id="site_submit" href="javascript:;">点击提交</a></span></div>
			</div>
		</div>
		
        <div id="content">
            <div class="content_c">

                <div id="maincolumn_full">

                    <div class="component-home">
                        <div class="componentheading">
                        </div>

                        <div class="contentpaneopen">
                            <ul class="usages-toc">
                                <li class="item1">
                                    <div>
                                        <h3>自动到手价</h3>
                                        <p>自动根据商品重量、距离和汇率，计算人民币到手价格</p>
                                        <a href="#"></a>
                                    </div>
                                </li>
                                <li class="item2">
                                    <div>
                                        <h3>尺码换算</h3>
                                        <p>最全面的海淘工具箱，尺码换算、单词翻译，统统不用愁</p>
                                        <a href="#"></a>
                                    </div>
                                </li>
                                <li class="item3">
                                    <div>
                                        <h3>一键转运</h3>
                                        <p>直接将商品信息直接发送到物流公司，免注册，一键搞定</p>
                                        <a href="#"></a>
                                    </div>
                                </li>
                                <li class="item4">
                                    <div>
                                        <h3>全程跟踪</h3>
                                        <p>全程跟踪境内境外物流状态，从商家发货到快递上门，支持所有主流转运路线</p>
                                        <a href="#"></a>
                                    </div>
                                </li>
                                <li class="item5">
                                    <div>
                                        <h3>微信同步</h3>
                                        <p>通过公众号随时掌握自己所关注商品和包裹的状态</p>
                                        <a href="#"></a>
                                    </div>
                                </li>
                                <li class="item6">
                                    <div>
                                        <h3>最多商家</h3>
                                        <p>最全的海外商家支持和多浏览器支持</p>
                                        <a href="#"></a>
                                    </div>
                                </li>
                            </ul>

                            <span class="article_separator">&nbsp;</span>
                            <div class="clr"></div>
                        </div>
                    </div>


                    <div class="clr"></div>

                </div>

                <div class="clr"></div>
            </div>
        </div>
	
		<!--  
        <div id="news">
            <div class="content_c">
                <div class="module-blognews">
                    <div class="item">
                        <div class="item_c">
                            <a href="http://www.ehtao.net/faq/other/howabout.html" target="_blank" style="background: url('yi_haitao_files/images_v2/tg_1.png') no-repeat scroll center center transparent;" class="img"></a>
                            <div class="caption">
                                <h3><a href="http://www.ehtao.net/faq/other/howabout.html" target="_blank" title="海淘助手NO.1">海淘助手NO.1</a></h3>
                                <p>作为国内最早诞生的海淘购物助手，海淘100拥有最多的用户数量，最高的知名度，最好的用户口碑，最细致完善的功能。因为专业，所以我们更放心。</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_c">
                            <a href="http://www.ehtao.net/faq/feature/autosync.html" target="_blank" style="background: url('yi_haitao_files/images_v2/tg_2.png') no-repeat scroll center center transparent;" class="img"></a>
                            <div class="caption">
                                <h3><a href="http://www.ehtao.net/faq/feature/autosync.html" target="_blank" title="自动云同步">自动云同步</a></h3>
                                <p>当您拥有了海淘100账号，就不用再担心重复操作了。您的关注商品，包裹单号，都将通过海淘100账号自动同步，您可以通过电脑和邮箱随时随地了解您关注的信息。</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_c">
                            <a href="http://www.ehtao.net/faq/other/download.html" target="_blank" style="background: url('yi_haitao_files/images_v2/tg_3.png') no-repeat scroll center center transparent;" class="img"></a>
                            <div class="caption">
                                <h3><a href="http://www.ehtao.net/faq/other/download.html" target="_blank" title="支持多个浏览器平台">支持多个浏览器平台</a></h3>
                                <p>我们几乎支持所有的浏览器平台，从Chrome-&gt;Firefox-&gt;IE-&gt;国内众多双核浏览器。我们尽量保证用户体验的一致，确保每个用户都获得更佳的海淘体验。</p>
                            </div>
                        </div>
                    </div>
                    <div id="latest_news" class="item">
                        <div class="item_c">
                            <div class="item_c_c">
                                <h3>常见问题</h3>
                                <ul>
                                    <li><a href="http://www.ehtao.net/faq/other/notdisplay.html" title="为什么安装了海淘100无法显示">为什么安装了海淘100无法显示？<span>[08/13]</span></a>
                                    </li>
                                    <li><a href="http://www.ehtao.net/faq/install/chrome.html" title="Chrome（谷歌浏览器）本地安装指南">Chrome（谷歌浏览器）本地安装指南<span>[08/13]</span></a>
                                    </li>
                                    <li><a href="http://www.ehtao.net/faq/install/baidu.html" title="百度浏览器安装指南">百度浏览器安装指南<span>[08/13]</span></a>
                                    </li>
                                    <li><a href="http://www.ehtao.net/faq/feature/pricehistory.html" title="如何查看并使用海淘100价格曲线">如何查看并使用海淘100价格曲线<span>[08/13]</span></a>
                                    </li>
                                    <li><a href="http://www.ehtao.net/faq/feature/pricealert.html" title="海淘100降价提醒怎么用">海淘100降价提醒怎么用？<span>[08/13]</span></a>
                                    </li>
                                    <li><a href="http://www.ehtao.net/faq/feature/coudan.html" title="什么是海淘凑单">什么是海淘凑单？<span>[08/13]</span></a>
                                    </li>
                                </ul>
                                <a href="http://www.ehtao.net/faq/feature.html" class="more">查看更多</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
	-->
	<script type="text/javascript">
	$(function(){
		//海淘商品延迟加载
		layer_load();
		$(window).bind('scroll',layer_load);

		function layer_load(){
			var scroll_top = $(document).scrollTop();
			var scroll_bottom = scroll_top + $(window).height();
			$('.haitao_goods_rec').each(function(){
				var pic_loaded = $(this).attr('pic_loaded');
				if(pic_loaded=='false'){
					var this_top = $(this).offset().top;
					var this_bottom = this_top + $(this).outerHeight();
					if(this_top<=scroll_bottom && this_bottom>=scroll_top){
						$(this).find('.haitao_goods_img img').each(function(){
							var original = $(this).attr('original');
							$(this).attr('src',original);
							$(this).attr('original','');
						});
						$(this).attr('pic_loaded','true');
					}
				}
			});
		}

		//显示滚动按钮
		$('.haitao_goods_main').hover(function(){
			var haitao_goods_li = $(this).find('.haitao_goods_li');
			if(haitao_goods_li.length>1){
				$(this).find('.haitao_move_ico').show();
			}
		},function(){
			$(this).find('.haitao_move_ico').hide();
		});
		
		//海淘商品滚动
		$('.haitao_goods_rec .haitao_goods_ul').scrollLeft(0);
		var goods_moving = false;
		$('.haitao_goods_rec .haitao_move_left').click(function(){
			if(goods_moving){
				return false;
			}else{
				goods_moving = true;
			}
			var haitao_goods_ul = $(this).siblings('.haitao_goods_ul');
			var haitao_goods_ul_width = haitao_goods_ul.width();
			//ie7以上及其他主流浏览器产生3像素bug，目前原因不明
			if(!$.browser.msie || ($.browser.version != "6.0"&&$.browser.version != "7.0")){
				haitao_goods_ul_width += 3;
			}
			var haitao_goods_li = haitao_goods_ul.find('.haitao_goods_li');
			var totle_width = haitao_goods_li.length*haitao_goods_ul_width;
			var old_left = haitao_goods_ul.scrollLeft();
			var new_left = old_left-haitao_goods_ul_width;
			if(new_left<=-haitao_goods_ul_width){
				new_left = totle_width-haitao_goods_ul_width;
			}
			var lunbo_bar_index = (new_left/haitao_goods_ul_width);
			var lunbo_position_li = $(this).parent('.haitao_goods_main').siblings('.haitao_goods_class').find('.lunbo_position_bar li');
			lunbo_position_li.removeClass('lunbo_current');
			lunbo_position_li.eq(lunbo_bar_index).addClass('lunbo_current');
			haitao_goods_ul.animate({scrollLeft:new_left},1000,function(){
				goods_moving = false;
			});
		});
		$('.haitao_goods_rec .haitao_move_right').click(function(){
			if(goods_moving){
				return false;
			}else{
				goods_moving = true;
			}
			var haitao_goods_ul = $(this).siblings('.haitao_goods_ul');
			var haitao_goods_ul_width = haitao_goods_ul.width();
			//ie7以上及其他主流浏览器产生3像素bug，目前原因不明
			if(!$.browser.msie || ($.browser.version != "6.0"&&$.browser.version != "7.0")){
				haitao_goods_ul_width += 3;
			}
			var haitao_goods_li = haitao_goods_ul.find('.haitao_goods_li');
			var totle_width = haitao_goods_li.length*haitao_goods_ul_width;
			var old_left = haitao_goods_ul.scrollLeft();
			var new_left = old_left+haitao_goods_ul_width;
			if(new_left>=totle_width){
				new_left = 0;
			}
			var lunbo_bar_index = (new_left/haitao_goods_ul_width);
			var lunbo_position_li = $(this).parent('.haitao_goods_main').siblings('.haitao_goods_class').find('.lunbo_position_bar li');
			lunbo_position_li.removeClass('lunbo_current');
			lunbo_position_li.eq(lunbo_bar_index).addClass('lunbo_current');
			haitao_goods_ul.animate({scrollLeft:new_left},1000,function(){
				goods_moving = false;
			});
		});

		//自动轮播
		$('.haitao_goods_rec').hover(function(){
			$(this).attr('allow_move','false');
		},function(){
			$(this).attr('allow_move','true');
		});
		var lunbo_timer = setInterval(function(){
			var window_center = $(document).scrollTop() + Math.round($(window).height()/2);
			$('.haitao_goods_rec').each(function(){
				var this_top = $(this).offset().top;
				var this_bottom = this_top + $(this).outerHeight();
				if(this_top<=window_center && this_bottom>=window_center){
					var allow_move = $(this).attr('allow_move');
					if(allow_move=='true'){
						$(this).find('.haitao_move_right').click();
					}
				}
			});
		},5000);
		
		//添加关注
		$('.haitao_goods_favorite').click(function(){
			var _this = this;
			var haitao_goods_name = $(this).parents('.haitao_goods_list_bottom').find('.haitao_goods_name a');
			var goods_name = haitao_goods_name.text();
			var goods_url = haitao_goods_name.attr('href');
			var goods_price = $(this).parents('.haitao_goods_list_bottom').find('.haitao_goods_price').attr('true_price');
			var goods_img = $(this).parents('.haitao_goods_border').find('.haitao_goods_img img').attr('src');
			var class_id = $(this).parents('.haitao_goods_rec').attr('class_id');
			var haitao_goods_list = $(this).parents('.haitao_goods_list');
			var site_tag = haitao_goods_list.attr('site_tag');
			var goods_index = haitao_goods_list.attr('goods_index');
			$.ajax({
	        	url:'interface.php?act=add_favorite_goods',
	        	type:"POST",
	        	data:'goods_url='+encodeURIComponent(goods_url)+'&goods_img='+goods_img+'&goods_name='+goods_name+'&goods_price='+goods_price+'&class_id='+class_id+'&site_tag='+site_tag+'&goods_index='+goods_index,
	        	dataType:"json",
	        	timeout:5000,
	        	cache:false,
	        	async:true,
	        	success:function(result){
	            	if(result.status==1){
	            		var click_count = parseInt($(_this).text());
	            		$(_this).text(click_count+1).unbind('click');
	            	}else{
	            		alert(result.message);
	            	}
	        	}
            });
		});
		//评价商品
		$('.haitao_goods_eval').click(function(){
			var _this = this;
			var haitao_goods_name = $(this).parents('.haitao_goods_list_bottom').find('.haitao_goods_name a');
			var goods_name = haitao_goods_name.text();
			var goods_url = haitao_goods_name.attr('href');
			var goods_price = $(this).parents('.haitao_goods_list_bottom').find('.haitao_goods_price').attr('true_price');
			var goods_img = $(this).parents('.haitao_goods_border').find('.haitao_goods_img img').attr('src');
			var class_id = $(this).parents('.haitao_goods_rec').attr('class_id');
			var haitao_goods_list = $(this).parents('.haitao_goods_list');
			var site_tag = haitao_goods_list.attr('site_tag');
			var goods_index = haitao_goods_list.attr('goods_index');
			var iframe_url = 'index.php?app=goods_eval&goods_url='+encodeURIComponent(goods_url)+'&goods_img='+goods_img+'&goods_name='+goods_name+'&goods_price='+goods_price+'&class_id='+class_id+'&site_tag='+site_tag+'&goods_index='+goods_index;
			layer_iframe('商品评价', iframe_url, {'width': 700});
		});
	});
	
    //提交网站
    $('#site_submit').click(function(){
        $('#window_back').height($(document).height()).show();
        var site_window_main = $('#site_window_main');
        var screen_width = $(window).width();
        var screen_height = $(window).height();
        var window_width = site_window_main.outerWidth();
        var window_height = site_window_main.outerHeight();
        var window_left = $(document).scrollLeft() + (screen_width-window_width)/2;
        var window_top = $(document).scrollTop() + (screen_height-window_height)/2;
        site_window_main.css({'left':window_left+'px','top':window_top+'px'}).fadeIn(500);
        $('#site_domain').focus();
        });
        $('#site_button').click(function(){
            var site_domain_obj = $('#site_domain');
            var site_domain = $.trim(site_domain_obj.val());
            if(site_domain==''){
                alert('网站主页不能为空！');
                site_domain_obj.focus();
                return false;
            }

            $.ajax({
	        	url:'interface.php?act=plugin_site_submit',
	        	type:"POST",
	        	data:'site_domain='+site_domain,
	        	dataType:"json",
	        	timeout:5000,
	        	cache:false,
	        	async:true,
	        	success:function(result){
	            	alert(result.message);
	            	if(result.status==1){
	            		site_domain_obj.val('');
	            		$('#window_close_img').click();
	            	}else{
	            		site_domain_obj.focus();
	            	}
	        	}
            });
        });
        //关闭提交网站窗口
        $('#window_close_img').click(function(){
        $('#window_back').hide();
        $('#site_window_main').fadeOut(500);
        });
    
        $("#wclose").click(function() {
            $("#warning").hide();
        })
         // 产品菜单
        $('.item60').prepend('<span class="divider">|</span>').hover(
            function() {
                $(this).children('ul').show();
            },
            function() {
                $(this).children('ul').hide();
            }
        );

        $(function() {
            // 判断浏览器
            ie6 = $.browser.msie && ($.browser.version == "6.0") && !$.support.style;
            var userAgent = navigator.userAgent.toLowerCase();
            var _alertbegin = '<div id="ie_alert"><div class="content_c"><span class="close">关闭</span>温馨提示：';
            var _alertend = '为得到更好的浏览体验，请使用' + '<a href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" target="_blank">IE7或以上版本</a>、' + '<a href="http://www.firefox.com" target="_blank">FireFox</a>、' + '<a href="http://www.google.cn/chrome/" target="_blank">Chrome</a>、' + '<a href="http://www.opera.com" target="_blank">Opera</a>、' + '<a href="http://www.apple.com.cn/safari/download/" target="_blank">Safari</a>' + '</div></div>';
            if (ie6 == true) {
                $('body').prepend(_alertbegin + '您使用的IE浏览器版本过低，' + _alertend);
            }
            /*else if (userAgent.indexOf('se 2.x') != -1) {
                            $('body').prepend(_alertbegin + '您正在使用搜狗浏览器，' + _alertend);
                        } else if (userAgent.indexOf('360se') != -1) {
                            $('body').prepend(_alertbegin + '您正在使用360浏览器，' + _alertend);
                        }*/
            $("#ie_alert .close").click(function() {
                $("#ie_alert").hide();
            });

            //图片切换
            $('.flexslider').hover(function() {
                $('.flex-direction-nav li a.prev').css('display', 'block');
                $('.flex-direction-nav li a.next').css('display', 'block');
            }, function() {
                $('.flex-direction-nav li a.prev').css('display', 'none');
                $('.flex-direction-nav li a.next').css('display', 'none');
            });
        });
    </script>
	<!-- layer弹出窗口 -->
	<script type="text/javascript" src="./layer/layer.min.js"></script>
	<script type="text/javascript" src="./js/layer_window.js"></script>
	<!-- layer弹出窗口 -->
<!-- 引入网站通用底部文件 -->
<?php require 'modules/index_footer.php';?>