<?php
class Info {
	
	public $oPrefix;
	public $oSuffix;
	public $prefix;
	public $suffix;
	public $title;
	public $type;
	public $tPrefix;
	public $tSuffix;
	public $style;
	public $num;
	public $errors;
	
	private $txt;
	
	function __construct() {
		
	}
	
	function add($msg) {
		$this->num+=1;
		if($this->style=="num") $this->txt.="[".$this->num."]";
		$this->txt.=$this->prefix.$msg.$this->suffix;
	}
	
	function error($msg,$error) {
		$this->add($msg);
		$this->errors[]=$error;
	}
	
	function __toString() {
		if($this->num>0) {
			return $this->tPrefix.$this->title.$this->tSuffix.$this->oPrefix.$this->txt.$this->oSuffix;
		} else {
			return "";
		}
	}
	
	function clear() {
		$this->__destruct();
	}
	
	function __destruct() {
		$this->prefix="";
		$this->suffix="";
		$this->num=0;
		$this->txt="";
	}
	
}
?>