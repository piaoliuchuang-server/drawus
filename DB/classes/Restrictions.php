<?php
/*
 * @name	查询规则
 * @intro	组合SQL查询语句
 * @usage	一个比较复杂的查询例子
			$criteria->addRestrictions(Restrictions::li("title","dd"));
			$criteria->addRestrictions(
				Restrictions::a(
					array(
						Restrictions::eq("cid",2),
						Restrictions::o(
							array(
								Restrictions::li("title","abc"),
								Restrictions::li("url","abc"),
								Restrictions::a(
									array(
										Restrictions::eq("tid",3),
										Restrictions::in("title","(1,2,3,4,5)"),
										Restrictions::li("account","abc")
									)
								)
							)
						)
					)
				)
			);
			$criteria->addRestrictions(Restrictions::li("title","dd"));
*/
class Restrictions {
	
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
	
	/* 等于 */
	static function eq($field,$value) {
		return $field."=".Restrictions::str($value)." ";
	}
	
	/* 不等于 */
	static function ne($field,$value) {
		return $field."<>".Restrictions::str($value)." ";
	}
	
	/* 大于 */
	static function gt($field,$value) {
		return $field.">".Restrictions::str($value)." ";
	}
	
	/* 大于等于 */
	static function ge($field,$value) {
		return $field.">=".Restrictions::str($value)." ";
	}
	
	/* 小于 */
	static function lt($field,$value) {
		return $field."<".Restrictions::str($value)." ";
	}
	
	/* 小于等于 */
	static function le($field,$value) {
		return $field."<=".Restrictions::str($value)." ";
	}
	
	/* 是 */
	static function is($field,$value) {
		return $field." IS $value ";
//		return $field." IS ".Restrictions::str($value)." ";
	}
	
	/* 包含 */
	static function in($field,$value) {
		return $field." IN (".$value.") ";
	}
	
	/* 不包含 */
	static function ni($field,$value) {
		return $field." NOT IN (".$value.") ";
	}
	
	/* 不包含 */
	static function nn($field,$value) {
		return $field." NOT IN (".$value.") ";
	}
	
	/* 匹配 */
	static function li($field,$value,$lo="AND") {
		return $field." LIKE '%".$value."%' ";
	}
	
	/* 正则 */
	static function re($field,$value) {
		return $field." REGEXP ".Restrictions::str($value)." ";
	}
	
	/* 逻辑 */
	static function lo($restrictions,$operator) {
		$str="(";
		$j=count($restrictions);
		for($i=0;$i<$j;$i++) {
			$str.=$restrictions[$i];
			if($i!=$j-1) {
				$str.=" ".$operator." ";
			}
		}
		return $str.")";
	}
	
	/* 和 */
	static function a($restrictions) {
		return Restrictions::lo($restrictions,"AND");
	}
	
	/* 或 */
	static function o($restrictions) {
		return Restrictions::lo($restrictions,"OR");
	}
	
	/* 异或 */
	static function x($restrictions) {
		return Restrictions::lo($restrictions,"XOR");
	}
	
	/* 非 */
	static function n($restrictions) {
		return Restrictions::lo($restrictions,"NOT");
	}
	
}
?>