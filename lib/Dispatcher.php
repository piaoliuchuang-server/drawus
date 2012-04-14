<?php 
class Dispatcher{
	static private $route;
	
	static public function setRoute($route){
		self::$route = $route;
	} 
	
	static public function getRoute(){
		return self::route;
	}
	
	static public function run(){
			
		Dispatcher::parse_raw_post_data();
		//记录访问日志--------fangxin是否需要记日志
		//Log::Access(self::$route);
		//fangxin----DEFAULT_DO 需要改
		$do = isset($_GET['do']) ? trim($_GET['do']) : DEFAULT_DO;
		$do_arr = explode('_', $do);
		if (count ($do_arr) >=2){
			//有两层结构
			$do_act = $do_arr[1];
			$business = $do_arr[0];
			$controller_name = ucwords($do_act) . ucwords($business) . "Controller";
		}else{
			$controller_name = ucwords($do) . "Controller";
		}
		if (!class_exists($controller_name)){
			Dispatcher::halt('controller: ' .$controller_name. ' not exist');
		}
		$controller  =  new $controller_name;
		if (! ($controller instanceof Controller)){
			Dispatcher::halt('controller: ' .$controller_name. ' not instanceof Controller');
		}
		
		if (!method_exists($controller, 'run')){
			Dispatcher::halt('controller: ' .$controller_name. ' lost Method: run()');
		}
		
		//fangxin-------疑问
		call_user_func(array(&$controller, 'run'));
		
	}
	
	/**
	 * 将流类型的post数据解析到$_POST数组
	 * Enter description here ...
	 */
	private function parse_raw_post_data(){
		if(!empty($HTTP_RAW_POST_DATA)){
			$post_array = explode('&', urldecode($HTTP_RAW_POST_DATA));
			foreach ($post_array as $val){
				$value = explode('=', $val);
				$_POST[$value[0]] = $value[1];
			}
		}
	}

	/**
	 * 致命错误中断中断
	 * Enter description here ...
	 * @param unknown_type $error_message
	 */
	static public function halt($error_message){
		if (DEBUG === true){
			die($error_message);
		}else{
			header('Location: /404.html');
			exit;
		}
	}
}


?>