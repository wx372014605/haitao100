<?php 
$lmenu_index = get_number('lmenu_index');
?>
<script type="text/javascript">
$(function(){
	$('#leftcolumn li:eq(<?php echo $lmenu_index?>)').addClass('active');
});
</script>
				<div id="leftcolumn">
                    <div class="module-faqmenu icon">
                        <h3>常见问题</h3>
                        <ul class="menu" id="faqmenu">
                            <li class="item21"><a href="index.php?app=function_intro&menu_index=1"><span>功能介绍</span></a>
                            </li>
                            <li class="item23"><a href="index.php?app=install_question&menu_index=1&lmenu_index=1"><span>安装疑难</span></a>
                            </li>
                            <!--  
                            <li class="item50"><a href="http://www.ehtao.net/faq/other.html"><span>其他问题</span></a>
                            </li>     
                            <li class="item10"><a href="http://www.ehtao.net/faq/team.html"><span>团队公告</span></a>
                            </li>
                            -->
                        </ul>
                    </div>
                    <div class="module-discussmenu icon">
                        <h3>求助&amp;反馈</h3>
                        <ul class="menu" id="discussmenu">
                            <li class="item37"><a href="http://weibo.com/haitao100" target="_blank" onclick="" rel="nofollow"><span>新浪微博</span></a>
                            </li>
                            <li class="item38"><a href="#" target="_blank" onclick="return false;" rel="nofollow"><span>腾讯微博</span></a>
                            </li>
                            <li class="item39"><a href="index.php?app=contact_us&menu_index=2" target="_blank"><span>联系我们</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="clr"></div>
                </div>