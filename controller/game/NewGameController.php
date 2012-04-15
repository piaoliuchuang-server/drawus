<?php
/**
 * 新建游戏
 */
class NewGameController extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function run()
	{
		$founder_id = $_POST[User_post_Params::GAME_FOUNDER];
		$partner_id = $_POST[User_post_Params::GAME_PARTNER];
		$user_module = new UserModule();
		
		//本机游戏创建者ID不能为空
		if (empty($founder_id) && $founder_id != '0')
		{
			$this->_addErrorCode(Game_result_Params::EMPTY_FOUNDER);
		}
		//寻找游戏玩家的ID不能为空
		if (empty($partner_id) && $partner_id != '0')
		{
			$this->_addErrorCode(Game_result_Params::EMPTY_PARTNER);
		}
		//不能和自己进行游戏
		if($founder_id == $partner_id)
		{
			$this->_addErrorCode(Game_result_Params::CANT_PLAY_WITH_YOURSELF);
		}
		
		//取游戏玩家的信息
		$partner = $user_module -> getUserInfoByUserId($partner_id);
		if(is_null($partner))
		{
			$this->_addErrorCode(User_error_Params::PLAYER_NOT_EXIST);	
		}
		
		$game_module = new GameModule();
		//查找这两个玩家是否正有游戏在进行
		$players = array($founder_id, $partner_id);
		$doing_game_id = $game_module -> searchGameByPlayers($players);
		if(!is_null($doing_game_id))
		{
			$this->_addErrorCode(Game_result_Params::ALREADY_HAVE_ACTIVE_GAME);
		}
		
		if ($this->_existError()){
			$this->AddUserResultHandle(false);
			return false;
		}

		//创建游戏
		$game = $game_module->createGameInfo($founder_id, $partner_id);
			
		if(!$game)
		{
			$err_info = '新建游戏到数据库失败：founder_id='. $founder_id .';partner_id=' .$partner_id ;
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
			$jsonResult->message = strval(Game_result_Params::CREAT_GAME_SUCCESS);
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