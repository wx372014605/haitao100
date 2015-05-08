<?php 
//获取公众账号平台id
$platform_id = get_string('platform_id');

//获取微信用户的性别比例
$sex_sql = "select sex,count(*) as data from weixin_user_info";
if($platform_id){
	$sex_sql .= " where platform_id='$platform_id'";
}
$sex_sql .= " group by sex";
$sex_arr = $mysql_handle->getRs($sex_sql);

//生成结果数据
$sex_name = array(
	"0"	=>	"未知",
	"1"	=>	"男",
	"2"	=>	"女"
);
$chart_data = '';
$result_arr = array();
$totle_num = 0;
foreach ($sex_arr as $val){
	$result_arr[$val['sex']] = $val['data'];
	$totle_num += $val['data'];
}
if($totle_num==0){
	$chart_data = "['未知',0],['男',0],['女',0]";
}else{
	foreach ($result_arr as $key=>$val){
		$chart_data .= "['".$sex_name[$key]."',".sprintf("%.2f",$val/$totle_num)."],";
	}
	$chart_data = preg_replace('/,$/', '', $chart_data);
}
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
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {enabled: false},
						showInLegend: true
					}
				},
				//设定报表对象的初始数据
				series: [
					{
						type: 'pie',
						name: '性别比例',
						data: [<?php echo $chart_data;?>] 
					}
				],
				title: {
					text: '微信粉丝性别比例',
					align: 'left',
					style: {'color':'#222222','font-size':'14px','font-family':'Microsoft YaHei'}
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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