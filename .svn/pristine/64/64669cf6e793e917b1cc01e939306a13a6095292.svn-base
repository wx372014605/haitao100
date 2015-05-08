//ajax表单提交通用函数
function ajax(url,parameters_obj,callback,timeout){
	//ajax传递的参数
	var parameters = "";
	for(var key in parameters_obj){
		if(parameters_obj.hasOwnProperty(key)){
			parameters += key+"="+parameters_obj[key]+"&";
		}
	}
	parameters = parameters.replace(/\&$/,"");
	if(parameters==""){
		alert_window("ajax参数传递错误！");
		return false;
	}
	
	if(typeof(timeout)=="undefined"){
		timeout = 3;//默认3秒超时
	}
	
	//显示loading窗口
	show_loading();
	
	$.ajax({
		url:url,
		type:"POST",
		data:parameters,
		dataType:"json",
		timeout:timeout*1000,
		cache:false,
		async:false,
		success:function(obj){
			//返回结果object对象
			//status:错误代码(0为成功，其他都为失败)
			//message:返回的提示信息
			//attach:返回的附加信息
			
			//关闭loading窗口
			close_loading();
			
			//执行回调事件
			if(typeof(callback)!="undefined"){
				//设置延时处理
				setTimeout(function(){
					callback(obj);
				},100);
			}
			return false;
		},
		error: function(XMLHttpRequest,textStatus,errorThrown){
			//alert(XMLHttpRequest.status);
			//alert(XMLHttpRequest.readyState);
			//alert(textStatus);
			if(textStatus=="timeout"){
				//关闭loading窗口
				close_loading();
				//显示提示
				alert_window("系统超时，请重试！");
			}
		}
	})
	return false;
}