<?php 
//获取招聘信息列表
$recruitment_sql = "select * from company_recruitment where is_available=1";
$recruitment_arr = $mysql_handle->getRs($recruitment_sql);
?>
<?php require 'modules/index_header.php';?>
<div id="content">
	<div class="content_c">
		<div id="about_main">
			<div class="about_menu">
				<h2>关于我们</h2>
				<ul>
					<li><a href="index.php?app=recruitment&menu_index=2">招贤纳士</a><i></i></li>
					<li class="current"><a href="index.php?app=company_intro&menu_index=2">公司简介</a><i></i></li>
					<li><a href="index.php?app=contact_us&menu_index=2">联系我们</a><i></i></li>
				</ul>
			</div>
			<div class="about_content">
				<div id="company_info">
					<p>　　上海泓丰国际货物运输代理有限公司成立于2005年7月，前身为上海茂鸿国际货运有限公司下属分公司（成立于1998年9月）是一家集民用航空运输代理、海上、陆路国际货运业务于一体的一级货运代理物流企业（经商务部批准）。</p>
					<p>　　上海泓丰于2003年在国际市场成功创立了Asia-Ex这一品牌，并开发了上海-日本全境的快件专递服务，逐渐形成了公司自有的核心业务。日本专递业务承诺客户在48小时内将98%的货物送达日本各个目的地。</p>
					<p>　　上海泓丰在上海设有两大操作中心，在上海浦东国际机场报关仓库设有报关部，公司内部拥有一批优秀的涉及报关、IT、物流操作等领域的专业人才，借助上海至日本专递服务的产品优势，在国内建立起Asia-Ex的销售网络，并不断增加市场分额。</p>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- 引入网站通用底部文件 -->
<?php require 'modules/index_footer.php';?>