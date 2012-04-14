<?php
/**
 * ====查找对应uuid的用户信息====
 */
class SearchUserController extends Controller
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
		
		$uuid = $_POST[User_post_Params::UUID];
		$user_module = new UserModule();
		$user_info = $user_module->getUserInfoByUuid($uuid);

		if(empty($user_info))//如果客户端第一次玩游戏，则提示创建新用户名
		{
			$jsonResult->message = strval(User_post_Params::GAME_FIRST_TIME);
			$jsonResult->data = "";
		}
		else //查找和此用户相关的游戏信息
		{
			$game_module = new GameModule();
			$user_games = $game_module->searchGameByUserid($user_info->getUser_id());
			$jsonResult->message = strval($user_info->getUser_id());
		    $jsonResult->data = array(
		    	'score' => $user_info->getUser_score(),
		    );	
		}
		echo json_encode($jsonResult);
		
	}
}
?>