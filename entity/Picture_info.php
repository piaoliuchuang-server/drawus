<?php
//create-time 2012-4-08 16:31:28
class Picture_info {
	private $picture_id;
	private $word;
	private $game_id;
	private $picture_time;
	private $picture_path;
	function setPicture_id($picture_id) {
		$this->picture_id=$picture_id;
	}
 
	function getPicture_id() {
		return $this->picture_id;
	}
	function setWord($word) {
		$this->word=$word;
	}
 
	function getWord() {
		return $this->word;
	}
	function setGame_id($game_id) {
		$this->game_id=$game_id;
	}
 
	function getGame_id() {
		return $this->game_id;
	}
	function setPicture_time($picture_time) {
		$this->picture_time=$picture_time;
	}
 
	function getPicture_time() {
		return $this->picture_time;
	}
	function setPicture_path($picture_path) {
		$this->picture_path=$picture_path;
	}
 
	function getPicture_path() {
		return $this->picture_path;
	}
}
?>