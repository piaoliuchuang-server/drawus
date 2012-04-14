<?php
class Pager {
	
	/* 下面是可写属性，调用相应的setXXX()方法设置 */
	
	//翻页调用地址({page}代表页码)
	private $url;
	
	//每页记录数
	private $size;
	
	//页码参数名称
	private $param="page";
	
	//程序直接指定记录页码，否则从param中获取
	private $value;
	
	//显示翻页数
	private $pagernum=0;
	
	
	/* 下面是只读属性 */
	
	//总记录数
	var $totalnum=0;
	
	//总共页数
	var $totalpage=0;
	
	//当前页码
	var $curpage=0;
	
	//排序计数
	var $ordernum=0;
	
	
	function setPagernum($pagernum) {
		$this->pagernum=$pagernum;
	}
	
	function getPagernum() {
		return $this->pagernum;
	}
	
	function setUrl($url) {
		$this->url=$url;
	}
	
	function getUrl() {
		return $this->url;
	}
	
	function setSize($size) {
		$this->size=$size;
	}
	
	function getSize() {
		return $this->size;
	}
	
	function setParam($param) {
		$this->param=$param;
	}
	
	function getParam() {
		return $this->param;
	}
	
	function setValue($value) {
		$this->value=$value;
	}
	
	function getValue() {
		return $this->value;
	}
	
	//正序
	function asc() {
		return ($this->curpage-1)*$this->size+(++$this->ordernum);
	}
	
	//倒序
	function desc() {
		return $this->totalnum-((($this->curpage-1)*$this->size)+($this->ordernum++));
	}
	
	//显示翻页
	function show($style) {
		if($this->totalpage>0) {
			switch($style) {
				case 1://WML
				if($this->totalpage>1) {
					if($this->curpage!=$this->totalpage) echo("<a href=\"".str_replace("{page}",$this->curpage+1,$this->url)."\">下页</a> ");
					if($this->curpage!=1) echo("<a href=\"".str_replace("{page}",$this->curpage-1,$this->url)."\">上页</a> ") ;
					if($this->curpage!=1) echo("<a href=\"".str_replace("{page}",1,$this->url)."\">首页</a> ");
					if($this->curpage!=$this->totalpage) echo("<a href=\"".str_replace("{page}",$this->totalpage,$this->url)."\">末页</a> ");
					echo($this->totalnum."条");
					if($this->totalpage!=1) echo("<br/>");
					echo($this->curpage."/".$this->totalpage."页");
					$inputName=Util::formId($this->param);
					$this->url=str_replace("{page}","$({$inputName})",$this->url);
					echo(" 至<input name=\"".$inputName."\" format=\"*N\" value=\"1\" size=\"3\" emptyok=\"false\" />页 <a href=\"{$this->url}\">跳转</a><br />");
				}
				break;
				case 2://HTML
				if($this->totalpage>1) {
					if($this->curpage!=1) $result.="<a href=\"".str_replace("{page}",1,$this->url)."\">首页</a> ";
					if($this->curpage!=1) $result.="<a href=\"".str_replace("{page}",$this->curpage-1,$this->url)."\">上一页</a> ";
					if($this->curpage!=$this->totalpage) $result.="<a href=\"".str_replace("{page}",$this->curpage+1,$this->url)."\">下一页</a> ";				
					if($this->curpage!=$this->totalpage) $result.="<a href=\"".str_replace("{page}",$this->totalpage,$this->url)."\">末页</a> ";
					$result.=$this->totalnum."条";
					if($this->totalpage!=1) $result.="";
					$result.=$this->curpage."/".$this->totalpage."页";
					$inputName=Util::formId($this->param);
				}
				return $result;
				break;
				case 3://AJAX
				if($this->totalpage>1) {
					if($this->curpage!=$this->totalpage) $result.="<div class=\"button\" onMouseOver=\"this.className='button button-hover'\" onMouseOut=\"this.className='button'\" onclick=\"go('".str_replace("{page}",$this->curpage+1,$this->url)."');\" style=\"float:right\" title=\"下一页\"><span class=\"left\"></span><span class=\"center\"><span class=\"icon next\"></span></span><span class=\"right\"></span></div>";
					$result.="<div style=\"float:right; padding-left:4px; padding-right:4px;\"><select onchange=\"go('".str_replace("{page}","'+this.value",$this->url).");\">";
					
					if($this->pagernum==0) {
						$si=1;
						$ei=$this->totalpage;
					} else {
						$si=$this->curpage-$this->pagernum/2;
						$ei=$this->curpage+($this->pagernum/2-1);
						if($si<=0) {
							$si=1;
							if($this->pagernum<$this->totalpage) {
								$ei=$this->pagernum;
							} else {
								$ei=$this->totalpage;
							}
						} else if($ei>=$this->totalpage) {
							$ei=$this->totalpage;
							if($this->pagernum<$this->totalpage) {
								$si=$this->totalpage-$this->pagernum+1;
							} else {
								$si=1;
							}
						}
					}
					
					for(;$si<=$ei;$si++) {
						$result.="<option value=\"".$si."\" ";
						if($si==$this->curpage) {
							$result.="selected=\"selected\"";
						}
						$result.="> ".$si." / ".$this->totalpage." </option>";
					}
					$result.="</select></div>";
					if($this->curpage!=1) $result.="<div class=\"button\" onMouseOver=\"this.className='button button-hover'\" onMouseOut=\"this.className='button'\" onclick=\"go('".str_replace("{page}",$this->curpage-1,$this->url)."');\" style=\"float:right\" title=\"上一页\"><span class=\"left\"></span><span class=\"center\"><span class=\"icon prev\"></span></span><span class=\"right\"></span></div>";
				}
				global $page;
				if(isset($page)) {
					$page->page_total=$this->totalnum;
					if($this->curpage!=$this->totalpage) {
						$page->page_num=$this->size;
					} else {
						$page->page_num=$this->totalnum-(($this->totalpage-1)*$this->size);
					}
				}
				return $result;
				break;
				
				
				case 6://AJAX DOWN-UP MODE
				if($this->totalpage>1) {
//					echo $this->curpage;
					if($this->curpage!=$this->totalpage) $result.="<div class=\"pager_down\" onMouseOver=\"this.className='pager_down_hover'\" onMouseOut=\"this.className='pager_down'\" onmousedown=\"this.className='pager_down_click'\" onclick=\"go('".str_replace("{page}",$this->curpage+1,$this->url)."');\" style=\"float:right\" title=\"下一页\"></div>";
					$result.="<div style=\"float:right; padding-left:4px; padding-right:4px;\"><select onchange=\"go('".str_replace("{page}","'+this.value",$this->url).");\">";
					if($this->pagernum==0) {
						$si=1;
						$ei=$this->totalpage;
					} else {
						$si=$this->curpage-$this->pagernum/2;
						$ei=$this->curpage+($this->pagernum/2-1);
						if($si<=0) {
							$si=1;
							if($this->pagernum<$this->totalpage) {
								$ei=$this->pagernum;
							} else {
								$ei=$this->totalpage;
							}
						} else if($ei>=$this->totalpage) {
							$ei=$this->totalpage;
							if($this->pagernum<$this->totalpage) {
								$si=$this->totalpage-$this->pagernum+1;
							} else {
								$si=1;
							}
						}
					}
					
					for(;$si<=$ei;$si++) {
						$result.="<option value=\"".$si."\" ";
						if($si==$this->curpage) {
							$result.="selected=\"selected\"";
						}
						$result.="> ".$si." / ".$this->totalpage." </option>";
					}
					$result.="</select></div>";
					if($this->curpage!=1) $result.="<div class=\"pager_up\" onMouseOver=\"this.className='pager_up_hover'\" onMouseOut=\"this.className='pager_up'\" onmousedown=\"this.className='pager_up_click'\" onclick=\"go('".str_replace("{page}",$this->curpage-1,$this->url)."');\" style=\"float:right\" title=\"上一页\"></div>";
				}
				global $page;
				if(isset($page)) {
					$page->page_total=$this->totalnum;
					if($this->curpage!=$this->totalpage) {
						$page->page_num=$this->size;
					} else {
						$page->page_num=$this->totalnum-(($this->totalpage-1)*$this->size);
					}
				}
				return $result;
				break;
				
				
				case 4://HTML带文本框
				$result.="<form><div style=\"text-align:center; width:98%;\">";
				if($this->curpage==1){
					$result.="<span class=\"pagestyle\">首页</span> ";
					$result.="<span class=\"pagestyle\">上一页</span> ";
				}else{
					$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",1,$this->url)."\">首页</a></span> ";
					$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",$this->curpage-1,$this->url)."\">上一页</a></span> ";
				}
				if($this->curpage==$this->totalpage){
					$result.="<span class=\"pagestyle\">下一页</span> ";
					$result.="<span class=\"pagestyle\">末页</span> ";
				}else{
					$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",$this->curpage+1,$this->url)."\">下一页</a></span> ";
					$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",$this->totalpage,$this->url)."\">末页</a></span> ";
				}
				$result.="<span class=\"pagestyle\">(当前第".$this->curpage."页/共".$this->totalpage."页  每页显示".$this->size."条记录 共".$this->totalnum."条记录)</span>";
				$result.="<span class=\"pagestyle\"><input value=".$this->curpage." tabindex=\"1\" name=\"page\" id=\"page\" class=\"inp4\" type=\"text\" />&nbsp;&nbsp;<input name=\"Submit\" type=\"button\" id=\"pagebut\" class=\"but\" value=\"跳转\" tabindex=\"2\" onClick=\"if(document.getElementById('page').value!=''){window.location.href=('".$this->getNewUrl()."&".$this->param."='+document.getElementById('page').value);}else{alert('请输入页码');return false;}\" /></span>";
				$result.="</div></form>";
				return $result;
				break;
				case 5://HTML带下拉框
				if($this->totalpage>1){
					$result.="<form><div style=\"text-align:center; width:98%;\">";
					if($this->curpage==1){
						$result.="<span class=\"pagestyle\"> 首页 </span>";
						$result.="<span class=\"pagestyle\"> 上一页</span>";
					}else{
						$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",1,$this->url)."\"> 首页 </a></span>";
						$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",$this->curpage-1,$this->url)."\"> 上一页 </a></span>";
					}
					if($this->curpage==$this->totalpage){
						$result.="<span class=\"pagestyle\"> 下一页 </span>";
						$result.="<span class=\"pagestyle\"> 末页 </span>";
					}else{
						$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",$this->curpage+1,$this->url)."\"> 下一页 </a></span>";
						$result.="<span class=\"pagestyle\"><a href=\"".str_replace("{page}",$this->totalpage,$this->url)."\"> 末页 </a></span>";	
					}
					$result.="<span class=\"pagestyle\">(当前第".$this->curpage."页/共".$this->totalpage."页  每页显示".$this->size."条记录 共".$this->totalnum."条记录)</span>";
					$result.="<span class=\"pagestyle\">";
					$result.="<select id='page' onchange=\"window.location.href=('".$this->getNewUrl()."&".$this->param."='+document.getElementById('page').value);\">";
						for($i=1;$i<=$this->totalpage;$i++) {
							$result.="<option value=\"".$i."\" ";
							if($i==$this->curpage) {
								$result.="selected=\"selected\"";
							}
							$result.="> 第".$i."页</option>";
						}
						$result.="</select>";
					$result.="</span></div></form>";
					return $result;
				}
				break;
			}
		}
	}
	
	function getNewUrl(){
		$search=array("?".$this->param."={page}&","&".$this->param."={page}",$this->param."={page}");
		$replace=array("?","","");
		return str_replace($search,$replace,$this->url);
	}
	
}
?>