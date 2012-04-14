<?php
//create-time 2012-4-08 16:31:28
class Picture_players_info {
	private $picture_id;
	private $user_id;
	private $player_position;
	private $guess_status;
	private $guess_time;
	function setPicture_id($picture_id) {
		$this->picture_id=$picture_id;
	}
 
	function getPicture_id() {
		return $this->picture_id;
	}
	function setUser_id($user_id) {
		$this->user_id=$user_id;
	}
 
	function getUser_id() {
		return $this->user_id;
	}
	function setPlayer_position($player_position) {
		$this->player_position=$player_position;
	}
 
	function getPlayer_position() {
		return $this->player_position;
	}
	function setGuess_status($guess_status) {
		$this->guess_status=$guess_status;
	}
 
	function getGuess_status() {
		return $this->guess_status;
	}
	function setGuess_time($guess_time) {
		$this->guess_time=$guess_time;
	}
 
	function getGuess_time() {
		return $this->guess_time;
	}
}
?>