<?php
class Union {
	
	//联合表名
	var $table;
	
	//联合类型
	var $type;
	
	//联合标准
	var $criteria;
	
	/* 构造函数 */
	function Union($criteria,$table="",$type="") {
		$this->criteria=$criteria;
		$this->table=$table;
		$this->type=$type;
	}
	
	/* 内联 */
	static function none($criteria,$table="") {
		$union=new Union($criteria,$table,"");
		return $union;
	}
	
	/* 内联 */
	static function distinct($criteria,$table="") {
		$union=new Union($criteria,$table,"DISTINCT");
		return $union;
	}
	
	/* 外联 */
	static function all($criteria,$table="") {
		$union=new Union($criteria,$table,"ALL");
		return $union;
	}
	
}
?>