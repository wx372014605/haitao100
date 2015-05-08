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
					<li class="current"><a href="index.php?app=recruitment&menu_index=2">招贤纳士</a><i></i></li>
					<li><a href="index.php?app=company_intro&menu_index=2">公司简介</a><i></i></li>
					<li><a href="index.php?app=contact_us&menu_index=2">联系我们</a><i></i></li>
				</ul>
			</div>
			<div class="about_content">
				<?php foreach ($recruitment_arr as $val){?>
				<div class="recruitment_list">
					<div class="recruitment_top">
						<div class="job_name">职位名称：<span><?php echo $val['job_name'];?></span></div>
						<div class="recruitment_num">招聘人数：<span><?php echo $val['recruitment_num'];?>人</span></div>
						<div class="add_time">发布时间：<span><?php echo $val['add_time'];?></span></div>
					</div>
					<div class="recruitment_info"><?php echo $val['requirement_content'];?></div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>

<!-- 引入网站通用底部文件 -->
<?php require 'modules/index_footer.php';?>