<?php
class Join {
	
	//联接表名
	var $table;
	
	//联接类型
	var $type;
	
	//原表键名
	var $key1;
	
	//联接表键名
	var $key2;
	
	/* 构造函数 */
	function Join($table,$type,$key1,$key2) {
		$this->table=$table;
		$this->type=$type;
		$this->key1=$key1;
		$this->key2=$key2;
	}
	
	/* 内联 */
	static function inner($table,$type,$key1,$key2) {
		$join=new Join($table,$type." INNER",$key1,$key2);
		return $join;
	}
	
	/* 外联 */
	static function outer($table,$type,$key1,$key2) {
		$join=new Join($table,$type." OUTER",$key1,$key2);
		return $join;
	}
	
	/* 左外联 */
	static function left($table,$type,$key1,$key2) {
		$join=new Join($table,$type."LEFT OUTER",$key1,$key2);
		return $join;
	}
	
	/* 左外联 */
	static function right($table,$type,$key1,$key2) {
		$join=new Join($table,$type."RIGHT OUTER",$key1,$key2);
		return $join;
	}
}
?>