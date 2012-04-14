<?php
interface DAOInterface {
	
	/*
	存储对象
	*/
	function save($entity);
	
	/*
	更新对象
	*/
	function update($entity);
	
	/*
	更新对象集
	*/
	function updates($entity,$criteria);
	
	/*
	删除对象
	*/
	function delete($entity);
	
	/*
	删除对象集
	*/
	function deletes($criteria);
	
	/*
	加载对象
	*/
	function load($object);
	
	/*
	获取列表（数据集）
	*/
	function rs($criteria);
	
	/*
	获取列表（数组）
	*/
	function ls($criteria);
	
}
?>