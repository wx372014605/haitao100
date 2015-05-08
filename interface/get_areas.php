<?php
//获取parent_id
$parent_id = get_number('parent_id');

//获取数据
$area_sql = "select area_id,area_name from areas where parent_id=$parent_id";
$area_arr = $mysql_handle->getRs($area_sql);

$option_str = '';
foreach ($area_arr as $val){
	$area_id = $val['area_id'];
	$area_name = $val['area_name'];
	$option_str .= '<option value="'.$area_id.'">'.$area_name.'</option>';
}
echo $option_str;
?>