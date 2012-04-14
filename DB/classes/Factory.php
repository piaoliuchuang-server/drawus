<?php
//import("classes/Database.php");
class Factory {
	
	static function getConnection($dialect,$host,$username,$password,$database,$err) {
		return Database::connect($dialect,$host,$username,$password,$database,$err);
	}
	
}
?>