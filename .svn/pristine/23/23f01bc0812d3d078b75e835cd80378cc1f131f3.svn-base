<?php
//获取回调url地址
$callback_url = post_string('callback_url', 0);

//获取帐号id
$account_id = post_number('account_id');
if($account_id==0){
	admin_tip(0, '获取帐号id失败！');
}

//获取帐号信息
$account_sql = "select platform_id from weixin_account where account_id=$account_id limit 1";
$account_arr = $mysql_handle->getRow($account_sql);
if($account_sql==false){
	admin_tip(0, '获取帐号信息失败！');
}else{
	if(!is_super() and $admin_id!=$account_arr['admin_id']){
		admin_tip(0, '非法操作！');
	}
}

//获取自定义菜单内容
$menu_data = isset($_POST['menu_data'])?strip_slashes($_POST['menu_data']):'';
if($menu_data==''){
	admin_tip(0, '获取自定义菜单内容失败！');
}
$data_obj = @json_decode($menu_data);
if($data_obj==false){
	admin_tip(0, 'json数据转换出错，请检查自定义菜单json是否正确！');
}else{
	$data = '{"button":[';//自定义菜单data
	$button_list = $data_obj->menu->button;//父菜单列表
	foreach ($button_list as $parent_button){
		$data .= '{';
		
		$parent_button_name = $parent_button->name;//父菜单名称
		$data .= '"name":"'.$parent_button_name.'",';
		
		$sub_button_list = $parent_button->sub_button;//子菜单列表
		$data .= '"sub_button":[';
		
		foreach ($sub_button_list as $sub_button){
			$data .= '{';
			
			$sub_button_type = $sub_button->type;//子菜单类型
			$data .= '"type":"'.$sub_button_type.'",';
			
			$sub_button_name = $sub_button->name;//子菜单名称
			$data .= '"name":"'.$sub_button_name.'",';
			
			if($sub_button_type=='click'){
				$sub_button_key = $sub_button->key;//子菜单KEY值
				$data .= '"key":"'.$sub_button_key.'"';
			}else if($sub_button_type=='view'){
				$sub_button_url = $sub_button->url;//子菜单URL值
				$data .= '"url":"'.$sub_button_url.'"';
			}
			
			$data .= '},';
		}
		$data = preg_replace("/,$/", "", $data);
		$data .= ']},';
	}
	$data = preg_replace("/,$/", "", $data);
	$data .= ']}';
}

//修改自定义菜单
$platform_id = $account_arr['platform_id'];
$result = $weixin_handle->set_menu($platform_id, $data);
if($result){
	admin_tip(1,"修改自定义菜单成功！",$callback_url);
}else{
	$error_info = $weixin_handle->get_error_info();
	admin_tip(0,"修改自定义菜单失败，".$error_info['error_info']);
}
?>