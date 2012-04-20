<?php
//create-time 2012-4-20 20:03:10
class Picture_infoDAO extends DAO implements DAOInterface {

	private $table="picture_info";

	function save($picture_info) {
		$sql=new SQL($this->table);
		$sql->add("word",$picture_info->getWord());
		$sql->add("game_id",$picture_info->getGame_id());
		$sql->add("picture_time",$picture_info->getPicture_time());
		$sql->add("picture_path",$picture_info->getPicture_path());
		$sql->insert();
		$ret = parent::query($sql);
		$picture_info->setPicture_id(parent::lastId());
		return $ret;
	}

	function update($picture_info) {
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("picture_id",$picture_info->getPicture_id()));
		return $this->updates($picture_info,$criteria);
	}

	function updates($picture_info,$criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->add("word",$picture_info->getWord());
		$sql->add("game_id",$picture_info->getGame_id());
		$sql->add("picture_time",$picture_info->getPicture_time());
		$sql->add("picture_path",$picture_info->getPicture_path());
		$sql->update();
		return parent::query($sql);
	}

	function delete($picture_info) {
		if(!is_object($picture_info)) $id=$picture_info; else $id=$picture_info->getPicture_id();
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
			$picture_info=new Picture_info();
			$result=parent::field($rs);
			$picture_info->setPicture_id($result['picture_id']);
			$picture_info->setWord($result['word']);
			$picture_info->setGame_id($result['game_id']);
			$picture_info->setPicture_time($result['picture_time']);
			$picture_info->setPicture_path($result['picture_path']);
			return $picture_info;
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