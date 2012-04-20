<?php
//create-time 2012-4-20 20:03:09
class Game_infoDAO extends DAO implements DAOInterface {

	private $table="game_info";

	function save($game_info) {
		$sql=new SQL($this->table);
		$sql->add("game_starttime",$game_info->getGame_starttime());
		$sql->add("game_endtime",$game_info->getGame_endtime());
		$sql->add("current_status",$game_info->getCurrent_status());
		$sql->insert();
		$ret = parent::query($sql);
		$game_info->setGame_id(parent::lastId());
		return $ret;
	}

	function update($game_info) {
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("game_id",$game_info->getGame_id()));
		return $this->updates($game_info,$criteria);
	}

	function updates($game_info,$criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->add("game_starttime",$game_info->getGame_starttime());
		$sql->add("game_endtime",$game_info->getGame_endtime());
		$sql->add("current_status",$game_info->getCurrent_status());
		$sql->update();
		return parent::query($sql);
	}

	function delete($game_info) {
		if(!is_object($game_info)) $id=$game_info; else $id=$game_info->getGame_id();
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("game_id",$id));
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->delete();
		return parent::query($sql);
	}


	function deletes($criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->delete();
		return parent::query($sql);
	}


	function load($object) {
		$criteria=new Criteria();
		if(!is_object($object)) $criteria->addRestrictions(Restrictions::eq("game_id",$object)); else $criteria=$object;
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->select();
		$rs=parent::query($sql);
		if(parent::exist($rs)) {
			$game_info=new Game_info();
			$result=parent::field($rs);
			$game_info->setGame_id($result['game_id']);
			$game_info->setGame_starttime($result['game_starttime']);
			$game_info->setGame_endtime($result['game_endtime']);
			$game_info->setCurrent_status($result['current_status']);
			return $game_info;
		}
	}

	function rs($criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->select();
		return parent::query($sql);
	}

	function ls($criteria) {
		return parent::getList($this->rs($criteria));
	}
}
?>