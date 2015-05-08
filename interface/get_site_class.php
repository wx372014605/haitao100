<?php
//获取海淘网站分类
$class_sql = "select * from site_class";
$class_arr = $mysql_handle->getRs($class_sql);
ajax_return(1, '获取海淘网站分类成功！', $class_arr);
?>