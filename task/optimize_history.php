<?php
/**
 * 每天定时整理goods_view_history表
 * @author zjq
 * v1.0
 */
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');

//获取一星期前的时间戳
$timestamp = strtotime("-7 day");
$history_sql = "delete from goods_view_history where update_time<$timestamp";
$result = $mysql_handle->query($history_sql);
$optimize_sql = "optimize table goods_view_history";
$result = $mysql_handle->query($optimize_sql);
?>