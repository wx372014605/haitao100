<?php 
//获取公众账号平台id
$platform_id = get_string('platform_id');

//获取数据类别
$data_class = get_number('data_class');

//获取数据天数
$data_day = get_number('data_day');
if($data_day<=0){
	$data_day = 30;
}
$start_date = get_current_date("-".$data_day." day");

//获取要显示的数据
if($data_class==0){
	$chart_name = '净增人数';
	$data_sql = "select data_date,sum(user_raise) as data from weixin_data_analysis where 1";
	if($platform_id!=''){
		$data_sql .= " and platform_id='$platform_id'";
	}
	$data_sql .= " and data_date>='$start_date' group by data_date";
}else{
	$chart_name = '累积人数';
	$data_sql = "select data_date,sum(user_totle) as data from weixin_data_analysis where 1";
	if($platform_id!=''){
		$data_sql .= " and platform_id='$platform_id'";
	}
	$data_sql .= " and data_date>='$start_date'  group by data_date";
}
$data_arr = $mysql_handle->getRs($data_sql);

//获取日期列表
$date_range = get_date_range($data_day);

//生成结果数据
$result_arr = array();
$min_data = 0;
foreach ($data_arr as $val){
	if(isset($result_arr[$val['data_date']])){
		$result_arr[$val['data_date']] += $val['data'];
	}else{
		$result_arr[$val['data_date']] = $val['data'];
	}
	if($val['data']<$min_data){
		$min_data = $val['data'];
	}
}
$chart_data = '';
$chart_categories = '';
foreach ($date_range as $val){
	if(isset($result_arr[$val])){
		$chart_data .= $result_arr[$val].',';
	}else{
		$chart_data .= '0,';
	}
	$chart_categories .= "'$val',";
}
$chart_data = preg_replace('/,$/', '', $chart_data);
$chart_categories = preg_replace('/,$/', '', $chart_categories);
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
<script type="text/javascript" src="../js/highcharts.js"></script>
<script type="text/javascript">
	$(function(){
		//判断浏览器版本是否是ie6
		if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
			$("#chart_box").append('<div class="ie6_warning">亲，您所用的ie6浏览器暂不支持此功能，您升级您的浏览器后再重新尝试！</div>');
		}else{
			//声明报表对象
			var chart = new Highcharts.Chart({
				chart: {
					//将报表对象渲染到层上
					renderTo: 'chart_box'
				},
				colors: [
					'#4EB853'
				],
				credits: {
					enabled: false
				},
				<?php if($data_class==1){?>
				plotOptions: {
					area: {fillColor: '#EAFFF2', fillOpacity: 0.5}
				},
				<?php }?>
				//设定报表对象的初始数据
				series: [
					{data: [<?php echo $chart_data;?>], name:'<?php echo $chart_name;?>'<?php if($data_class==1)echo ",type: 'area'";?>}
				],
				title: {
					text: '趋势图',
					align: 'left',
					style: {'color':'#222222','font-size':'14px','font-family':'Microsoft YaHei'}
				},
				tooltip: {
					crosshairs: [{
						width: 1,
						color: '#A2DEA5',
						dashStyle:"ShortDot"
					}],
					borderRadius: 8
				},
				xAxis: {
					<?php if($data_day==30){?>
					tickInterval: 3,
					<?php }?>
					categories: [<?php echo $chart_categories;?>],
					tickLength : 5
				},
				yAxis: {
					title: '',
					labels: {format:'{value}'},
					<?php if($min_data>=0){?>
					min: '0',
					<?php }?>
					gridLineColor: '#F2F3F4'
				}
			});
		}
	});
</script>
</head>
<body>
<div class="content_main">
	<div id="chart_box"></div>
</div>
</body>
</html>