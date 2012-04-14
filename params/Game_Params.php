<?php
/**
 * 游戏参数
 * 
 * @author fangxin
 */
class Game_Params
{
	/**
	 * 游戏创建成功
	 */
	const CREAT_GAME_SUCCESS = 'creat_game_success';
	
	/**
	 * 不能和自己玩
	 */
	const CANT_PLAY_WITH_YOURSELF = 'yourself';
	
	/**
	 * 这两个玩家已经有正在进行的游戏
	 */
	const ALREADY_HAVE_ACTIVE_GAME = 'already_have_active_game';
	
	/**
	 * 画
	 */
	const DRAW = 'draw';
	
	/**
	 * 猜
	 */
	const GUESS = 'guess';
	
	/**
	 * 离开游戏
	 */
	const LEFT = 'left';
}
?>