<?php
//create-time 2012-4-20 20:03:10
class Picture_players_info {
	private $picture_id;
	private $user_id;
	private $result_status;
	private $time;
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
	function setResult_status($result_status) {
		$this->result_status=$result_status;
	}
 
	function getResult_status() {
		return $this->result_status;
	}
	function setTime($time) {
		$this->time=$time;
	}
 
	function getTime() {
		return $this->time;
	}
}
?>