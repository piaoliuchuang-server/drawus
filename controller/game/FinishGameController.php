<?php
/**
 * ====用户选择结束游戏====
 */
class FinishGameController extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function run()
	{
		header("Content-type:text/plain");
		header("Cache-Control: no-cache");
		$jsonResult = new Http_handle_result();
		
		$user_id = $_POST[Game_http_Params::USER_ID_FINISH];
		$game_id = $_POST[Game_http_Params::GAME_ID_FINISH];
		$game_module = new GameModule();
		$res = $game_module->updateGameUserInfo($game_id, $user_id);
		if ($res === false) //当没有此游戏信息，或者更新失败时，都提示false
		{   
			Log::Error(" 更新游戏信息：更新玩家状态-game_user_info表失败：, game_id=" . $game_id . ",user_id=" . $user_id);
			$this->_addErrorCode(Game_result_Params::GAME_FINISH_FAIL);
		}
		else if($res == Game_result_Params::GAME_USER_NOT_EXIST)
		{
			$this->_addErrorCode(Game_result_Params::GAME_USER_NOT_EXIST);
		}
		else if($res == Game_result_Params::PLAYER_HAS_LEFT)
		{
			$this->_addErrorCode(Game_result_Params::PLAYER_HAS_LEFT);
		}
		if ($this->_existError()){
			$this->AddUserResultHandle(false);
			return false;
		}
		
		//查找此游戏id现在正在玩的玩家有哪些，如果为0人，则把game_info表里的结束时间填入
		$player_now = $game_module->getUserCountByGameId($game_id);
		if($player_now === false)//为0人
		{
			//把game_info表里的结束时间填入
			$res = $game_module->updateGameEndtime($game_id);
			if ($res === false) //当没有此游戏信息，或者更新失败时，都提示false
			{   
				Log::Error("更新游戏信息：填入结束时间-game_info表失败：, game_id=" . $game_id );
				$this->_addErrorCode(Game_result_Params::GAME_FINISH_FAIL);
			}
			else if($res == Game_result_Params::GAME_NOT_EXIST)
			{
				$this->_addErrorCode(Game_result_Params::GAME_NOT_EXIST);
			} 
		}
		
		if ($this->_existError()){
			$this->AddUserResultHandle(false);
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
			$jsonResult->message = strval(Game_result_Params::GAME_FINISH_SUCCESS);
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