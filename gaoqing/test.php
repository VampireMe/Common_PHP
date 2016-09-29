<?php

set_error_handler("error_handler", E_ALL);

function error_handler( $errno, $errstr, $errfile, $errline) {
	$error_no = array(
			2 => 'E_WARNING', 				//非致命的 run-time 错误。不暂停脚本执行。
			8 => 'E_NOTICE',  				//Run-time 通知。脚本发现可能有错误发生，但也可能在脚本正常运行时发生。
			256 => 'E_USER_ERROR', 			//致命的用户生成的错误。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_ERROR。
			512 => 'E_USER_WARNING', 		//非致命的用户生成的警告。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_WARNING。
			1024 => 'E_USER_NOTICE', 		//用户生成的通知。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_NOTICE。
			4096 => 'E_RECOVERABLE_ERROR',  //可捕获的致命错误。类似 E_ERROR，但可被用户定义的处理程序捕获。(参见 set_error_handler())
			8191 => 'E_ALL', 				//所有错误和警告，除级别 E_STRICT 以外。（在 PHP 6.0，E_STRICT 是 E_ALL 的一部分）
	);
	
	$message .= "错误级别是：" . $error_no[$errno]. PHP_EOL;
	$message .=  "异常信息是：". $errstr. PHP_EOL;
	$message .= "当前异常发生在: ". $errfile. " 中的第 ". $errline. " 行". PHP_EOL;
	
	throw new Exception($message);
	
}

//常见的错误编号，对应的起错误名称
$error_no = array(
		2 => 'E_WARNING', 				//非致命的 run-time 错误。不暂停脚本执行。
		8 => 'E_NOTICE',  				//Run-time 通知。脚本发现可能有错误发生，但也可能在脚本正常运行时发生。
		256 => 'E_USER_ERROR', 			//致命的用户生成的错误。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_ERROR。
		512 => 'E_USER_WARNING', 		//非致命的用户生成的警告。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_WARNING。
		1024 => 'E_USER_NOTICE', 		//用户生成的通知。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_NOTICE。
		4096 => 'E_RECOVERABLE_ERROR',  //可捕获的致命错误。类似 E_ERROR，但可被用户定义的处理程序捕获。(参见 set_error_handler())
		8191 => 'E_ALL', 				//所有错误和警告，除级别 E_STRICT 以外。（在 PHP 6.0，E_STRICT 是 E_ALL 的一部分）
);

try {
	echo 1/0 . PHP_EOL;
}catch (Exception $e){
	echo $e->getMessage();
}


echo "aaaa";



?>