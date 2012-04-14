<?php 

class Utility{
	
	/**
	 * Curl Post
	 * @param string $url
	 * @param array $post
	 * @param int $timeout
	 */
	static function curl_post($url, $post = array(), $timeout = 10) {
		global $g_handle;
		if (empty ( $g_handle ))
			$g_handle = curl_init ();
		
		$options = array (
			CURLOPT_URL => $url, 
			CURLOPT_POST => true, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_FRESH_CONNECT => false, 
			CURLOPT_FORBID_REUSE => false, 
			CURLOPT_TIMEOUT => $timeout, 
			CURLOPT_POSTFIELDS => http_build_query ( $post ) );
		if (empty ( $post ))
			$options [CURLOPT_POST] = false;
		
		curl_setopt_array ( $g_handle, $options );
		
		$ret = curl_exec ( $g_handle );
		return $ret;
	}
	
	/**
	 * Curl Get
	 * @param string $url
	 * @param int $timeout
	 */
	/*
	static function curl_get($url, $timeout = 5) {
		global $g_handle;
		if (empty ( $g_handle ))
			$g_handle = curl_init ();
		
		$options = array (
			CURLOPT_URL => $url, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_FRESH_CONNECT => false, 
			CURLOPT_FORBID_REUSE => false, 
			CURLOPT_TIMEOUT => $timeout );
		curl_setopt_array ( $g_handle, $options );
		
		$ret = curl_exec ( $g_handle );
		return $ret;
	}
	*/
	
	/**
	 * Curl Get
	 * @param string $url
	 * @param int $timeout
	 */
	static function curl_get($url, $timeout = 5) 
	{
		
		global $g_handle;
		if (empty ( $g_handle ))
			$g_handle = curl_init ();
		
		$options = array (
			CURLOPT_URL => $url, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_FRESH_CONNECT => false, 
			CURLOPT_FORBID_REUSE => false, 
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_TIMEOUT => $timeout );
		curl_setopt_array ( $g_handle, $options );
		
		$ret = curl_exec ( $g_handle );
		curl_close($g_handle);
		return $ret;
		
	}

	/**
	 * 获取get参数
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	static function get($param) {
		if($_GET[$param]){
			$pattern = array("|","&",";","$","%","@","'","\"","\\\'","\\\"","<",">","(",")","+",",","\\");
			$replace ="";
			return str_replace($pattern,$replace,$_GET[$param]);
		}
		return "";
		//return isset($_GET[$param])?$_GET[$param]:"";
	}

	/**
	 * 获取post参数
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	static function post($param) {
		return isset($_POST[$param])?$_POST[$param]:"";
	}

	/**
	 * 获取cookie
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	static function cookie($param) {
		return isset($_COOKIE[$param])?$_COOKIE[$param]:"";
	}

	/**
	 * 获取session
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	static function session($param) {
		return isset($_SESSION[$param])?$_SESSION[$param]:"";
	}

	/**
	 * 获取系统参数
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	static function server($param) {
		return isset($_SERVER[$param])? $_SERVER[$param]:"";
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	static function  wapUrl($url){
		$url=str_replace("&amp;","&",$url);
		$url=str_replace("&","&amp;",$url);
		return $url;
	}

	/**
	 * 输出网页内容类型
	 * Enter description here ...
	 * @param string $type
	 */
	static function getContentType($type) {
		$type=strtolower($type);
		$acceptHeader = $_SERVER['HTTP_ACCEPT'];
		if (stripos($acceptHeader, 'application/vnd.wap.xhtml+xml')!==false){
			header('Content-type: application/vnd.wap.xhtml+xml;charset=UTF-8');
		}elseif(stripos($acceptHeader, 'application/xhtml+xml')!==false){
			header('Content-type: application/xhtml+xml;charset=UTF-8');
		}else{
			header('Content-type: text/html;charset=UTF-8');
		}
		switch($type) {
			case "xhtml-mobile10":
				echo("<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n");
				break;
		}
	}

	/**
	 * 将键值对parse成数�
	 * Enter description here ...
	 * @param string $s 格式aaa=123`bbb=234
	 */
	static function kv2arr($s="") {
		$a=explode("`",$s);
		$d=array();
		foreach($a as $b) {
			$c=explode("=",$b);
			if(sizeof($c)>=2) {
				$d[strtolower($c[0])]=$c[1];
			}
		}
		return $d;
	}
	
	/**
	 * 数组parse成键值对字符�
	 * Enter description here ...
	 * @param array $arr
	 */
	static function arr2kv($arr=array()){
		$strArr = array();
		foreach($arr as $key => $v) {
			$strArr[]=$key ."=" . $v;
		}
		return implode('`', $strArr);
	}

	/**
	 * 从arr数组中获取参数�
	 * Enter description here ...
	 * @param string $ps
	 * @param array $arr
	 * @return string
	 */
	static function get_param($ps,$arr) {
		$ts="";$tc=0;
		$params=explode("&",$ps);
		foreach($params as $param) {
			if($param!="") {
				$set=explode("=",$param);
				if(sizeof($set)>1) {
					if($tc!=0) $ts.="&";
					$ts.=$set[0]."=".$arr[$set[1]];
					$tc++;
				}
			}
		}
		return $ts;
	}
	
	/**
	 * 获取UC Web客户端参数
	 * 
	 * 主流程：
	 * 		1.从url参数uc_param_str中获取需要的参数
	 * 		2.将且仅将uc_param_str中未能获取到的参数，依次以http head 和 cookie方式重新获取
	 * 		3.以上方式都不能获取到客户端唯一标志(dn 和 sn)， 则生成一个GUID并保存到cookie,做为用户标识
	 * 
	 * 客户端参数说明：
	 * 		bid  --- 品牌ID;
	 *		fr   --- 客户端平台;
	 *		ver  --- UCMobile版本;
	 *		pfid --- 专版ID;
	 *		ip   --- 客户端iP;
	 *		ni   --- 用户唯一标识sn; 8.3后新扩展的uc_param_str参数，需要M9解密
	 *		dn   --- 用户唯一标识; 仅通过uc_param_str传递
	 *		sn   --- 用户唯一标识; 仅通过uccpara传递
	 */
	static function getUCParamArr()
	{
		$param_arr = array('bid'=>'', 'fr' =>'', 'ver'=>'', 'pfid'=>'','ip'=>'');
		$uid = ''; //本系统用户标志
		$local_sn = ''; //客户端标志 = sn 或  dn
					
		//============Begin: 处理uc_param_str
		//处理$uid
		$ni = Utility::get('ni');
		if($ni) 
		{
			//$ni = urldecode($ni);//去掉，否则会不稳定-时好时坏
			$ni = base64_decode($ni);
			$ni = m9_decode($ni);
			Log::Debug("UcParamStr 的 ni参数=" . $ni, __FILE__ ,__LINE__,__CLASS__,__METHOD__);
			$tmp_arr=explode("-",$ni);
			if(is_array($tmp_arr) && count($tmp_arr)> 1 ) 
			{
				$uid = $tmp_arr[1];
				$local_sn = $ni;
			}
		}
		$dn = Utility::get('dn');
		if(!$uid && $dn) 
		{
			$tmp_arr=explode("-",$dn);
			if(is_array($tmp_arr) && count($tmp_arr)> 0 ) 
			{
				$uid = $tmp_arr[0];
				$local_sn = $dn;
			}
		}
		//处理uid以外的参数
		$un_get_keys = array();//保存未获取到值的参数名
		$param_keys = array_keys($param_arr);
		foreach ($param_keys as $param_k)
		{	
			//uc_param_str的参数名只取前面两位
			$key_for_get = $param_k;
			if(strlen($key_for_get) > 2)
			{
				$key_for_get = substr($key_for_get, 0, 2);
			}
			$get_value = Utility::get($key_for_get);
			if(!$get_value)
			{
				array_push($un_get_keys, $param_k);
			}
			$param_arr[$param_k] = $get_value;
		}
		//============End: 处理uc_param_str
		
		//============Begin: 处理uc_param_str未获取到的参数
		if(count($un_get_keys) > 0)
		{
			Log::Debug("通过uc_prams_str未能获取到的参数名=" . implode(",", $un_get_keys), __FILE__ ,__LINE__,__CLASS__,__METHOD__);
			
			$uccpara = false;
			$uccpara = $_SERVER['HTTP_UCCPARA'];
			if(!$uccpara)
			{
				$uccpara = base64_decode($_COOKIE['uccpara']);
			}
			if($uccpara)
			{
				Log::Debug("通过Http head 或 cookie获取到的客户端参数=" . print_r($uccpara, true), __FILE__ ,__LINE__,__CLASS__,__METHOD__);
				$uccpara_arr = Utility::kv2arr($uccpara);
				$sn = $uccpara_arr["sn"];
				//处理$uid
				if(!$uid && $sn)
				{
					$tmp_arr=explode("-",$sn);
					if(is_array($tmp_arr) && count($tmp_arr) > 1) 
					{
						$uid = $tmp_arr[1];
						$local_sn = $sn;
					}
				}
				//处理$uid以外的参数
				foreach ($un_get_keys as $un_param_k)
				{
					$un_value = $uccpara_arr[$un_param_k];
					$param_arr[$un_param_k] = $un_value;
				}
			}
		}
		//============End: 处理uc_param_str未获取到的参数
		
		//不能通过客户端获取$uid，则生成guid, 且放到cookie，做为用户标志
		if(!$uid)
		{
			$uid=Utility::getGuid();
		}
		
		//为了兼容之前对本函数的调用,需要返回数组包含key：guid,sn,ve,fr,pf,bi,ip
		$param_arr["guid"] = $uid;
		$param_arr["sn"] = $local_sn;
		$param_arr["ve"] = $param_arr["ver"];
		$param_arr["bi"] = $param_arr["bid"];
		$param_arr["pf"] = $param_arr["pfid"];
		
		return $param_arr;
	}
	
	/**
	 * 获取uccpara数组
	 * Enter description here ...
	 */
	static function getUCParamArr_outDate() {
		$tmp_arr=array();
		if (isset($_COOKIE['uccpara'])){
			$http_uccpara = base64_decode($_COOKIE['uccpara']);
		}else{
			$http_uccpara = Utility::server("HTTP_UCCPARA");
		}
		$ex=$pf=$ve=$fr=$sn=$bi=$ch=$wi=$he=$ip=$ip2=$ua=$ua2=$cp=$guid="";
		if($http_uccpara!="") {
			$ex="e";
			$arr=self::kv2arr($http_uccpara);
			$pf=@$arr["pfid"];
			$cp=urldecode(@$arr["cp"]);
			$ve=@$arr["ver"];
			$fr=@$arr["fr"];
			$sn=@$arr["sn"];
			$bi=@$arr["bid"];
			$ch=@$arr["ch"];
			$wi=@$arr["width"];
			$he=@$arr["height"];
			$ip=@$arr["ip"];
			$ip2=Utility::server("REMOTE_ADDR");
			$ua=@$arr["ua"];
			$ua2=Utility::server("HTTP_USER_AGENT");
			if($sn) {
				$tmp_arr=explode("-",$sn);
				if(is_array($tmp_arr)&&count($tmp_arr)>=3) $guid=$tmp_arr[1];
			}
		} else {
			$ex="a";
			$pf=Utility::get("pf");
			$cp=Utility::get("cp");
			$ve=Utility::get("ve");
			if($ve=="") {
				if($cp!="") {
					$a=explode(";",$cp);
					foreach($a as $b) {
						$c=explode(":",$b);
						if(sizeof($c)>=2) {
							if($c[0]=="ver") {
								$ve=$c[1];
								break;
							}
						}
					}
				}
			}
			$fr=Utility::get("fr");
			$sn=Utility::get("dn");
			$bi=Utility::get("bi");
			$ch=Utility::get("ch");
			$ss=Utility::get("ss");
			if($ss!="") {
				$ss=explode("x",$ss);
				if(sizeof($ss)>=2) {
					$wi=$ss[0];
					$he=$ss[1];
				}
			}
			$ip=Utility::server("REMOTE_ADDR");
			$ua=Utility::server("HTTP_USER_AGENT");
			if($sn) {
				$tmp_arr=explode("-",$sn);
				if(is_array($tmp_arr)&&count($tmp_arr)>=2) $guid=$tmp_arr[0];
			}
		}
		if($guid=="") $guid=Utility::getGuid();
		$tmp_arr["ex"]=$ex;
		$tmp_arr["guid"]=$guid;
		$tmp_arr["pf"]=$pf;
		$tmp_arr["ve"]=$ve;
		if($fr==""||Utility::session("fr")!="android") {
			if(Utility::session("fr")=="android") {
				$fr="android";
			} else {
				if(Utility::get("fr")=="android") {
					$_SESSION["fr"]="android";
					$fr="android";
				}
			}
		}
		$tmp_arr["fr"]=$fr;
		$tmp_arr ["sn"] = $sn;
		$tmp_arr ["bi"] = $bi;
		$tmp_arr ["ch"] = $ch;
		$tmp_arr ["wi"] = $wi;
		$tmp_arr ["he"] = $he;
		$tmp_arr ["ip"] = $ip;
		$tmp_arr ["ip2"] = $ip2;
		$tmp_arr ["ua"] = $ua;
		$tmp_arr ["ua2"] = $ua2;
		$tmp_arr ["cp"] = $cp;
		return $tmp_arr;
	}
	
	static function getArrayFromString($str, $delimiter) {
		$result = array();
		$tmp = explode($delimiter, $str);
		foreach ($tmp as $v) {
			$vk = explode(":",$v,2);
			if(sizeof($vk) == 2) {
				$result[strtolower($vk[0])]=$vk[1];
			}
		}
		return $result;
	}

	//替换掉参数中存在的项
	static function attributeParamStr($attribute, $key = "", $value = "", $separators = "&amp;") {
		$str = "";
		if ($key !="" && $value != "" && !array_key_exists($key, $attribute)) $str = "&amp;".$key."=".$value;
		foreach ($attribute as $k => $v) {
			if ($k != $key && $v >0) {
				$str .= $separators.$k."=".$v;
			} elseif($k == $key && $value != "") {
				$str .= $separators.$key."=".$value;
			}
		}
		if (stripos($str, $separators) === 0) {
			$str = substr($str, strlen($separators));
		}
		
		$cg = intval(Utility::get("cg"));
		if($cg==1 || $cg ==2){
			if($str)
				$str .="&amp;cg=".$cg;
			else
				$str = "cg=".$cg;
		}
		
		return $str;
	}
	
	//手机号验�
	static function validMobileNum($mobile_number) {
		return (preg_match('/^((13[0-9])|(147)|(15[012356789])|(18[0256789]))\d{8}$/', $mobile_number));
	}
		
	
	//判断用户当前使用的是否是uc浏览�
	static function isUcweb(){
		$HTTP_UCCPARA=isset($_SERVER["HTTP_UCCPARA"])?$_SERVER["HTTP_UCCPARA"]:(isset($_COOKIE['uccpara']) ? base64_decode($_COOKIE['uccpara']) : "");
		if($HTTP_UCCPARA!=""||$_GET["dn"]!=""){
			return true;
		}else{
			return false;
		}
	}
	
	function getGuid() {
		$guid = Utility::cookie ( "UCPAY_GUID" );
		if ($guid == "") {
			$guid = Utility::unique();
			setcookie ( "UCPAY_GUID", $guid, time () + 315360000, "/" );
		}
		return $guid;
	}
	
	//验证email格式是否合法
	static function checkEmail($email) {
		return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$email));
	}
	
	//截取字符�utf-8)
	static function cut($str,$len,$dem="...") {
		$result="";
		$str=html_entity_decode(trim(strip_tags($str)),ENT_QUOTES,"utf-8");
		$strlen=strlen($str);
		for($i=0;(($i<$strlen)&&($len>0));$i++) {
			if($number=strpos(str_pad(decbin(ord(substr($str,$i,1))),8,"0",STR_PAD_LEFT),"0")) {
				if($len<1.0) {
					break;
				}
				$result.=substr($str,$i,$number);
				$len-=1.0;
				$i+=$number-1;
			} else {
				$result.=substr($str,$i,1);
				$len-=0.5;
			}
		}
		$result=htmlspecialchars($result,ENT_QUOTES,"utf-8");
		if($i<$strlen) {
			$result.=$dem;
		}
		return $result;
	}
	
	//mbsting截取字符�utf-8)
	static function cut_mb($str,$len,$dem="...") {
		$result="";
		//$str=html_entity_decode(trim(strip_tags($str)),ENT_QUOTES,"utf-8");
		$strlen=mb_strlen($str,"utf-8");
		//$cutlen=mb_strlen($dem,"utf-8");
		if($strlen<=$len){
			$result=$str;	
		}
		else{
			mb_internal_encoding("UTF-8");
			$result=mb_substr($str,0,$len).$dem;
		}
		$result=htmlspecialchars($result,ENT_QUOTES,"utf-8");
		return $result;
	}
	
	/**
	 * AJAX编码转换
	 */
	static function ajax_decode($str) {
		$decodedStr="";
		$pos=0;
		$len=strlen($str);
		while($pos<$len) {
			$charAt=substr($str,$pos,1);
			if($charAt=="%") {
				$pos++;
				$charAt=substr($str,$pos,1);
				if($charAt=="u") {
					$pos++;
					$unicodeHexVal=substr($str,$pos,4);
					$unicode=hexdec($unicodeHexVal);
					$entity="&#".$unicode.";";
					$decodedStr.=utf8_encode($entity);
					$pos+=4;
				} else {
					$hexVal=substr($str,$pos,2);
					$decodedStr.=chr(hexdec($hexVal));
					$pos+=2;
				}
			} else {
				$decodedStr.=$charAt;
				$pos++;
			}
		}
		return $decodedStr;
	}
	
	//解码
	function unescape($str) {
	 $ret = '';
	 $len = strlen($str);
	 for ($i = 0; $i < $len; $i++)
	 {
	  if ($str[$i] == '%' && $str[$i+1] == 'u')
	  {
	   $val = hexdec(substr($str, $i+2, 4));
	   if ($val < 0x7f) $ret .= chr($val);
	   else if($val < 0x800) $ret .= chr(0xc0 |($val>>6)).chr(0x80 |($val&0x3f));
	   else $ret .= chr(0xe0 |($val>>12)).chr(0x80 |(($val>>6)&0x3f)).chr(0x80 |($val&0x3f));
	   $i += 5;
	  }
	  else if ($str[$i] == '%')
	  {
	   $ret .= urldecode(substr($str, $i, 3));
	   $i += 2;
	  }
	  else
	  {
	   $ret .= $str[$i];
	  }
	 }
	 return  $ret;
	}
	
	//编码 js escape
	function escape($string) { 
		$return="";
		for($x=0;$x<mb_strlen($string,"UTF-8");$x++) {
			$str=mb_substr($string,$x,1,"UTF-8");
			if(strlen($str)>1) {
				$return.="%u".strtoupper(bin2hex(mb_convert_encoding($str,"UCS-2","UTF-8")));
			} else {
				$return.="%".strtoupper(bin2hex($str));
			}
		}
		return $return;
	}
	
	//生成随机标识
	static function sn() {
		$str="_".session_id()."_".Utility::getmicrotime()."_".rand(0,100)."_";
		$str=md5($str);
		return $str;
	}
	
	//生成唯一标示
	static function unique(){
		$str = Utility::getmicrotime().mt_rand().mt_rand().mt_rand();
		return md5($str);
	}
	
	//客户端IP地址
	static function ip() {
		return $_SERVER["REMOTE_ADDR"];
	}
	
	//当前日期时间
	static function now() {
		@date_default_timezone_set('Asia/Shanghai');
		return date("Y-m-d H:i:s");
	}
	
	//当前毫秒�
	static function getmicrotime() {
		list($usec,$sec)=explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}
	
	//当前时间�
	static function timestamp(){
		@date_default_timezone_set('Asia/Shanghai');
		return gmmktime();
	}
	
	//转成xml
	static function data_to_xml($data) {
		if (is_object ( $data )) {
			$data = get_object_vars ( $data );
		}
		$xml = '';
		foreach ( $data as $key => $val ) {
			is_numeric ( $key ) && $key = "item id=\"$key\"";
			$xml .= "<$key>";
			$xml .= (is_array ( $val ) || is_object ( $val )) ? Utility::data_to_xml ( $val ) : $val;
			list ( $key, ) = explode ( ' ', $key );
			$xml .= "</$key>";
		}
		return $xml;
	}
	
	// xml编码
	static function xml_encode($data, $encoding = 'utf-8', $root = "root") {
		$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
		$xml .= '<' . $root . '>';
		$xml .= Utility::data_to_xml ( $data );
		$xml .= '</' . $root . '>';
		return $xml;
	}
	
	/**
	* 发送Http请求
	*
	* @param string $url
	* @param array $post
	* @param string $method
	* @param bool $returnHeader
	* @param string $cookie
	* @param bool $bysocket
	* @param string $ip
	* @param integer $timeout
	* @param bool $block
	* @return string Response
	*/
	static function  httpRequest($url,$post='',$method='POST',$limit=0,$returnHeader=FALSE,$cookie='',$bysocket=FALSE,$ip='',$timeout=15,$block=TRUE) 
	{
	   $return = '';
	   $matches = parse_url($url);
	
	   !isset($matches['host']) && $matches['host'] = '';
	   !isset($matches['path']) && $matches['path'] = '';
	   !isset($matches['query']) && $matches['query'] = '';
	   !isset($matches['port']) && $matches['port'] = '';
	
	   $host = $matches['host'];
	   $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	   $port = !empty($matches['port']) ? $matches['port'] : 80;
	
	   if(strtolower($method) == 'post') {
	       $post = (is_array($post) and !empty($post)) ? http_build_query($post) : $post;
	       $out = "POST $path HTTP/1.0\r\n";
	       $out .= "Accept: */*\r\n";
	       //$out .= "Referer: $boardurl\r\n";
	       $out .= "Accept-Language: zh-cn\r\n";
	       $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
	       $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
	       $out .= "Host: $host\r\n";
	       $out .= 'Content-Length: '.strlen($post)."\r\n";
	       $out .= "Connection: Close\r\n";
	       $out .= "Cache-Control: no-cache\r\n";
	       $out .= "Cookie: $cookie\r\n\r\n";
	       $out .= $post;
	   } else {
	       $out = "GET $path HTTP/1.0\r\n";
	       $out .= "Accept: */*\r\n";
	       //$out .= "Referer: $boardurl\r\n";
	       $out .= "Accept-Language: zh-cn\r\n";
	       $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
	       $out .= "Host: $host\r\n";
	       $out .= "Connection: Close\r\n";
	       $out .= "Cookie: $cookie\r\n\r\n";
	   }
	
	   $fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	
	   if(!$fp)
	   {
	   		return ''; 
	   }
	   else 
	   {
	       $header = $content = '';
	
	       stream_set_blocking($fp, $block);
	       stream_set_timeout($fp, $timeout);
	       fwrite($fp, $out);
	       $status = stream_get_meta_data($fp);
	
	       if(!$status['timed_out'])
	       {//未超�
	           while (!feof($fp)) {
	               $header .= $h = fgets($fp);
	               if($h && ($h == "\r\n" ||  $h == "\n")) 
	               		break;
	           }
	
	           $stop = false;
	           while(!feof($fp) && !$stop) 
	           {
	               $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
	               $content .= $data;
	               if($limit) 
	               {
	                   $limit -= strlen($data);
	                   $stop = $limit <= 0;
	               }
	           }
	       }//endif
		   fclose($fp);
		   return $returnHeader ? array($header,$content) : $content;
	   }//end else
	}
	
	
	/**
	 * 去掉路径前缀(即，网站根目�+ "/")
	 * 
	 * @param string $file_path 原始路径
	 */
    static function getRidRootDir($file_path)
	{
		$root_dir = APP_ROOT;
		if(empty($root_dir))
		{
			$root_dir = dirname(dirname(__FILE__));
		}
		$root_pos = strpos($file_path,$root_dir);
		$get_rid_start = strlen(APP_ROOT) + 1;
		if ($root_pos !== false && strlen($file_path) >= $get_rid_start)
		{
			$file_path = substr($file_path, $get_rid_start); 
		}
		return $file_path;
	}
	
	/**
	 * 跟据属性值，获取XML元素
	 * 
	 * @param string $parent_element
	 * @param string $xml_tag_name
	 * @param string $attrbute_name
	 * @param string $attribute_value
	 * @return the special xml element, or false
	 */
	static function getXMLByAttribute($parent_element, $xml_tag_name, $attrbute_name, $attribute_value)
	{
		foreach ($parent_element->getElementsByTagName($xml_tag_name) as $element)
		{
			if ($element->getAttribute($attrbute_name) == $attribute_value)
			{
			 	return $element;
			}
		}
		return false;
	}
	
	/**
	 * 检查数组对象是否包含子元素
	 * 
	 * @param array $array
	 */
	static function arrayContainsItem($array)
	{
		return $array && is_array($array) && count($array) > 0;
	}
	
	
	
}

?>