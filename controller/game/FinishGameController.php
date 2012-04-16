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
			$jsonResult->message = strval(Game_result_Params::GAME_FINISH_FAIL);
			$jsonResult->data = "";
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
				$jsonResult->message = strval(Game_result_Params::GAME_FINISH_FAIL);
				$jsonResult->data = "";
				return false;
			}
		}
		$jsonResult->message = strval(Game_result_Params::GAME_FINISH_SUCCESS);
		$jsonResult->data = "";
		
		echo json_encode($jsonResult);
	}
}
?>