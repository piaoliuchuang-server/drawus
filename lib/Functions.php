<?php
/**
 * 获取uccpara参数
 * Enter description here ...fangxin-------有用么？
 * @param mix $name
*/
function Uccparam($name){
	static $uccparamArr = array();
	if(empty($uccparamArr)){
		$uccparamArr = Utility::getUCParamArr();
	}
	if(is_string($name)){
		$name = explode(',', $name);
	}
	$returnArr = array();
	foreach($name as $n){
		if(isset($uccparamArr[$n])){
			$returnArr[$n] = $uccparamArr[$n];
		}else{
			$returnArr[$n] = '';
		}
	}
	if(count($returnArr) === 1){
		return array_pop($returnArr);
	}else{
		return $returnArr;
	}
	
}


/**
 * 自动加载类
 * Enter description here ...
 * @param unknown_type $classname
 */
function __autoload($classname){
	if(class_exists($classname)){
		return;
	}
	if (substr($classname, -3) === 'DAO'){
		$class_file_path = APP_ROOT . "/" . "dao" . "/" . $classname . ".php";
	}elseif (preg_match('/^[A-Z][a-z_A-Z]+[a-z]$/', $classname)){//大写开头,小写结束,中间允许_
		preg_match_all('/([A-Z][a-z_]+)/', $classname, $matches);
		if($matches){
			$matches = $matches[0];
			$matches_size = count($matches);
			if($matches_size === 1){ //entity
				$class_file_path = APP_ROOT . "/" . "entity" . "/" . $classname . ".php";
			}else{
				$class_file_path = APP_ROOT . "/" ;
				krsort($matches);
				$matches = array_values($matches);
				for($i=0;$i<$matches_size-1;$i++){
					$class_file_path .= strtolower($matches[$i]) . "/";
				}
				$class_file_path .= $classname . ".php";
				Log::Error('$class_file_path12: '. $class_file_path, __FILE__, __LINE__,__CLASS__, '__autoload()');
			}
		}
	}else{
		Log::Error('class : ' .$classname. '命名不规范', __FILE__, __LINE__, '__autoload()');
		if(DEBUG === true){		
			die('class : ' .$classname. '命名不规范');
		}else{
			return;
		}
		
	}
	
	if($class_file_path){
		if (!file_exists($class_file_path)){
			Log::Error('class 文件: '.$class_file_path.'不存在', __FILE__, __LINE__, '__autoload()');
			if(DEBUG === true){
				die('class 文件: '.$class_file_path.'不存在');
			}else{
				return;
			}
		}
		require $class_file_path;
	}
}
