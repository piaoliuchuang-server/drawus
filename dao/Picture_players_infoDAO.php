<?php
//create-time 2012-4-08 16:31:28
class Picture_players_infoDAO extends DAO implements DAOInterface {

	private $table="picture_players_info";

	function save($picture_players_info) {
		$sql=new SQL($this->table);
		$sql->add("picture_id",$picture_players_info->getPicture_id());
		$sql->add("user_id",$picture_players_info->getUser_id());
		$sql->add("player_position",$picture_players_info->getPlayer_position());
		$sql->add("guess_status",$picture_players_info->getGuess_status());
		$sql->add("guess_time",$picture_players_info->getGuess_time());
		$sql->insert();
		$ret = parent::query($sql);
		return $ret;
	}

	function update($picture_players_info) {
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("picture_id",$picture_players_info->getPicture_id()));
		return $this->updates($picture_players_info,$criteria);
	}

	function updates($picture_players_info,$criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->add("user_id",$picture_players_info->getUser_id());
		$sql->add("player_position",$picture_players_info->getPlayer_position());
		$sql->add("guess_status",$picture_players_info->getGuess_status());
		$sql->add("guess_time",$picture_players_info->getGuess_time());
		$sql->update();
		return parent::query($sql);
	}

	function delete($picture_players_info) {
		if(!is_object($picture_players_info)) $id=$picture_players_info; else $id=$picture_players_info->getPicture_id();
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("picture_id",$id));
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
		if(!is_object($object)) $criteria->addRestrictions(Restrictions::eq("picture_id",$object)); else $criteria=$object;
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->select();
		$rs=parent::query($sql);
		if(parent::exist($rs)) {
			$picture_players_info=new Picture_players_info();
			$result=parent::field($rs);
			$picture_players_info->setPicture_id($result['picture_id']);
			$picture_players_info->setUser_id($result['user_id']);
			$picture_players_info->setPlayer_position($result['player_position']);
			$picture_players_info->setGuess_status($result['guess_status']);
			$picture_players_info->setGuess_time($result['guess_time']);
			return $picture_players_info;
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