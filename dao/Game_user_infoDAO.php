<?php
//create-time 2012-4-11 21:58:40
class Game_user_infoDAO extends DAO implements DAOInterface {

	private $table="game_user_info";

	function save($game_user_info) {
		$sql=new SQL($this->table);
		$sql->add("game_id",$game_user_info->getGame_id());
		$sql->add("user_id",$game_user_info->getUser_id());
		$sql->add("position",$game_user_info->getPosition());
		$sql->insert();
		$ret = parent::query($sql);
		return $ret;
	}

	function update($game_user_info) {
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("game_id",$game_user_info->getGame_id()));
		return $this->updates($game_user_info,$criteria);
	}

	function updates($game_user_info,$criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->add("user_id",$game_user_info->getUser_id());
		$sql->add("position",$game_user_info->getPosition());
		$sql->update();
		return parent::query($sql);
	}

	function delete($game_user_info) {
		if(!is_object($game_user_info)) $id=$game_user_info; else $id=$game_user_info->getGame_id();
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
			$game_user_info=new Game_user_info();
			$result=parent::field($rs);
			$game_user_info->setGame_id($result['game_id']);
			$game_user_info->setUser_id($result['user_id']);
			$game_user_info->setPosition($result['position']);
			return $game_user_info;
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