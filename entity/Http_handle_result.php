<?php

/**
 * HTTP返回的对象
 * (通常，会被序列化为JSON字符串)
 * 
 * @author fangxin
 */
class Http_handle_result
{
	/**
	 * 错误代码，多个错误代码用逗号隔开
	 * 错误代码的定义因业务类型而异，在params/业务_error_Params.php中定义
	 * 
	 * @var mixed
	 */
	public $message;
	
	/**
	 * 结果
	 * 结果的定义因业务类型而异，在entiry/业务_result_Params.php中定义
	 * 
	 * @var mixed
	 */
	public $data;
	

}

?>