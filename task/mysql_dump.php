<?php
/**
 * 每天定时备份数据库
 * @author zjq
 * v1.0
 */
set_time_limit(0);//取消超时
ignore_user_abort(true);//用户关闭浏览器,PHP脚本也可以继续执行

//linux用命令行执行php文件需要用全路径
$webRoot = str_replace("\\","/",dirname(dirname(__FILE__)))."/";
//引入配置文件和相关函数
require_once ($webRoot.'includes.php');

define("BACKUP_DIR", '/www/htdocs/backup/mysql/haitao100/');
if(!is_dir(BACKUP_DIR)){
	mkdir(BACKUP_DIR, 0777, true);
}

exec("/www/wdlinux/mysql-5.1.63/bin/mysqldump -hlocalhost -pHFmysql123_2014 haitao100>'".BACKUP_DIR.get_current_time().".sql'");
?>