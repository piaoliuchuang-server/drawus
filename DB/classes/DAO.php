<?php
//import("classes/SQL.php");
//import("classes/Pager.php");
//import("classes/Order.php");
//import("classes/Group.php");
//import("classes/Join.php");
//import("classes/Union.php");
//import("classes/Restrictions.php");
//import("classes/Criteria.php");

class DAO {
	
	function __construct() {
		mysql_query("set names utf8");
	}
	
	/*
	�?后插入记录ID�?
	*/
	function lastId() {
		return mysql_result(@mysql_query("select last_insert_id()"),0,0);
	}
	/*
	 * 返回上一个 MySQL 操作中的错误信息的数字编码 
	 */
	function mysqlErrno()
	{
		return mysql_errno();
	}
	//返回上一次操作的影响条数
	function affectedRows(){
		return mysql_affected_rows(); 
	}
	
	/*
	传入SQL，返回记录集�?
	*/
	function query($sql) {
	   
		//Log::Error("sql",$sql);
  	echo $sql. '<br/>';
	//file_put_contents("1.txt",$sql."\n");
		global $page;
		if(isset($page)) $page ->query+=1;
		return @mysql_query($sql);
	}
	
	/*
	判断记录集是否存在
	*/
	function exist($rs) {
		return @mysql_num_rows($rs);
	}
	
	/*
	传入记录集，返回二维数组
	*/
	function getList($rs) {
		$i=0;
		while($row=mysql_fetch_array($rs)) {
			$row[0]=$i+1;
			$list[$i]=$row;
			$i++;
		}
		if(isset($list))
			return $list;
	}
	
	/*
	获取数据列�?��??
	*/
	function field($rs) {
		return @mysql_fetch_array($rs);
	}
	
	/*
	获取总记录数
	*/
	static function getCount($sql) {
		$union = stripos($sql, ' UNION ');
		$group = stripos($sql, ' GROUP BY ');
		$order = stripos($sql, ' ORDER BY ');
		if ($order > 0) {
			$sql = substr($sql, 0, $order);
		}
		if (!$union && !$group) {
			$from  = stripos($sql, ' FROM ');
			$sql = "SELECT COUNT(0)" . substr($sql, $from, strlen($sql));
		}
		if ($union || $group) {
			$sql = "SELECT COUNT(0) FROM ($sql) TMP";
		}
		$rs=DAO::query($sql);
		$arr=DAO::field($rs);
		return $arr[0];
		
		//return mysql_numrows(@mysql_query($sql));
	}
	
	/*
	创建函数
	*/
	static function createFunction() {
$str=<<<EOD
		CREATE FUNCTION ad2(num int (10))
			RETURNS float
		begin
		declare money float;
		return money;
EOD;
		@mysql_query($str);
	}
	
	function __toString() {
		return "DAO";
	}
	
}
?>