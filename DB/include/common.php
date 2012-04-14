<?php
/*
 * 自动连接数据库 
 */
$stime=microtime(true);
error_reporting(E_ALL&~E_NOTICE);
set_time_limit(0);
@date_default_timezone_set('Asia/Shanghai');
header("Content-type:text/html;charset=UTF-8");
$lifeTime=24*3600;
session_set_cookie_params($lifeTime);
session_start();
define("ACCOUNT",$_SESSION["SESSION_CONSOLE"]);
define("ROOT",substr(dirname(__FILE__),0,-7));
define('ROOT_PATH', dirname(ROOT));
function import($file) {
	require(ROOT.$file);
}

import("include/config.php");
//循环引入classes下面的文件
foreach (scandir(ROOT . 'classes') as $file){
	if (substr($file, -4) === '.php'){
		import('classes/' . $file);
	}
}
import('interface/DAOInterface.php');
import("include/conn.php");//连接数据库
?>