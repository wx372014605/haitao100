/**
 * 系统通用弹窗窗口
 * @author zjq
 * @version 1.2
 */
//弹出alert窗口
function alert_window(msg_content,callback,window_title,type){
	//防止重复显示
	if(fly_window_showing){
		return;
	}else{
		fly_window_showing = true;
	}
	
	//生成窗口背景
	if(!fly_window_back){
		fly_window_back = $(fly_back_html).appendTo("body");
	}
	fly_window_back.height($(document).height());
	fly_window_back.show();
	
	//生成主窗口
	if(!fly_window_main){
		fly_window_main = $(fly_window_html).appendTo("body");
	}
	
	//窗口标题
	if(typeof(window_title)=="undefined"){
		window_title = '系统提示';
	}
	$("#fly_msg_title").text(window_title);
	
	//消息图标
	if(typeof(type)=="undefined"){
		type = 0;
	}
	switch(type){
	case 1:
		var ico_id = "fly_msg_error";
		break;
	case 2:
		var ico_id = "fly_msg_ok";
		break;
	case 3:
		var ico_id = "fly_msg_question";
		break;
	default:
		var ico_id = "fly_msg_waring";
		break;
	}
	var msg_content_html = '<div id="'+ico_id+'" class="fly_msg_ico"></div><div id="fly_msg_str">'+msg_content+'</div>';
	$("#fly_msg_content").empty().append(msg_content_html);
	
	//添加确定按钮
	var fly_alert_button = $('<div id="fly_alert_button" class="fly_button">确定</div>');
	fly_window_main.find("#fly_button_bar").empty().append(fly_alert_button);
	
	
	//绑定事件
	fly_alert_button.unbind("click");
	fly_alert_button.click(function(){
		//关闭弹出窗口
		close_fly_window();
		//执行回调事件
		if(typeof(callback)!="undefined"){
			callback();
		}
	});
	
	//显示窗口
	var fly_window_main_top = $(document).scrollTop()+parseInt(($(window).height()-fly_window_main.height())/2);
	fly_window_main.css({"top":fly_window_main_top+"px"});
	fly_window_main.show();
}

//弹出confirm窗口
function confirm_window(msg_content,callback){
	
}

//弹出说明窗口
function content_window(msg_content,window_title,callback){
	//防止重复显示
	if(fly_window_showing){
		return;
	}else{
		fly_window_showing = true;
	}
	
	//生成窗口背景
	if(!fly_window_back){
		fly_window_back = $(fly_back_html).appendTo("body");
	}
	fly_window_back.height($(document).height());
	fly_window_back.show();
	
	//生成主窗口
	if(!fly_window_main){
		fly_window_main = $(fly_window_html).appendTo("body");
	}
	
	//窗口标题
	if(typeof(window_title)=="undefined"){
		window_title = '系统提示';
	}
	$("#fly_msg_title").text(window_title);
	
	var msg_content_html = '<div class="fly_msg_intro">'+msg_content+'</div>';
	$("#fly_msg_content").empty().append(msg_content_html);
	
	
	//添加确定按钮
	var fly_alert_button = $('<div id="fly_alert_button" class="fly_button">确定</div>');
	fly_window_main.find("#fly_button_bar").empty().append(fly_alert_button);
	
	
	//绑定事件
	fly_alert_button.unbind("click");
	fly_alert_button.click(function(){
		//关闭弹出窗口
		close_fly_window();
		//执行回调事件
		if(typeof(callback)!="undefined"){
			callback();
		}
	});
	
	//显示窗口
	var fly_window_main_top = $(document).scrollTop()+parseInt(($(window).height()-fly_window_main.height())/2);
	fly_window_main.css({"top":fly_window_main_top+"px"});
	fly_window_main.show();
}

//关闭弹出窗口
function close_fly_window(){
	fly_window_back.hide();
	fly_window_main.hide();
	fly_window_showing = false;
}

//弹出loading窗口
function show_loading(){
	//防止重复显示
	if(fly_window_showing){
		return;
	}else{
		fly_window_showing = true;
	}
	
	//生成窗口背景
	if(!fly_window_back){
		fly_window_back = $(fly_back_html).appendTo("body");
	}
	fly_window_back.height($(document).height());
	fly_window_back.show();
	
	//生成loading窗口
	if(!fly_loading_main){
		fly_loading_main = $(fly_loading_html).appendTo("body");
	}
	fly_loading_main.show();
}

//关闭loading窗口
function close_loading(){
	if(fly_window_back){
		fly_window_back.hide();
	}
	if(fly_loading_main){
		fly_loading_main.hide();
	}
	fly_window_showing = false;
}

/*定义弹出窗口的html代码*/
var fly_window_back = null;//弹出窗口背景元素
var fly_window_main = null;//弹出窗口元素
var fly_loading_main = null;//loading窗口元素
var fly_window_showing = false;//窗口是否正在显示的标志
var fly_back_html = '<div id="fly_window_back"></div>';
var fly_window_html = '<div id="fly_window_main">'+
						'<div class="fly_top_bar">'+
							'<div class="fly_top_left"></div>'+
							'<div class="fly_top_right"></div>'+
							'<div class="fly_top_center">'+
								'<div id="fly_msg_title"></div>'+
								'<div id="fly_window_close"></div>'+
							'</div>'+
						'</div>'+
						'<div class="fly_center_box">'+
							'<div class="fly_center_left"></div>'+
							'<div class="fly_center_content">'+
								'<div class="fly_msg_box">'+
									'<div id="fly_msg_content" class="fly_msg_content"></div>'+
									'<div id="fly_button_bar"></div>'+
								'</div>'+
							'</div>'+
							'<div class="fly_center_right"></div>'+
						'</div>'+
						'<div class="fly_bottom_bar">'+
							'<div class="fly_bottom_left"></div>'+
							'<div class="fly_bottom_right"></div>'+
							'<div class="fly_bottom_center"></div>'+
						'</div>'+
					'</div>';

var fly_loading_html = '<div id="fly_loading_main"></div>';

//绑定关闭窗口的事件
$("#fly_window_close").live("click",close_fly_window);