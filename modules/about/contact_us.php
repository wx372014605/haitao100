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
					<li><a href="index.php?app=company_intro&menu_index=2">公司简介</a><i></i></li>
					<li class="current"><a href="index.php?app=contact_us&menu_index=2">联系我们</a><i></i></li>
				</ul>
			</div>
			<div class="about_content">
				<div id="hf_map"></div>
				<div class="contact_way">
					<p>地址：上海市长宁区虹井路855弄10号</p>	
				　　<p>查件电话：0086-21-51560666 400-880-9986</p>
				　　<p>公司总机：0086-21-51560600</p>
				　　<p>传真：0086-21-62699136 62699179</p>
				　　<p>E-mail：cs@asia-ex.com</p>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>
<script type="text/javascript">
    //创建和初始化地图函数：
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
        addMarker();//向地图中添加marker
    }
    
    //创建地图函数：
    function createMap(){
        var map = new BMap.Map("hf_map");//在百度地图容器中创建一个地图
        var point = new BMap.Point(121.374352,31.193333);//定义一个中心点坐标
        map.centerAndZoom(point,18);//设定地图的中心点和坐标并将地图显示在地图容器中
        window.map = map;//将map变量存储在全局
    }
    
    //地图事件设置函数：
    function setMapEvent(){
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
        map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard();//启用键盘上下左右键移动地图
    }
    
    //地图控件添加函数：
    function addMapControl(){
        //向地图中添加缩放控件
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
	map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
	var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
	map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
	map.addControl(ctrl_sca);
    }
    
    //标注点数组
    var markerArr = [{title:"泓丰国际货物运输代理公司",content:"地址：上海市长宁区虹井路855弄10号3楼<br/>电话：(021)51560600",point:"121.373611|31.194066",isOpen:1,icon:{w:23,h:25,l:46,t:21,x:9,lb:12}}
		 ];
    //创建marker
    function addMarker(){
        for(var i=0;i<markerArr.length;i++){
            var json = markerArr[i];
            var p0 = json.point.split("|")[0];
            var p1 = json.point.split("|")[1];
            var point = new BMap.Point(p0,p1);
			var iconImg = createIcon(json.icon);
            var marker = new BMap.Marker(point,{icon:iconImg});
			var iw = createInfoWindow(i);
			var label = new BMap.Label(json.title,{"offset":new BMap.Size(json.icon.lb-json.icon.x+10,-20)});
			marker.setLabel(label);
            map.addOverlay(marker);
            label.setStyle({
                        borderColor:"#808080",
                        color:"#333",
                        cursor:"pointer"
            });
			
			(function(){
				var index = i;
				var _iw = createInfoWindow(i);
				var _marker = marker;
				_marker.addEventListener("click",function(){
				    this.openInfoWindow(_iw);
			    });
			    _iw.addEventListener("open",function(){
				    _marker.getLabel().hide();
			    })
			    _iw.addEventListener("close",function(){
				    _marker.getLabel().show();
			    })
				label.addEventListener("click",function(){
				    _marker.openInfoWindow(_iw);
			    })
				if(!!json.isOpen){
					label.hide();
					_marker.openInfoWindow(_iw);
				}
			})()
        }
    }
    //创建InfoWindow
    function createInfoWindow(i){
        var json = markerArr[i];
        var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>"+json.content+"</div>");
        return iw;
    }
    //创建一个Icon
    function createIcon(json){
        var icon = new BMap.Icon("http://app.baidu.com/map/images/us_mk_icon.png", new BMap.Size(json.w,json.h),{imageOffset: new BMap.Size(-json.l,-json.t),infoWindowOffset:new BMap.Size(json.lb+5,1),offset:new BMap.Size(json.x,json.h)})
        return icon;
    }
    
    initMap();//创建和初始化地图
</script>

<!-- 引入网站通用底部文件 -->
<?php require 'modules/index_footer.php';?>
