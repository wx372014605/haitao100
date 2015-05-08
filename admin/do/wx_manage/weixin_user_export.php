<?php
//获取公众账号平台id
$platform_id = get_string('platform_id');

//获取微信昵称
$nickname = get_string('nickname');

//获取性别
$sex = get_number('sex');

//获取省份
$province_id = get_number('province_id');

//获取城市
$city_id = get_number('city_id');

//获取要导出的数据
$data_sql = "select account.account_name,uf.open_id,uf.nickname,if(uf.sex=1,'男',if(uf.sex=2,'女','未知')) as sex,uf.country,uf.province,uf.city,uf.subscribe_time from weixin_user_info as uf 
left join weixin_account as account on uf.platform_id=account.platform_id where 1";
if($platform_id!=''){
	$data_sql .= " and uf.platform_id='$platform_id'";
}
if($nickname!=''){
	$data_sql .= " and uf.nickname like '%$nickname%'";
}
if($sex>0){
	$data_sql .= " and uf.sex=$sex";
}
if($city_id>0){//优先判断城市
	$city_sql = "select area_name from areas where area_id=$city_id limit 1";
	$city_arr = $mysql_handle->getRow($city_sql);
	if($city_arr){
		$city_name = str_replace('市', '', $city_arr['area_name']);
		$data_sql .= " and uf.city='$city_name'";
	}
}else if($province_id>0){
	$province_sql = "select area_name from areas where area_id=$province_id limit 1";
	$province_arr = $mysql_handle->getRow($province_sql);
	if($province_arr){
		$province_name = str_replace('省', '', $province_arr['area_name']);
		$data_sql .= " and uf.province='$province_name'";
	}
}
$data_arr = $mysql_handle->getRs($data_sql);

//插入标题
array_unshift($data_arr, array(
	"account_name"	=>	"所属平台",
	"open_id"		=>	"open_id",
	"nickname"		=>	"微信昵称",
	"sex"			=>	"性别",
	"country"		=>	"国家",
	"province"		=>	"省份",
	"city"			=>	"城市",
	"subscribe_time"=>	"关注时间"
));

//导出excel
export_excel($data_arr);
?>