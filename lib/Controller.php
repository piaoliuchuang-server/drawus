<?php 
abstract class Controller{
	private $available = false;

	public function __construct(){
		
	}
	
	protected function loadView($tpl, $exsit=true){
		if (file_exists(TPL_PATH . $tpl . ".tpl")){
			include TPL_PATH . $tpl . ".tpl";
			if($exsit){
				exit();
			}
		}else{
			Dispatcher::halt("template is not exists : " . $tpl);
		}
	}
	
	/**
	 * ajax方式返回客户段
	 * Enter description here ...
	 * @param array $data
	 * @param string $info //状态信息
	 * @param int $status //状态
	 * @param string $type //输出类型
	 */
	protected function ajaxReturn($data,$info='',$status=0,$type='')
    {
        $result  =  array();
        $result['status']  =  $status;
        $result['info'] =  $info;
        $result['data'] = $data;
        if(empty($type)) $type  =   'JSON';
        if(strtoupper($type)=='JSON') {
            // 返回JSON数据格式到客户端 包含状态信息
            header("Content-Type:text/html; charset=utf-8");
            exit(json_encode($result));
        }elseif(strtoupper($type)=='XML'){
            // 返回xml格式数据
            header("Content-Type:text/xml; charset=utf-8");
            exit(Utility::xml_encode($result));
        }elseif(strtoupper($type)=='EVAL'){
            // 返回可执行的js脚本
            header("Content-Type:text/html; charset=utf-8");
            exit($data);
        }else{
            // 
        }
    }

	/**
	 * 是否POST请求
	 * Enter description here ...
	 */
	protected function isPost(){
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}
	
	/**
     * @todo:跳转
     */
    protected function redirect($url, $time=0, $msg=''){
    	if(empty($url)){
    		return;
    	}
    	if (!headers_sent()) {
        	// redirect
        	if (0 === $time) {
            	header("Location: " . $url);
        	} else {
            	header("refresh:{$time};url={$url}");
            	echo($msg);
        	}
        	exit();
    	} else {
        	$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        	if ($time != 0)
            	$str .= $msg;
        	exit($str);
    	}
    }
	
    /**
     * 生成链接
     * Enter description here ...
     * @param string $do
     * @param array $params
     */
	protected function url($do='', $params=array(), $redirect=false){
		if (empty($do)) $do = DEFAULT_DO;
		if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
			$url_prefix = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']; 
		}else{
			$url_prefix = "/" . Dispatcher::getRoute() . ".php";
		}
		$url = $url_prefix . "?" ."do=" . $do ;
		if(!empty($params)){
			$url .= "&" . http_build_query($params);
		}
		if($redirect){
			Controller::redirect($url);
		}else{
			return $url;
		}
	}
}

?>