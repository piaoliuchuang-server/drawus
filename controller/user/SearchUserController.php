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
		
		$uuid = $_GET[User_http_Params::UUID];
		$user_module = new UserModule();
		$user_info = $user_module->getUserInfoByUuid($uuid);

		if(empty($user_info))//如果客户端第一次玩游戏，则提示创建新用户名
		{
			//验证注册用户数是否超过上限
			@date_default_timezone_set('Asia/Shanghai');
			$starttime = date('Y-m-d', time());
			$endtime = date("Y-m-d", time()+60*60*24);
			$new_user_count = $user_module->getUserCountByTime($starttime, $endtime);
			if($new_user_count >= NEW_USER_MAX_DAILY)
			{
				$jsonResult->message = strval(User_result_Params::HAS_REACHED_MAX);
				$jsonResult->data = "";
			}
			else
			{
				$jsonResult->message = strval(Game_result_Params::GAME_FIRST_TIME);
				$jsonResult->data = "";
			}
		}
		else //查找和此用户相关的游戏信息
		{
			$game_module = new GameModule();
			$game_info = $game_module->searchGameByUserid($user_info->getUser_id());
			$jsonResult->message = strval($user_info->getUser_id());
		    $jsonResult->data = array(
		    	'score' => $user_info->getUser_score(),
		    	'gameinfo' => $game_info,
		    );	
		}
		echo json_encode($jsonResult);
		
		
	}
}
?>