<?php
class Order {
	
	var $field;
	
	var $type;
	
	/* 构造函数 */
	function Order($field,$type) {
		$this->field=$field;
		$this->type=$type;
	}
	
	/* 正序 */
	static function asc($field) {
		$order=new Order($field,"asc");
		return $order;
	}
	
	/* 倒序 */
	static function desc($field) {
		$order=new Order($field,"desc");
		return $order;
	}
	
}
?>