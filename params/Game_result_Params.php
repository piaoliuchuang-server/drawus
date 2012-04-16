<?php
/**
 * 游戏返回结果参数
 * 
 * @author fangxin
 */
class Game_result_Params
{
	/**
	 * 游戏创建成功
	 */
	const CREAT_GAME_SUCCESS = 'creat_game_success';
	
	/**
	 *  游戏创建者不能为空
	 */
	const EMPTY_FOUNDER = 'empty_founder';
	
	/**
	 * 游戏伙伴不能为空
	 */
	const EMPTY_PARTNER = 'empty_partner';
	
	/**
	 * 不能和自己玩
	 */
	const CANT_PLAY_WITH_YOURSELF = 'yourself';
	
	/**
	 * 这两个玩家已经有正在进行的游戏
	 */
	const ALREADY_HAVE_ACTIVE_GAME = 'already_have_active_game';
	
	/**
	 * 此客户端第一次玩游戏，请创建新用户
	 */
	const GAME_FIRST_TIME = 'creat_new_username';	
	
	/**
	 * 结束游戏信息失败
	 */
	const GAME_FINISH_FAIL = 'fail';
	
	/**
	 * 更新游戏状态成功
	 */
	const GAME_FINISH_SUCCESS = 'success';
	
}
?>