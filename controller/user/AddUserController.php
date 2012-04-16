<?php
/**
 * ====创建新用户====
*/
class AddUserController extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function run()
	{
		$user_module = new UserModule();
		
		//验证注册用户数是否超过上限
		@date_default_timezone_set('Asia/Shanghai');
		$starttime = date('Y-m-d', time());
		$endtime = date("Y-m-d", time()+60*60*24);
		$new_user_count = $user_module->getUserCountByTime($starttime, $endtime);
		if($new_user_count > NEW_USER_MAX_DAILY)
		{
			$this->_addErrorCode(User_result_Params::HAS_REACHED_MAX);
			$this->AddUserResultHandle(false);
			return false;
		}
		
		//用户名非空验证和格式验证
		$user_id = $_POST[User_http_Params::USER_ID];
		$password = $_POST[User_http_Params::USER_PWD];
		$uuid = $_POST[User_http_Params::UUID];
		$token = $_POST[User_http_Params::TOKEN];

		if (empty($user_id) && $user_id != '0')
		{
			$this->_addErrorCode(User_result_Params::EMPTY_USER_ID);
		}
		else if (strlen($user_id) < 4 || strlen($user_id) > 16) 
		{
			$this->_addErrorCode(User_result_Params::ILLEGAL_WORD);
		}
		else if(empty($uuid))
		{
			$this->_addErrorCode(User_result_Params::EMPTY_UUID);
		}
		else
		{
			//查看是否含有空格
			$str_id = explode(" ", $user_id);
			if(count($str_id)!=1) $this->_addErrorCode(User_result_Params::ILLEGAL_WORD);
		}
		
		//密码可为空，若不为空，则进行格式验证
		if (!(empty($password) && $password != '0')) 
		{
			$str_pwd = explode(" ", $password);
			if (strlen($password) < 4 || strlen($password) > 16 || count($str_pwd)!=1) $this->_addErrorCode(User_result_Params::ILLEGAL_WORD);
		}
		
		if ($this->_existError()){
			$this->AddUserResultHandle(false);
			return false;
		}
		
		

		$user = $user_module->createUserInfo($user_id, $uuid, $token, $password, NEW_USER_SCORE);
		
		if(!$user)
		{
			//如果已经存在此用户，则返回json提示
			if(mysql_error() != '' && strstr(mysql_error(),'Duplicate entry') != false && strstr(mysql_error(),'for key \'PRIMARY\'') != false)
			{
				$this->_addErrorCode(User_result_Params::EXIST_USER_ID);
			}
			else
			{
				$this->_addErrorCode(User_result_Params::ADD_USER_FAILED);
			}
			if ($this->_existError()) 
			{
				$this->AddUserResultHandle(false);
			}
			
			$err_info = '保存用户名到数据库失败：user_id='. $user_id .';password=' .$password ;
			Log::Error($err_info,__FILE__ ,__LINE__,__CLASS__,__METHOD__);
			return false;
		}
		
		$this->AddUserResultHandle(true);
		
		
	}
	
	/**
	 * 处理增加用户结果,为Http服务
	 * 
	 * @param $status为false时说明有错误，true时为添加用户成功
	 */
	private function AddUserResultHandle($status)
	{
		header("Content-type:text/plain");
		header("Cache-Control: no-cache");
		$jsonResult = new Http_handle_result();
		if (!$status)
		{
			$jsonResult->message = $this->_errorToString();
			$jsonResult->data = '';
		}
		else 
		{
			$jsonResult->message = strval(User_result_Params::SUCESS);
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
	private function _initErrorCode()
	{
		 $this->_clearErrorCode();
	}
	
	/**
	 * 清理错误代码数组
	 */
	private function _clearErrorCode()
	{
		 $this->_errorCode = array();
	}
	
	/** 
	 * 增加错误代码至$errorCode
	 * 
	 * @param int $errorCode  错误代码
	 */
	private function _addErrorCode($errorCode)
	{
		 $this->_errorCode[count( $this->_errorCode)] = $errorCode;
	}
	
	/**
	 * 判断当前是否包含错误
	 * 
	 * @return bool 
	 */
	private function _existError()
	{
		return count( $this->_errorCode) > 0;
	}
	
	/**
	 *将当前错误代码用 逗号 拼接成字符串
	 *
	 * @return string
	 */
	private function _errorToString()
	{
		return implode(',',  $this->_errorCode);
	}
	//===========================错误代码操作 	End===========================
}

?>