<?php
class SQL {
	
	var $table; //表名
	
	var $criteria; //规则
	
	var $fields; //字段
	
	var $fetches; //获取
	
	var $fetch_str="*"; //获取语句
	
	private $sql_str; //SQL语句
	
	/* 构造函数 */
	function __construct($table) {
		$this->table=$table;
	}
	
	/* 添加获取 */
	function fetch($fetch) {
		$this->fetches[]=fetch;
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
	function add($field,$value) {
		$this->fields[]=array($field=>$value);
	}
	
	/* 包装字符串 */
	static function str($value) 
	{
		if(is_string($value))
		{ 
			if(get_magic_quotes_gpc)
			{
				$value = stripslashes($value);
			}
			$value = mysql_real_escape_string($value);
			$value="'$value'";
		}
		return $value;
	}
	
	/* 添加 */
	function insert() {
	
		$field_str="";
		$value_str="";
		foreach($this->fields as $field) {
			foreach($field as $key=>$value) {
				if(isset($value)) {
					if($field_str<>"") $field_str.=",";
					$field_str.=$key;
					if($value_str<>"") $value_str.=",";
					$value_str.=$this->str($value);
				}
			}
		}
		
		$this->sql_str="INSERT INTO $this->table($field_str) VALUES($value_str)";
	}
	
	/* 更新 */
	function update() {
		
		$update_str="";
		foreach($this->fields as $field) {
			foreach($field as $key=>$value) {
				if(isset($value)) {
					if($update_str<>"") $update_str.=",";
					$update_str.=$key."=".$this->str($value);
				}
			}
		}
		
		$this->sql_str="UPDATE $this->table SET $update_str";
		$this->doRestriction();
	}
	
	/* 删除 */
	function delete() {
		
		$this->sql_str="DELETE FROM $this->table";
		$this->doRestriction();
	}
	
	/* 查询 */
	function select() {
		
		//处理获取
		$this->doFetch();
		
		//处理联接
		$this->doJoin();
		
		//处理约束
		$this->doRestriction();
		
		//处理分组
		$this->doGroup();
		
		//处理排序
		$this->doOrder();
		
		//处理联合
		$this->doUnion();
		
		//处理游标
		$this->doCursor();
	}
	
	/* 处理联合 */
	function doUnion() {
		
		if(isset($this->criteria->unions)) {
			foreach($this->criteria->unions as $union) {
				
				if($union->table=="") $union->table=$this->table;
				$sql=new SQL($union->table);
				$sql->criteria=$union->criteria;
				$sql->select();
				
				$this->sql_str.=" UNION ".$union->type." ".$sql;
			}
		}
	}
	
	/* 处理联接 */
	function doJoin() {
		
		if(isset($this->criteria->joins)) {
			foreach($this->criteria->joins as $join) {
				$this->sql_str.=" ".$join->type." JOIN ".$join->table;
				if (strpos($join->table,' ')){
					$tmp_arr = split(' ',$join->table);
					$tmp_t = $tmp_arr[count($tmp_arr)-1];
					$this->sql_str.=" ON $this->table.".$join->key1."=".$tmp_t.".".$join->key2;
				}else{
					$this->sql_str.=" ON $this->table.".$join->key1."=".$join->table.".".$join->key2;
				}
			}
		}
	}
	
	/* 处理获取 */
	function doFetch() {
		
		if(isset($this->criteria->fetches)) {
			$fetch_str="";
			foreach($this->criteria->fetches as $fetch) {
				if($fetch_str<>"") $fetch_str.=",";
				$fetch_str.=$fetch;
			}
			if($fetch_str!="") $this->fetch_str=$fetch_str;
		}
		$this->sql_str="SELECT $this->fetch_str FROM $this->table";
	}
	
	/* 处理字段 */
	function doField() {
		
		if(isset($this->criteria->fields)) {
			$field_str="";
			foreach($this->criteria->fields as $field) {
				if($field_str<>"") $field_str.=",";
				$field_str.=$field;
			}
			if($field_str!="") $this->field_str=$field_str;
		}
	}
	
	/* 处理分组 */
	function doGroup() {
		
		if(isset($this->criteria->groups)) {
			$group_str="";
			foreach($this->criteria->groups as $group) {
				if($group_str<>"") $group_str.=",";
				$group_str.=$group->field;
			}
			$this->sql_str.=" GROUP BY ".$group_str;
		}
	}
	
	/* 处理排序 */
	function doOrder() {
		
		if(isset($this->criteria->orders)) {
			$order_str="";
			foreach($this->criteria->orders as $order) {
				if($order_str<>"") $order_str.=",";
				$order_str.=$order->field." ".$order->type;
			}
			$this->sql_str.=" ORDER BY ".$order_str;
		}
	}
	
	/* 处理游标 */
	function doCursor() {
		
		//处理分页
		if(isset($this->criteria->pager)) {
			
			$page_size=$this->criteria->pager->getSize();
			$page_param=$this->criteria->pager->getParam();
			$page_value=$this->criteria->pager->getValue();
//			echo $row_count."111\n";
//			echo $page_size."222\n";
			$row_count=DAO::getCount($this->sql_str); //总记录数
			$total_page=(int)($row_count/$page_size); //总页数
			if($row_count%$page_size!=0) $total_page++;
			
			if(isset($_REQUEST[$page_param])||isset($page_value)) { //当前页码
				if(isset($page_value)) {
					$current_page=(int)$page_value;
				} else {
					$current_page=(int)$_REQUEST[$page_param];
				}
				if(!is_integer($current_page)||$current_page<=0) {
					$current_page=1;
				} elseif($current_page>$total_page) {
					$current_page=$total_page;
				}
			} else {
				$current_page=1;
			}
			
			$firstResult=$page_size*($current_page-1); //起始记录
			
			$this->criteria->pager->totalnum=$row_count;
			$this->criteria->pager->totalpage=$total_page;
			$this->criteria->pager->curpage=$current_page;
			
			//显示分页
			/*if(isset($this->criteria->view)) {
				$this->criteria->view->assign($this->criteria->pager->name."_count",$row_count);
				if($this->criteria->pager->display) {
					$this->criteria->view->assign($this->criteria->pager->name."_current",$currentPage);
					$this->criteria->view->assign($this->criteria->pager->name."_total",$totalPage);
					$this->criteria->view->assign($this->criteria->pager->name."_param",$this->criteria->pager->param);
				}
			}*/
			
			$this->sql_str.=" LIMIT ".$firstResult.",".$page_size;
		} else {
			if(isset($this->criteria->firstResult)&&isset($this->criteria->maxResults)) {
				$this->sql_str.=" LIMIT ".$this->criteria->firstResult.",".$this->criteria->maxResults;
			} elseif(isset($this->criteria->maxResults)) {
				$this->sql_str.=" LIMIT ".$this->criteria->maxResults;
			}
		}
	}
	
	/* 处理约束 */
	function doRestriction() {
		
		if(isset($this->criteria->restrictions)) {
			$restriction_str="";
			$j=count($this->criteria->restrictions);
			for($i=0;$i<$j;$i++) {
				$restriction_str.=$this->criteria->restrictions[$i];
				if($i!=$j-1) $restriction_str.=" AND ";
			}
			$this->sql_str.=" WHERE ".$restriction_str;
		}
	}
	
	/* 生成SQL语句 */
	function __toString() {
		//echo($this->sql_str."<br/>");
		return $this->sql_str;
	}
	
}
?>