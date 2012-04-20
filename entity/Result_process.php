<?php
/**
 * 处理http结果
 */
class Result_process
{
	private $success_return;
	
	function setSuccess_return($success_return) {
		$this->success_return = $success_return;
	}
 
	function getSuccess_return() {
		return $this->$success_return;
	}
	
	/**
	 * 处理增加用户结果,为Http服务
	 * 
	 * @param $status为false时说明有错误，true时为添加用户成功
	 */
	function AddUserResultHandle($status)
	{
		header("Content-type:text/plain; charset=UTF-8");
		header("Cache-Control: no-cache");
		$jsonResult = new Http_handle_result();
		if (!$status)
		{
			$jsonResult->message = $this->_errorToString();
			$jsonResult->data = '';
		}
		else 
		{
			$jsonResult->message = strval($this->success_return);
			$jsonResult->data = '';
		}
		echo json_encode($jsonResult);
		
	}

	//=================================================================
	//  错误代码操作 Begin
	//
	//==================================================================
	/**
	 * 存放错误代码
	 * 
	 * @var int[]
	 */
	private $_errorCode;
	
	/**
	 * 初始化错误代码数组
	 */
	function _initErrorCode()
	{
		 $this->_clearErrorCode();
	}
	
	/**
	 * 清理错误代码数组
	 */
	function _clearErrorCode()
	{
		 $this->_errorCode = array();
	}
	
	/** 
	 * 增加错误代码至$errorCode
	 * 
	 * @param int $errorCode  错误代码
	 */
	function _addErrorCode($errorCode)
	{
		 $this->_errorCode[count( $this->_errorCode)] = $errorCode;
	}
	
	/**
	 * 判断当前是否包含错误
	 * 
	 * @return bool 
	 */
	function _existError()
	{
		return count( $this->_errorCode) > 0;
	}
	
	/**
	 *将当前错误代码用 逗号 拼接成字符串
	 *
	 * @return string
	 */
	function _errorToString()
	{
		return implode(',',  $this->_errorCode);
	}
	//=========================== 错误代码操作  End===========================
}
?>