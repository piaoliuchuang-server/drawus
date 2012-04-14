<?php
/**
 * 游戏--公用业务逻辑
 */
class GameModule
{
	private $game_infoDAO;
	private $game_user_infoDAO;
	
	function __construct()
	{
		$this->game_infoDAO = new Game_infoDAO();
		$this->game_user_infoDAO = new Game_user_infoDAO();
	}
	
	/**
	 * 创建新游戏
	 * 
	 * @param $founder_id
	 * @param $partner_id
	 */
	function createGameInfo($founder_id, $partner_id)
	{
		$game = new Game_info(); 
		$game->setGame_starttime(Utility::now());
		$ret = $this->game_infoDAO->save($game);
		if($ret === false)
		{
			$this->_log_error();
			return false;
		}

		$game_user = new Game_user_info();
		$game_user->setGame_id($game->getGame_id());
		$game_user->setUser_id($founder_id);
		$game_user->setPosition(Game_Params::DRAW);
		$ret_founder = $this->game_user_infoDAO->save($game_user);
		if($ret_founder === false)
		{
			$this->_log_error();
			return false;
		}
		
		$game_user->setUser_id($partner_id);
		$game_user->setPosition(Game_Params::GUESS);
		$ret_partner = $this->game_user_infoDAO->save($game_user);
		if($ret_partner === false)
		{
			$this->_log_error();
			return false;
		}
		return true;
	}
	
	/**
	 * 查找玩家（二个人或者三个人）正在进行的游戏
	 * ----------------此方法目前只支持两个人的游戏
	 * 
	 * @param players 数组
	 */
	function searchGameByPlayers($players)
	{
		if(count($players) == 2)
		{
			$sql = 'select * from game_user_info as t1 where (t1.user_id = \''.$players[0].'\' or t1.user_id = \''.$players[1].'\') and exists (select 1 from game_user_info as t2 where (t2.user_id = \''.$players[0].'\' or t2.user_id = \''.$players[1].'\') and t1.user_id != t2.user_id and t1.game_id = t2.game_id)';
			$dao = new DAO();
			$rs = $dao->query($sql);
		}
		//如果是三个玩家的话暂时用如下sql
		//select * FROM(select game_id from game_user_info where user_id='$founder_id') AS A JOIN (select game_id from game_user_info where user_id='$partner_id') AS B where A.game_id=B.game_id
		if($dao->exist($rs)) 
		{
			$game_user_info=new Game_user_info();
			$result=$dao->field($rs);
			$game_user_info->setGame_id($result['game_id']);
			$game_user_info->setUser_id($result['user_id']);
			$game_user_info->setPosition($result['position']);
			return $game_user_info;
		}
	}
	
	
	/**
	 * 根据user_id取用户游戏信息
	 * 
	 */
	function searchGameByUserid($user_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('user_id',$user_id));
		$games = $this->game_user_infoDAO->load($criteria);
		echo $this->game_user_infoDAO->ls($games);
		$infos = $this->game_user_infoDAO->ls($criteria);
		echo $infos[0];
	}
	
	private function _log_error()
	{
		Log::Error("database error : " . mysql_error());
	}
}
?>