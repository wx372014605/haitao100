<?php
/**
 * 每月定时整理session表
 * @author zjq
 * v1.0
 */
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');

$optimize_sql = "optimize table session";
$result = $mysql_handle->query($optimize_sql);
?>