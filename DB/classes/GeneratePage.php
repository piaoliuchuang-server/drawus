<?php
//生成静态页
class GeneratePage {

	//备份已经存在的原文件
	function backup($savepath){
		if(file_exists($savepath)){
			copy($savepath,$savepath.".bak");
			$result="备份原文件成功.";
		}
		return $result;
	}
	
	//写文件
	function write($savepath,$contents){
		$fp=fopen($savepath,"w");
		fwrite($fp,$contents);
		fclose($fp);
		$result="生成成功!";	
		return $result;
	}
	
	//直接读取动态页文件生成，$url为带http://的完整路径
	function trend2static($url,$savepath,$backup=0){
		if($backup){
			$result=$this->backup($savepath);
		}
		if($contents=file_get_contents($url)){
			$result.=$this->write($savepath,$contents);						
		}
		return $result;	
	}
	
	//由模板文件替换标签
	function template2static($templatePath,$tags,$re_tags,$savepath,$backup=0){
		if($backup){
			$result=$this->backup($savepath);
		}
		if($contents=file_get_contents($templatePath)){
			$contents=str_replace($tags,$re_tags,$contents);
			$result.=$this->write($savepath,$contents);	
		}
		return $result;
	
	}


}

?>