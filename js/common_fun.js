//去除字符串首尾空格
function trim(str){
	return str.replace(/(^\s*)|(\s*$)/g,"");
}

//检测文件类型是否正确
function is_file(file_name,allow_type){
	if(typeof(allow_type)=='object'){
		if(typeof(file_name)=='undefined')return false;
		var pos = file_name.lastIndexOf(".");
		var extend_name = file_name.slice(pos+1).toLowerCase();
		var arr_length = allow_type.length;
		if(arr_length==0){
			return true;
		}else{
			for(var i=0;i<arr_length;i++){
				if(allow_type[i]==extend_name){
					return true;
				}
			}
			return false;
		}
	}else{
		return true;
	}
}

//检测是否是图片类型
function is_img(file_name){
	if(typeof(file_name)=='undefined')return false;
	var img_type_arr = Array('jpg','jpeg','gif','png','bmp');
	var pos = file_name.lastIndexOf(".");
	var extend_name = file_name.slice(pos+1).toLowerCase();
	for(var i=0,len=img_type_arr.length;i<len;i++){
		if(img_type_arr[i]==extend_name){
			return true;
		}
	}
	return false;
}

//图片onerror事件
function img_error(img_element){
	img_element.src = 'http://localhost/haitao100/images/nopic.png';
	img_element.onerror = null;//防止死循环
}

//对Date的扩展，将 Date 转化为指定格式的String 
//月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
//年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
//例子： 
//(new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
//(new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
//author: meizz
Date.prototype.Format = function(fmt){
	var o = {
	 "M+" : this.getMonth()+1,                 //月份
	 "d+" : this.getDate(),                    //日
	 "h+" : this.getHours(),                   //小时
	 "m+" : this.getMinutes(),                 //分
	 "s+" : this.getSeconds(),                 //秒
	 "q+" : Math.floor((this.getMonth()+3)/3), //季度
	 "S"  : this.getMilliseconds()             //毫秒
	};
	if(/(y+)/.test(fmt)){
		fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
	}
	for(var k in o){
		if(new RegExp("("+ k +")").test(fmt)){
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
		}
	}
	return fmt;
}

//获取当前时间戳
function get_now_stamp(){
	var time_stamp = new Date().getTime();
	return Math.round((time_stamp/1000));
}

//时间格式转化为时间戳
function strtotime(time_str){
	time_str = time_str.replace(/-/g,"/");
	var time_stamp = new Date(time_str);
	return parseInt(time_stamp/1000);//返回以秒为单位的时间戳
}

//时间戳转化为时间
function timetostr(time_stamp){
	var time_str = new Date(parseInt(time_stamp)*1000).Format("yyyy-MM-dd hh:mm:ss");
	return time_str;
}

//描述转化为时间
function secondtotime(second){
	var hour = Math.floor(second/3600);
	if(hour<10){
		hour = '0'+hour;
	}
	var minute = Math.floor(second%3600/60);
	if(minute<10){
		minute = '0'+minute;
	}
	var second = Math.floor(second%60);
	if(second<10){
		second = '0'+second;
	}
	return hour+':'+minute+':'+second;
}

//产生min到max之间的随机数
function random(min,max){
    return Math.round(min+Math.random()*(max-min));
}