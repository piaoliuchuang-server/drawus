<?php
//create-time 2012-4-20 20:03:10
class Words {
	private $word;
	private $word_type;
	private $word_degree;
	function setWord($word) {
		$this->word=$word;
	}
 
	function getWord() {
		return $this->word;
	}
	function setWord_type($word_type) {
		$this->word_type=$word_type;
	}
 
	function getWord_type() {
		return $this->word_type;
	}
	function setWord_degree($word_degree) {
		$this->word_degree=$word_degree;
	}
 
	function getWord_degree() {
		return $this->word_degree;
	}
}
?>