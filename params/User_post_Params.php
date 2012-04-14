<?php
/**
 * 客户端注册用户POST参数名
 * @author fangxin
 */
class User_post_Params 
{
	/**
	 * 提交用户的UUID
	 */
	const UUID = 'uuid';
	
	/**
	 * iphone客户端token
	 */
	const TOKEN = 'token';
	
	/**
	 * 注册时提交的用户名
	 */
	const USER_ID = 'user_id';
	
	/**
	 * 注册时提交的密码
	 */
	const USER_PWD = 'user_pwd';
	
	/**
	 * 客户端的用户ID
	 */
	const GAME_FOUNDER = 'game_founder';
	
	/**
	 * 寻找游戏玩家的id 
	 */
	const GAME_PARTNER = 'game_partner';
	
	/**
	 * 此客户端第一次玩游戏，请创建新用户
	 */
	const GAME_FIRST_TIME = 'creat_new_username';	
}

?>