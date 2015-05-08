<?php
/**
 * 网站自定义的错误处理函数
 * @author zjq
 * @version 1.0
 */
function myErrorHandler($errno, $errstr, $errfile, $errline){
	$error_str = '';
	switch ($errno){
		case E_ERROR:
		case E_USER_ERROR:
			$error_str .= "An error has occurred on line $errline in file $errfile,";
			$error_str .= "error_no:$errno error_msg:$errstr";
			break;
		case E_USER_WARNING:
			$error_str .= "Warning:$errstr in file $errfile on line $errline";
			break;
		case E_USER_NOTICE:
			$error_str .= "Notice:$errstr in file $errfile on line $errline";
			break;
		default:
			$error_str .= "$errstr in file $errfile on line $errline";
			break;
	}
	//保存错误日志
	write_log($error_str, "error.log");
	
    /* Don't execute PHP internal error handler */
    return true;
}
set_error_handler("myErrorHandler");
?>