<?php
//create-time 2012-4-20 20:03:10
class User_infoDAO extends DAO implements DAOInterface {

	private $table="user_info";

	function save($user_info) {
		$sql=new SQL($this->table);
		$sql->add("user_id",$user_info->getUser_id());
		$sql->add("uuid",$user_info->getUuid());
		$sql->add("token",$user_info->getToken());
		$sql->add("password",$user_info->getPassword());
		$sql->add("register_time",$user_info->getRegister_time());
		$sql->add("user_score",$user_info->getUser_score());
		$sql->insert();
		$ret = parent::query($sql);
		return $ret;
	}

	function update($user_info) {
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("user_id",$user_info->getUser_id()));
		return $this->updates($user_info,$criteria);
	}

	function updates($user_info,$criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->add("uuid",$user_info->getUuid());
		$sql->add("token",$user_info->getToken());
		$sql->add("password",$user_info->getPassword());
		$sql->add("register_time",$user_info->getRegister_time());
		$sql->add("user_score",$user_info->getUser_score());
		$sql->update();
		return parent::query($sql);
	}

	function delete($user_info) {
		if(!is_object($user_info)) $id=$user_info; else $id=$user_info->getUser_id();
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("user_id",$id));
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
		if(!is_object($object)) $criteria->addRestrictions(Restrictions::eq("user_id",$object)); else $criteria=$object;
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->select();
		$rs=parent::query($sql);
		if(parent::exist($rs)) {
			$user_info=new User_info();
			$result=parent::field($rs);
			$user_info->setUser_id($result['user_id']);
			$user_info->setUuid($result['uuid']);
			$user_info->setToken($result['token']);
			$user_info->setPassword($result['password']);
			$user_info->setRegister_time($result['register_time']);
			$user_info->setUser_score($result['user_score']);
			return $user_info;
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