<?php
/**
 * 用户信息-错误代码
 * 
 * @author fangxin
 */
class User_error_Params
{
	/**
	 * 没有错误
	 */
	const SUCESS = 'sucess';
	
	/**
	 * 客户端uuid为空
	 */
	const EMPTY_UUID = 'empty_uuid';
	
	/**
	 * 用户ID为空
	 */
	const EMPTY_USER_ID = 'empty_user_id';
	
	/**
	 * 用户密码为空
	 */
	const EMPTY_USER_PWD = 'empty_user_pwd';
	
	/**
	 * 字符不符合规则
	 */
	const ILLEGAL_WORD = 'illegal_word';
	
	/**
	 * 用户名已经有人申请
	 */
	const EXIST_USER_ID = 'exist_user_id';
	
	/**
	 * 用户名创建失败
	 */
	const ADD_USER_FAILED = 'add_user_failed';
	
	/**
	 * 获取用户信息失败
	 */
	const GET_USER_FAILED = 'get_user_failed';
	
	/**
	 * 每日注册用户已达上线
	 */
	const HAS_REACHED_MAX = 'has_reached_max';
	
	/**
	 * 没有这个用户ID
	 */
	const PLAYER_NOT_EXIST = 'player_not_exist';
}
?>