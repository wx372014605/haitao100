<?php
//临时代码获取转运方式
$way_arr = array(
	0 => array(
		array('way_id'=>1,'way_name'=>'天马仓库'),
		array('way_id'=>2,'way_name'=>'俄勒冈免税州仓库'),
		array('way_id'=>3,'way_name'=>'顺丰仓库'),
	),
	1 => array(
		array('way_id'=>4,'way_name'=>'A渠道'),
		array('way_id'=>5,'way_name'=>'B渠道'),
		array('way_id'=>6,'way_name'=>'C渠道'),
		array('way_id'=>7,'way_name'=>'D渠道'),
		array('way_id'=>8,'way_name'=>'E渠道'),
	),
	2 => array(
		array('way_id'=>9,'way_name'=>'A渠道'),
		array('way_id'=>10,'way_name'=>'B渠道'),
		array('way_id'=>11,'way_name'=>'C渠道'),
		array('way_id'=>12,'way_name'=>'D渠道'),
	),
	3 => array(
		array('way_id'=>13,'way_name'=>'A渠道'),
		array('way_id'=>14,'way_name'=>'A渠道'),
		array('way_id'=>15,'way_name'=>'B渠道'),
		array('way_id'=>16,'way_name'=>'C渠道'),
	),
);
ajax_return(1, '获取转运仓库成功！', $way_arr);
?>