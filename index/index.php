<?php
ini_set('display_errors', "ON");
error_reporting(E_ALL);
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set ('Asia/Shanghai');
session_start();
//应用程序目录
define('APP_ROOT', dirname(dirname(__FILE__))); 

require APP_ROOT . '/config/common.inc.php';
require APP_ROOT . '/lib/Log.php';
require APP_ROOT . '/lib/Utility.php'; //工具方法
require APP_ROOT . '/lib/Default_error_handler.php';
require APP_ROOT . '/lib/Dispatcher.php';
require APP_ROOT . '/lib/Controller.php';
require APP_ROOT . '/lib/Functions.php'; //一些公用的方法
require APP_ROOT . '/DB/include/common.php';

Dispatcher::setRoute('index');
Dispatcher::run();


?>