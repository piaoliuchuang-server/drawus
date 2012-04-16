<?php
/*
=== 数据库配�DB) ===
*/
//数据库类�
define("DB_DIALECT","mysql");
//数据库服务器地址      //注：部署到测试服务器时，IP需要改�27.0.0.1
define("DB_HOST","127.0.0.1");
//数据库用户名
define("DB_USER","root");
//数据库密�
define("DB_PASS","drawus");
//数据库名�
define("DB_NAME","drawus");


/*
 *=====日志配置========
 */
define('LOG_PATH', APP_ROOT . "/log/");
//是否开启debug
define('DEBUG', true);
//debug日志
define("DEBUG_LOG", LOG_PATH . "debug/" . date('Y-m-d'));
//error日志
define("ERROR_LOG", LOG_PATH . "error/" . date('Y-m-d'));
//日常访问统计日志
define('ACCESS_LOG', LOG_PATH . "access/" . date('Y-m-d'));

//默认do
define('DEFAULT_DO', 'mysql_search');

/*
=== 系统配置 ===
*/
//是否为正式部署，是为TRUE,否为FALSE   
//===注：为TRUE时，error_reporting()将会失效===
define('IS_RELEASE', FALSE);


/*
 * ==== 游戏参数 ====
 */

 //给用户的初始分数
 define("NEW_USER_SCORE","2");
 //每天注册用户的上限
 define("NEW_USER_MAX_DAILY","50");
?>