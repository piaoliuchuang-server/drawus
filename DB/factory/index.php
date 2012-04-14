<?php
//ini_set('display_errors', "ON");
//error_reporting(E_ALL);

define('ROOT_APP', dirname(dirname(dirname(__FILE__))));
require (ROOT_APP . "/config/common.inc.php");
require('../include/common.php');
//import('classes/GenerateDb.php');

$gd=new GenerateDb();
$table_name=$gd->table_name();
if($_GET['action']=='make'){
	$table=$_POST['table'];
	$type=$_POST['type'];
	if($table==1){
		foreach($type as $key){
			print_r($gd->make($key));
		}
	}
	else{
		foreach($type as $key){
			if($key=='dao')
				print_r($gd->make_dao($table));
			if($key=='entity')
				print_r($gd->make_entity($table));
		}
	
	
	}
}
//print_r($table_name);
foreach($table_name as $key){
$option.="<option value=\"{$key}\">{$key}</option>";
}
echo<<<eof
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="css/css.css" rel="stylesheet" type="text/css">
</head>
<body><form action="?action=make" method="post" name="myform">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td height="50"></td></tr>
<tr><td align="center">
选择表：<select name="table"><option value="1">选择全部</option>{$option}</select>
</td></tr>
<tr><td align="center">
dao文件<input name="type[]" type="checkbox" value="dao" checked/>entity文件<input name="type[]" type="checkbox" value="entity" checked/></td></tr>
<tr><td align="center">
<input name="submit" type="submit" value="生成" />
</td></tr>
 
</table></form>
eof;
?>

</body></html>