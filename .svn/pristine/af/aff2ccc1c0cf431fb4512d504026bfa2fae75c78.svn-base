<?php
//获取要删除的帐号id
$account_id = get_number('account_id');
if($account_id==0){
	admin_tip(0, '获取帐号id失败！');
}

//获取帐号信息
$account_sql = "select account_logo from weixin_account where account_id=$account_id limit 1";
$account_arr = $mysql_handle->getRow($account_sql);
if($account_sql==false){
	admin_tip(0, '获取要删除的帐号信息失败！');
}

//修改公众帐号
$delete_sql = "delete from weixin_account where account_id=$account_id limit 1";
$result = $mysql_handle->query($delete_sql);
if($result){
	//删除帐号logo图片
	$account_logo = str_replace(WEB_URL, '', $account_arr['account_logo']);
	delete_file($account_logo);
	$big_logo = str_replace('_small', '', $account_logo);
	delete_file($big_logo);
	admin_tip(1, '删除公众帐号成功！');
}else{
	admin_tip(0, '删除公众帐号失败！');
}
?>