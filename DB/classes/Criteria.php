<?php
class Criteria {
	
	var $table;
	
	var $pager;
	
	var $view;
	
	var $joins;
	
	var $unions;
	
	var $fetches;
	
	var $fields;
	
	var $orders;
	
	var $groups;
	
	var $restrictions;
	
	var $firstResult;
	
	var $maxResults;
	
	/* 设置表名 */
	function setTable($table) {
		$this->table=$table;
	}
	
	/* 设置分页 */
	function setPager($pager) {
		$this->pager=$pager;
	}
	
	/* 设置视图 */
	function setView($view) {
		$this->view=$view;
	}
	
	/* 添加联接 */
	function addJoin($join) {
		$this->joins[]=$join;
	}
	
	/* 添加联合 */
	function addUnion($union) {
		$this->unions[]=$union;
	}
	
	/* 添加获取 */
	function addFetch($fetch) {
		$this->fetches[]=$fetch;
	}
	
	/* 添加行数 */
	function addCount($fetch) {
		$this->fetches[]="count($fetch)";
	}
	
	/* 添加最大值 */
	function addMax($fetch) {
		$this->fetches[]="max($fetch)";
	}
	
	/* 添加最小值 */
	function addMin($fetch) {
		$this->fetches[]="min($fetch)";
	}
	
	/* 添加合计 */
	function addSum($fetch) {
		$this->fetches[]="sum($fetch)";
	}
	
	/* 添加平均值 */
	function addAvg($fetch) {
		$this->fetches[]="avg($fetch)";
	}
	
	/* 添加字段 */
	function addField($field) {
		$this->fields[]=$field;
	}
	
	/* 添加排序 */
	function addOrder($order) {
		$this->orders[]=$order;
	}
	
	/* 添加分组 */
	function addGroup($group) {
		$this->groups[]=$group;
	}
	
	/* 添加约束 */
	function addRestrictions($restriction) {
		$this->restrictions[]=$restriction;
	}
	
	/* 起始记录 */
	function setFirstResult($firstResult) {
		$this->firstResult=$firstResult;
	}
	
	/* 获取条数 */
	function setMaxResults($maxResults) {
		$this->maxResults=$maxResults;
	}
	
	/* 生成字符串 */
	function str() {
		$sql=new SQL($this->table);
		$sql->criteria=$this;
		$sql->select();
		return $sql;
	}
	
}
?>