<?php
class Group {
	
	var $field;
	
	/* 构造函数 */
	function Group($field) {
		$this->field=$field;
	}
	
	static function by($field) {
		$group=new Group($field);
		return $group;
	}
	
}
?>