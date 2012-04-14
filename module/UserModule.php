<?php
/**
 * 用户--公用业务逻辑
 */
class UserModule
{
	private $user_infoDAO;
	
	function __construct()
	{
		$this->user_infoDAO = new User_infoDAO();
	}
	
	/**
	 * 创建新用户
	 * 
	 * @param $user_id
	 * @param $password
	 * @param $uuid
	 * @param $token not null
	 */
	function createUserInfo($user_id, $uuid, $token, $password, $user_score)
	{
		$user = new User_info(); 
		$user->setUser_id($user_id);
		$user->setUuid($uuid);
		if(!empty($token))
		{
			$user->setToken($token);
		}
		$user->setPassword($password);
		$user->setRegister_time(Utility::now());
		$user->setUser_score($user_score);
		
		$ret = $this->user_infoDAO->save($user);
		if($ret === false)
		{
			$this->_log_error();
			return false;
		}
		return $user;
	}
	
	/**
	 * 根据uuid取用户信息
	 * 
	 * @param $uuid
	 */
	function getUserInfoByUuid($uuid)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('uuid', $uuid));
		$user = $this->user_infoDAO->load($criteria);
		return $user;
	}
	
	/**
	 * 根据客户端user_name取用户信息
	 * 
	 * @param string $user_id
	 */
	function getUserInfoByUserId($user_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('user_id', $user_id));
		$user = $this->user_infoDAO->load($criteria);
		return $user;
	}
	
	/**
	 * 根据register_time，得到某段时间内注册的总用户数
	 * 
	 * @param date $starttime 
	 * @param date $endtime
	 */
	function getUserCountByTime($starttime, $endtime)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::ge('register_time', $starttime));
		$criteria->addRestrictions(Restrictions::lt('register_time', $endtime));
		$sql = new SQL('user_info');
		$sql->criteria = $criteria;
		$sql->select();
		$user_count = DAO::getCount($sql);
		if(!$user_count)
	    	return false;
	    return $user_count;
	}
	
	/**
	 * 错误日志
	 * 
	 */
	private function _log_error()
	{
		Log::Error("database error : " . mysql_error());
	}
}
?>