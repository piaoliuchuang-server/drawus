<?php
class Database {
	
	/* 连接数据库 */
	static function connect($dialect,$host,$username,$password,$database,$err) {
		$e="";
		$e=$err["e"];
		$ec=$err["ec"];
		$ed=$err["ed"];
		if(!isset($ec)) $ec=$e;
		if(!isset($ed)) $ed=$e;
		$conn=mysql_connect($host,$username,$password) or die($ec);
		mysql_select_db($database,$conn) or die($ed);
		return $conn;
	}
	
	/* 数据库方言 */
	function dialect($dialect) {
		
	}
	
}
?>