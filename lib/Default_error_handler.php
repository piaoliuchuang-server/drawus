<?php
/**
 * 默认的错误或异常处理机制
 * 
 * @author zhugm@ucweb.com
 */


function default_Exception($exception)
{
	Log::Error("未处理的异常：" . $exception->getMessage());
	return true; 
}
//注册默认异常处理函数，处理未被catch到的异常
set_exception_handler('default_Exception');


function default_Error($errno, $errstr, $errfile, $errline)
{ 
     if ($errno === E_NOTICE || $errno === E_STRICT)
     {
		 return;
     }
      //不暴露文件绝对路径到Log中
 	 $errfile = Utility::getRidRootDir($errfile);
 	 Log::Error("未处理的错误" . $errno . "：", $errstr, $errfile, $errline); 
     /* return true, Don't execute PHP internal error handler */  
     return true;  
}
 
 /**
 * 注册默认错误处理函数
 * 
 * 注意：
 *	（1）E_ERROR、E_PARSE、E_CORE_ERROR、E_CORE_WARNING、E_COMPILE_ERROR、E_COMPILE_WARNING是不会被这个句柄处理的，也就是会用最原始的方式显示出来。不过出现这些错误都是编译或PHP内核出错，在通常情况下不会发生。
 * 	（2）使用set_error_handler()后，error_reporting()将会失效。也就是所有的错误（除上述的错误以外），都会交由以上自定义函数进行处理
 */
if (IS_RELEASE)
{//通常在正式部署时，才设置：display_errors=OFF，此时才需要注册默认错误处理函数，否则error_reporting()将会失效；
	set_error_handler("default_Error"); 
}

?>