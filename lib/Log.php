<?php 
/**
 * 日志类-------------fangxin  应该有不需要的方法
 * @author fangxin
 */

class Log{	
	/**
	 * debug日志
	 * Enter description here ...
	 * @param mix $data
	 * @param string $function
	 * @param string $class
	 */
	public static function Debug($data,$file="",$line="",$class="",$function=""){
		if (DEBUG === false){
			return false;
		}
		$log_string = self::formatData($data);
		$time = date('Y-m-d H:i:s');
		$log_string = $time . '	' . $log_string;
		if(!empty($file))
		{
			$file = Utility::getRidRootDir($file);
			$log_string .= "[FILE:".$file."]";
		}
		if(!empty($line))
		{
			$log_string .= "[LINE:".$line."]";
		}
		if(!empty($class))
		{
			$log_string .= "[CLASS:".$class."]";
		}
		if(!empty($function))
		{
			$log_string .= "[FUNCTION:".$function."]";
		}
		$log_string .= "\n";
		try{
			self::doLog($log_string, DEBUG_LOG);
		}catch (Exception $e){
			error_log(iconv('UTF-8', 'GB2312', $e->getMessage()."\n\r".$e->getTrace()), 1, 'hujy@ucweb.com');
		}
	}
	
	/**
	 * error日志
	 * Enter description here ...
	 * @param mix $data
	 */
	public static function Error($data,$file="",$line="",$class="",$function=""){
		$log_string = self::formatData($data);
		$time = date('Y-m-d H:i:s');
		$log_string = $time . '	' . $log_string;
		if(!empty($file))
		{
			$file = Utility::getRidRootDir($file);
			$log_string .= "[FILE:".$file."]";
		}
		if(!empty($line))
		{
			$log_string .= "[LINE:".$line."]";
		}
		if(!empty($class))
		{
			$log_string .= "[CLASS:".$class."]";
		}
		if(!empty($function))
		{
			$log_string .= "[FUNCTION:".$function."]";
		}
		$log_string .= "\n";
		try{
			self::doLog($log_string, ERROR_LOG);
		}catch (Exception $e){
			error_log(iconv('UTF-8', 'GB2312', $e->getMessage()."\n\r".$e->getTrace()), 1, 'hujy@ucweb.com');
		}
	}
	
	/**
	 * 其他统计日志
	 * Enter description here ...
	 * @param mix $data
	 * @param string $stat_file
	 */
	/*
	public static function Stat($data, $stat_file){
		$log_string = self::mergeStatData($data);
		//$log_string = self::formatData($data);
		$time = date('Y-m-d H:i:s');
		$log_string = 'time='. $time . "`" . $log_string . "\n";
		try{
			self::doLog($log_string, $stat_file);
		}catch (Exception $e){
			echo $e->getMessage();
			error_log(iconv('UTF-8', 'GB2312', $e->getMessage()."\n\r".$e->getTrace()), 1, 'hujy@ucweb.com');
		}
	}
	*/
	
	/**
	 * 记acess日志
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function Access($route=''){
		if(!isset($_GET['do'])) $_GET['do'] = DEFAULT_DO;
		$request = array_merge($_GET, $_POST);
		if($route) $request['route'] = $route;
		self::Stat($request, ACCESS_LOG);
	}
	
	private static function formatData($data){
		if (is_null($data))
			return " ";
		if (is_array($data) || is_object($data)){
			$data = print_r($data, 1);
		}
		//$data .= "\n";
		return $data;
	}
	
	/*
	private static function mergeStatData($data){
		static $statUcparamArr = array();
		if (empty($statUcparamArr)){
			$statUcparamArr = Uccparam('guid,sn,ve,fr,pf,bi,ip');
		}
		if(is_object($data)) $data = get_object_vars($data);
		if (is_array($data)){
			return Utility::arr2kv(array_merge($statUcparamArr, $data));
		}else{
			return Utility::arr2kv($statUcparamArr) . "`" . $data;
		}
	}
	*/
	private static function doLog($log_string, $file){
		if(empty($log_string))
			return false;

		if(!is_writable(LOG_PATH)){
			throw new Exception( LOG_PATH . '日志目录没有写权限');
		}
		if (!file_exists(dirname($file)))
		{
			Log::makeDir(dirname($file));
		}
		$fp = fopen($file, "a+");
		if (!$fp){
			throw new Exception('打不开日志文件:' . $file);
		}
		if (flock($fp, LOCK_EX)){
			fputs($fp, $log_string);
			flock($fp, LOCK_UN);
		}else{
			throw new Exception('锁文件失败:' . $file);
		}
		fclose($fp);
		
	}
	
	private static function makeDir($path){		
		if (!file_exists($path))
		{
			Log::makeDir(dirname($path));
			mkdir($path, 0775);
		}
	}
}

?>