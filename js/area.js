/*
*获取当前js文件的绝对路径
*author zjq
*/
function get_absolute_path(){
	var script_obj = document.getElementsByTagName("script");
	var this_obj = script_obj[script_obj.length-1];
	var this_src = this_obj.src;
	var this_path = this_src.replace(/[^\/]+\.js$/i,'');
	var parent_path = this_path.replace(/[^\/]+[\/]?$/,'');
	return parent_path;
}
var parent_path = get_absolute_path();

//加载ajax选择省市地区的控件
function load_area_widget(widget_id,widget_options){
	//获取当前对象
	var _this = this;
	
	//定义控件对象
	var widget_ob = $("#"+widget_id).css({"display":"inline"});
	
	//默认配置
	this.options = {
		'country_id':1,//当前默认为中国
		'province_id':0,
		'city_id':0,
		'district_id':0,
		'show_country':false,
		'show_province':true,
		'show_city':true,
		'show_district':true
	};
	//应用自定义配置
	if(typeof(widget_options)=='object'){
		for(var key in widget_options){
			if(widget_options.hasOwnProperty(key)){
				_this.options[key] = widget_options[key];
			}
		}
	}
	
	//加载数据(参数：元素id，父级id，当前id)
	this.load_data = function(element_id,parent_id,this_id){
		var ajax_url = parent_path+'action.php?act=get_areas';
		var this_obj = $("#"+element_id);//当前操作的下拉列表元素
		if(element_id=='country_id'){
			this_obj.empty().append('<option value="0">请选择国家</option>');
			$("#province_id").empty().append('<option value="0">请选择省份</option>').hide();
			$("#city_id").empty().append('<option value="0">请选择城市</option>').hide();
			$("#district_id").empty().append('<option value="0">请选择地区</option>').hide();
		}else if(element_id=='province_id'){
			this_obj.empty().append('<option value="0">请选择省份</option>');
			$("#city_id").empty().append('<option value="0">请选择城市</option>').hide();
			$("#district_id").empty().append('<option value="0">请选择地区</option>').hide();
			if(parent_id==0){
				this_obj.hide();
				return;
			}
		}else if(element_id=='city_id'){
			this_obj.empty().append('<option value="0">请选择城市</option>');
			$("#district_id").empty().append('<option value="0">请选择地区</option>').hide();
			if(parent_id==0){
				this_obj.hide();
				return;
			}
		}else if(element_id=='district_id'){
			this_obj.empty().append('<option value="0">请选择地区</option>');
			if(parent_id==0){
				this_obj.hide();
				return;
			}
		}
		$.ajax({
			url:ajax_url,
			type:"GET",
			data:"parent_id="+parent_id,
			dataType:"text",
			timeout:5000,
			cache:true,
			async:true,
			success:function(result){
				if(element_id=='country_id' && _this.options.country_id>0){
					result = result.replace('"'+_this.options.country_id+'"','"'+_this.options.country_id+'" selected');
				}else if(element_id=='province_id' && _this.options.province_id>0){
					result = result.replace('"'+_this.options.province_id+'"','"'+_this.options.province_id+'" selected');
				}else if(element_id=='city_id' && _this.options.city_id>0){
					result = result.replace('"'+_this.options.city_id+'"','"'+_this.options.city_id+'" selected');
				}else if(element_id=='district_id' && _this.options.district_id>0){
					result = result.replace('"'+_this.options.district_id+'"','"'+_this.options.district_id+'" selected');
				}
				this_obj.append(result).show();
			},
			error:function(){
				widget_ob.empty().append('加载省市地区数据失败！');
			}
		})
	}
	
	//生成控件
	var widget_html = '<span>地区选择：</span>';
	if(_this.options.show_country){
		widget_html += '<select id="country_id" name="country_id" style="margin:0 2px;display:none;"><option value="0">请选择国家</option></select>';
		if(_this.options.show_province){
			$("#country_id").live('change',function(){
				_this.load_data('province_id',this.value,_this.options.province_id);
			});
		}
	}
	if(_this.options.show_province){
		widget_html += '<select id="province_id" name="province_id" style="margin:0 2px;display:none;"><option value="0">请选择省份</option></select>';
		if(_this.options.show_city){
			$("#province_id").live('change',function(){
				_this.load_data('city_id',this.value,_this.options.city_id);
			});
		}
	}
	if(_this.options.show_city){
		widget_html += '<select id="city_id" name="city_id" style="margin:0 2px;display:none;"><option value="0">请选择城市</option></select>';
		if(_this.options.show_district){
			$("#city_id").live('change',function(){
				_this.load_data('district_id',this.value,_this.options.district_id);
			});
		}
	}
	if(_this.options.show_district){
		widget_html += '<select id="district_id" name="district_id" style="margin:0 2px;display:none;"><option value="0">请选择地区</option></select>';
	}
	widget_ob.empty().append(widget_html);
	
	//加载数据
	if(_this.options.show_country){
		_this.load_data('country_id',0,_this.options.country_id);
	}
	if(_this.options.show_province && _this.options.country_id>0){
		_this.load_data('province_id',_this.options.country_id,_this.options.province_id);
	}
	if(_this.options.show_city && _this.options.province_id>0){
		_this.load_data('city_id',_this.options.province_id,_this.options.city_id);
	}
	if(_this.options.show_district && _this.options.city_id>0){
		_this.load_data('district_id',_this.options.city_id,_this.options.district_id);
	}
	
	//返回操作的jquery对象
	return widget_ob;
}