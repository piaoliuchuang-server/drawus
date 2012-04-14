<?php
//create-time 2012-4-08 16:31:28
class Game_info {
	private $game_id;
	private $game_starttime;
	private $game_endtime;
	function setGame_id($game_id) {
		$this->game_id=$game_id;
	}
 
	function getGame_id() {
		return $this->game_id;
	}
	function setGame_starttime($game_starttime) {
		$this->game_starttime=$game_starttime;
	}
 
	function getGame_starttime() {
		return $this->game_starttime;
	}
	function setGame_endtime($game_endtime) {
		$this->game_endtime=$game_endtime;
	}
 
	function getGame_endtime() {
		return $this->game_endtime;
	}
}
?>