//点击链接出现confirm弹出窗口
function href_confirm(obj,msg){
	layer.confirm(msg,function(index){
		layer.close(index);
		if(document.all){
			var referer_link = $('<a href="'+obj.attr('href')+'"></a>');
			referer_link.appendTo("body");
			referer_link[0].click();
		}else{
			location.href = obj.attr('href');
		}
	});
	return false;
}

//点击按钮出现confirm弹出窗口
function button_confirm(msg,callback){
	layer.confirm(msg,function(index){
		layer.close(index);
		if(typeof(callback)!='undefined'){
			callback();
		}
	});
	return false;
}

//layer的tip弹出窗口
function layer_tip(ob,msg,layer_options){
	var default_options = {
		guide: 1,
		time: 3,
		style: ['background-color:#F26C4F; color:#fff', '#F26C4F'],
		maxWidth:240
	};
	//应用自定义配置
	if(typeof(layer_options)=='object'){
		for(var key in layer_options){
			if(layer_options.hasOwnProperty(key)){
				default_options[key] = layer_options[key];
			}
		}
	}
	$("html,body").animate({scrollTop:ob.offset().top-100},500,function(){
		layer.tips(msg, ob.get(0), default_options);
	});
}

//layer的alert弹出窗口
function layer_alert(msg){
	layer.alert(msg,17);
}

//layer的iframe层
function layer_iframe(title,iframe_src,layer_options){
	//获取当前对象
	var _this = this;
	
	//默认配置
	this.options = {
		'callback' : null,
		'width'	: 900,
		'height' : $(window).height()-100
	};
	//应用自定义配置
	if(typeof(layer_options)=='object'){
		for(var key in layer_options){
			if(layer_options.hasOwnProperty(key)){
				_this.options[key] = layer_options[key];
			}
		}
	}
	//显示弹出层
	$.layer({
		type: 2,
		shade: [0],
		fix: false,
		title: title,
		offset: ['40px',''],
		iframe: {src : iframe_src},
		area: [_this.options.width+'px' , _this.options.height+'px'],
		close: function(index){
			layer.close(index); //执行关闭
			if(_this.options.callback){
				_this.options.callback();
			}
		}
	});
}