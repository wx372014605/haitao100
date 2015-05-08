<?php 
//获取公众账号平台id
$platform_id = get_string('platform_id');

//获取中国所有省份
$province_sql = "select area_name from areas where area_type=1;";
$province_arr = $mysql_handle->getRs($province_sql);
$proname_arr = array();
foreach ($province_arr as $val){
	$proname_arr[$val['area_name']] = 0;
}

//获取要显示的数据
$data_sql = "select province,count(user_id) as user_count from weixin_user_info where country='中国' and province<>''";
if($platform_id){
	$data_sql .= " and platform_id='$platform_id'";
}
$data_sql .= " group by province";
$data_arr = $mysql_handle->getRs($data_sql);
$result_arr = array();
foreach ($data_arr as $val){
	$result_arr[$val['province']] = $val['user_count'];
}

//生成结果数据
$chart_data = '';
$max_data = 0;
$result_arr = array_merge($proname_arr,$result_arr);
foreach ($result_arr as $key=>$val){
	$chart_data .= "{name:'".$key."',value:".$val."},";
	if($max_data<$val){
		$max_data = $val;
	}
}
$chart_data = preg_replace('/,$/', '', $chart_data)."\r\n";

//获取地图最大基数
$num_len = strlen($max_data);
$first_num = substr($max_data, 0, 1);
$divisor = pow(10, $num_len-1);
$max_base = ($first_num+1)*$divisor;
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
<!--Step:1 Import a module loader, such as esl.js or require.js-->
<!--Step:1 引入一个模块加载器，如esl.js或者require.js-->
<script src="../js/echarts/esl.js"></script>
</head>
<body>
<div class="content_main">
	<!--Step:2 Prepare a dom for ECharts which (must) has size (width & hight)-->
    <!--Step:2 为ECharts准备一个具备大小（宽高）的Dom-->
	<div id="chart_box"></div>
</div>
<script type="text/javascript">
	$(function(){
		//判断浏览器版本是否是ie6
		if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
			$("#chart_box").append('<div class="ie6_warning">亲，您所用的ie6浏览器暂不支持此功能，您升级您的浏览器后再重新尝试！</div>');
		}else{
			//Step:3 conifg ECharts's path, link to echarts.js from current page.
			//Step:3 为模块加载器配置echarts的路径，从当前页面链接到echarts.js，定义所需图表路径
			require.config({
				paths: {
					'echarts': '../js/echarts/echarts',
					'echarts/chart/bar': '../js/echarts/echarts-map',
					'echarts/chart/line': '../js/echarts/echarts-map',
					'echarts/chart/map': '../js/echarts/echarts-map'
				}
			});
			
			//Step:4 require echarts and use it in the callback.
			//Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
			require(['echarts', 'echarts/chart/bar', 'echarts/chart/line', 'echarts/chart/map'],
			function(ec) {
				// --- 地图 ---
				var myChart = ec.init(document.getElementById('chart_box'));
				myChart.setOption({
					title : {
				        text: '微信粉丝地域分布'
				    },
					tooltip: {
						trigger: 'item',
						backgroundColor: 'rgba(255,255,255,0.8)',
						borderColor: '#4EB853',
						borderWidth: 1,
						borderRadius: 8,
						padding: 8,
						textStyle: {color:'#000'}
					},
					dataRange: {
				        orient: 'horizontal',
				        min: 0,
				        max: <?php echo $max_base;?>,
				        text:['高','低'],
				        splitNumber:0,
				        color: ['#026FDD','#F0FFFF']
				    },
					series: [{
						name: '粉丝数',
						type: 'map',
						mapType: 'china',
						itemStyle: {
							normal: {
								borderColor: 'white',
								borderWidth: 1,
								label: {
									show: true,
									textStyle: {
		                                color: '#868ED1'
		                            }
								}
							},
							emphasis: {
								color: '#A5DBB2',
								label: {
									show: true,
									textStyle: {
		                                color: '#ffffff',
		                                fontWeight: 'bold'
		                            }
								}
							}
						},
						data: [
							<?php echo $chart_data;?>
						]
					}]
				});
			});
		}
	});
</script>
</body>
</html>