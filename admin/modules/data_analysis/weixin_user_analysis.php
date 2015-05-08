<?php 
//获取公众账号平台id
$platform_id = get_string('platform_id');

//获取公众帐号列表
$account_sql = "select platform_id,account_name from weixin_account";
$account_arr = $mysql_handle->getRs($account_sql);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo WEB_KEYWORDS;?>" />
<meta name="description" content="<?php echo WEB_DESCRIPTION;?>" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/content.css" />
<link rel="stylesheet" type="text/css" href="css/data_analysis.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
	var platform_id = '<?php echo $platform_id?>';
	var data_class = 0;//默认显示用户净增报表
	var data_day = 30;//默认显示30天的数据
	$(function(){
		//iframe高度自适应
		refresh_iframe();
		$("#analysis_iframe").bind("load",function(){
			$(this).height($(this).contents().height());
			if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style){
				$(this).width($(this).contents().width());
			}
		});
		
		//切换公众平台
		$("#platform_id").change(function(){
			location.href = 'modules.php?menu_tag=weixin_user_analysis&platform_id='+$(this).val();
			return false;
		});

		//切换报表类型
		$(".chart_type").click(function(){
			$(".chart_type").removeClass('current_class');
			$(this).addClass('current_class');
		});
		$("#raise_num").click(function(){
			data_class = 0;
			$(".time_condition").show();
			refresh_iframe();
		});
		$("#totle_num").click(function(){
			data_class = 1;
			$(".time_condition").show();
			refresh_iframe();
		});
		$("#user_range").click(function(){
			$(".time_condition").hide();
			$("#analysis_iframe").attr("src","modules.php?menu_tag=weixin_user_range&platform_id="+platform_id);
		});
		$("#sex_percent").click(function(){
			$(".time_condition").hide();
			$("#analysis_iframe").attr("src","modules.php?menu_tag=weixin_user_sex&platform_id="+platform_id);
		});
		$(".data_time_ul a").click(function(){
			$(".data_time_ul a").removeClass('current_time');
			$(this).addClass('current_time');
			data_day = $(this).attr('data_day');
			refresh_iframe();
		});
	});

	//刷新数据内容
	function refresh_iframe(){
		var data_url = "modules.php?menu_tag=weixin_user_data&platform_id="+platform_id+"&data_class="+data_class+"&data_day="+data_day;
		$("#analysis_iframe").attr("src",data_url);
	}
</script>
</head>
<body>
<div class="content_main">
	<div class="location_bar">
		<span>当前位置</span>
		<span>&gt;&gt;</span>
		<span>数据分析</span>
		<span>&gt;&gt;</span>
		<span>用户分析</span>
	</div>
	<div class="search_div">
		<form class="search_form" action="" method="get">
			<span class="search_tip">公众帐号：</span>
			<select id="platform_id" name="platform_id">
				<option value="">全部帐号</option>
				<?php foreach ($account_arr as $val){?>
				<option value="<?php echo $val['platform_id'];?>" <?php if($platform_id==$val['platform_id'])echo 'selected';?>><?php echo sub_str($val['account_name'],20);?></option>
				<?php }?>
			</select>
		</form>
	</div>
	<div class="analysis_padding">
		<div class="analysis_main">
			<div class="data_condition">
				<div class="class_condition">
					<h2 class="data_condition_title">关键指标详解</h2>
					<ul class="data_class_ul">
						<li><a id="raise_num" class="chart_type current_class" href="javascript:;">净增人数</a></li>
						<li><a id="totle_num" class="chart_type" href="javascript:;">累积人数</a></li>
						<li><a id="user_range" class="chart_type" href="javascript:;">粉丝分布</a></li>
						<li><a id="sex_percent" class="chart_type" href="javascript:;">性别比例</a></li>
					</ul>
				</div>
				<div class="time_condition">
					<h2 class="data_condition_title">时间</h2>
					<ul class="data_time_ul">
						<li><a href="javascript:;" data_day="7">7天</a></li>
						<li><a href="javascript:;" data_day="15">15天</a></li>
						<li><a class="current_time" href="javascript:;" data_day="30">30天</a></li>
					</ul>
				</div>
			</div>
			<iframe id="analysis_iframe" frameborder="0" src=""></iframe>
		</div>
	</div>
</div>
</body>
</html>