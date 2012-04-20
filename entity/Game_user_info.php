<?php
//create-time 2012-4-20 20:03:10
class Game_user_info {
	private $game_id;
	private $user_id;
	private $position;
	function setGame_id($game_id) {
		$this->game_id=$game_id;
	}
 
	function getGame_id() {
		return $this->game_id;
	}
	function setUser_id($user_id) {
		$this->user_id=$user_id;
	}
 
	function getUser_id() {
		return $this->user_id;
	}
	function setPosition($position) {
		$this->position=$position;
	}
 
	function getPosition() {
		return $this->position;
	}
}
?>