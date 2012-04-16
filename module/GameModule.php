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
			$sql = 'select * from game_user_info as t1 where (t1.user_id = \''.$players[0].'\' or t1.user_id = \''.$players[1].'\') and exists (select 1 from game_user_info as t2 where (t2.user_id = \''.$players[0].'\' or t2.user_id = \''.$players[1].'\') and t1.user_id != t2.user_id and t1.game_id = t2.game_id and t1.position <> \''.Game_Params::LEFT.'\' and t2.position <> \''.Game_Params::LEFT.'\')';
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
	 * @param $user_id
	 * @return Json
	 */
	function searchGameByUserid($user_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('user_id',$user_id));
		$criteria->addRestrictions(Restrictions::ne('position',Game_Params::LEFT));
		$sql=new SQL("game_user_info");
		$sql->criteria=$criteria;
		$sql->select();
		$dao = new DAO();
		$rs = $dao->query($sql);
		$num_rs = $dao->exist($rs);
		if($num_rs)
		{
			$gameinfo = array();
			for($i=0; $i < $num_rs; $i++)
			{
				$row = @mysql_fetch_assoc($rs);
				//根据game_id查找游戏玩家信息(game_user_info)
				$gu_rs = $this->searchGameUserByGameid($row['game_id']);
				$partner = array();
				while(false !== ($gu_row=@mysql_fetch_assoc($gu_rs)))
				{
					if($gu_row['user_id'] != $user_id)
					{
						$partner[] = array(
							'partner_id' => stripcslashes($gu_row['user_id']),
							'partner_position' => stripcslashes($gu_row['position']),
						);
					}
				}
				
				$gameinfo[] = array(
					'game_id' => stripcslashes($row['game_id']),
					'position' => stripcslashes($row['position']),
					'partner' => $partner,
				);
			}
			return $gameinfo;
		}
		else
		{
			return "";
		}

	}
	
	/**
	 * 把game_user_info表中的游戏玩家信息position状态改为left
	 * 
	 * @param $game_id
	 * @param $user_id
	 */
	function updateGameUserInfo($game_id, $user_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('game_id',$game_id));
		$criteria->addRestrictions(Restrictions::eq('user_id',$user_id));
		$game_user = $this->game_user_infoDAO->load($criteria);
		if(is_null($game_user)) //没有查询到游戏信息，返回false
		{
			return false;
		}
		//更新game_user_info表对应的position为left
		$game_user->setPosition(Game_Params::LEFT);
		$sql=new SQL("game_user_info");
		$dao = new DAO();
		$sql->criteria=$criteria;
		$sql->add("position",$game_user->getPosition());
		$sql->update();
		return $dao->query($sql);	
	}
	
	/**
	 * 在game_info表中插入对应game_id的游戏结束时间
	 */
	function updateGameEndtime($game_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('game_id',$game_id));
		$game = $this->game_infoDAO->load($criteria);
		if(is_null($game))//没有查到游戏信息，返回false
		{
			return false;
		}
		//更新game_info表的游戏结束时间
		$game->setGame_endtime(Utility::now());
		return $this->game_infoDAO->update($game);	
	}
	
	/**
	 * 根据game_id查找正在玩游戏的玩家信息
	 * 
	 * @param $game_id
	 * @return $rs;
	 */
	function searchGameUserByGameid($game_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('game_id',$game_id));
		$criteria->addRestrictions(Restrictions::ne('position',Game_Params::LEFT));
		$sql=new SQL("game_user_info");
		$sql->criteria=$criteria;
		$sql->select();
		$dao = new DAO();
		$rs = $dao->query($sql);
		return $rs;
	}
	
	/**
	 * 根据game_id，得到正在玩游戏的游戏玩家总数
	 * 
	 * @param $game_id 
	 */
	function getUserCountByGameId($game_id)
	{
		$criteria = new Criteria();
		$criteria->addRestrictions(Restrictions::eq('game_id',$game_id));
		$criteria->addRestrictions(Restrictions::ne('position',Game_Params::LEFT));
		$sql = new SQL('game_user_info');
		$sql->criteria = $criteria;
		$sql->select();
		$user_count = DAO::getCount($sql);
		if(!$user_count)
	    	return false;
	    return $user_count;
	}
	
	private function _log_error()
	{
		Log::Error("database error : " . mysql_error());
	}
}
?>