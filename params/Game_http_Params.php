<?php
/**
 * 游戏http请求的参数
 * 
 * @author fangxin
 */
class Game_http_Params
{
	/**
	 * 客户端的用户ID
	 */
	const GAME_FOUNDER = 'game_founder';
	
	/**
	 * 寻找游戏玩家的id 
	 */
	const GAME_PARTNER = 'game_partner';
	
	/**
	 * 要结束游戏的发起请求人的id
	 */
	const USER_ID_FINISH = 'user_id_finish';
	
	/**
	 * 要结束的游戏的id
	 */
	const GAME_ID_FINISH = "game_id_finish";
}
?>