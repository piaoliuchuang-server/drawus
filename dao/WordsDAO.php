<?php
//create-time 2012-4-20 20:03:10
class WordsDAO extends DAO implements DAOInterface {

	private $table="words";

	function save($words) {
		$sql=new SQL($this->table);
		$sql->add("word",$words->getWord());
		$sql->add("word_type",$words->getWord_type());
		$sql->add("word_degree",$words->getWord_degree());
		$sql->insert();
		$ret = parent::query($sql);
		return $ret;
	}

	function update($words) {
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("word",$words->getWord()));
		return $this->updates($words,$criteria);
	}

	function updates($words,$criteria) {
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->add("word_type",$words->getWord_type());
		$sql->add("word_degree",$words->getWord_degree());
		$sql->update();
		return parent::query($sql);
	}

	function delete($words) {
		if(!is_object($words)) $id=$words; else $id=$words->getWord();
		$criteria=new Criteria();
		$criteria->addRestrictions(Restrictions::eq("word",$id));
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
		if(!is_object($object)) $criteria->addRestrictions(Restrictions::eq("word",$object)); else $criteria=$object;
		$sql=new SQL($this->table);
		$sql->criteria=$criteria;
		$sql->select();
		$rs=parent::query($sql);
		if(parent::exist($rs)) {
			$words=new Words();
			$result=parent::field($rs);
			$words->setWord($result['word']);
			$words->setWord_type($result['word_type']);
			$words->setWord_degree($result['word_degree']);
			return $words;
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