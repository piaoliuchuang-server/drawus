<?php
/**
 * game_info表游戏status参数
 * 
 * @author fangxin
 */
class Game_status_Params
{
	/**
	 * 游戏结束
	 */
	const END = 0;
		
	/**
	 * 等待画图
	 */
	const WAIT_DRAW = 1;
	
	/**
	 * 画图完成
	 */
	const DRAW_DONE = 2;
}
?>