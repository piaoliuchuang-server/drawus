<?php
class GenerateDb extends DAO {
	//生成的时间
	private function createtime(){
		@date_default_timezone_set('Asia/Shanghai');
		return "//create-time ".date("Y-n-d G:i:s",gmmktime());
	}
	
	//写文件
	private function write($floder,$string,$table){
		//判断文件是否存在，如果存在自动备份到backup文件夹（带上日期）
		@date_default_timezone_set('Asia/Shanghai');
		//backup文件不存在就创建
			if(!is_dir(ROOT_PATH."/".$floder.'/backup/'))
				mkdir(ROOT_PATH."/".$floder.'/backup/');
			if(file_exists(ROOT_PATH."/".$floder.'/'.ucfirst($table).'.php')){
				copy(ROOT_PATH."/".$floder.'/'.ucfirst($table).'.php',ROOT_PATH."/".$floder.'/backup/'.ucfirst($table).date("Y-n-d G_i_s").'.php');
				$report=$table."存在，已经改名备份！<br/>";
			}
		$fp=fopen(ROOT_PATH."/".$floder.'/'.ucfirst($table).'.php','w');
		fwrite($fp,$string);
		fclose($fp);
		return $report;
	}
	
	//获取表列名
	function field($table){
		$sql=new SQL($table);
		$sql->criteria=new Criteria();
		$sql->criteria->pager=new Pager();
		$sql->criteria->pager->setSize(1);
		$sql->select();
		$rs=parent::query($sql);
		$numberfields=mysql_num_fields($rs); 
			for($i=0;$i<$numberfields;$i++){
				$arr[]=mysql_field_name($rs,$i);
			}
		return $arr;	
	}
	
	//获取表字段属性
	function flags($table){
		$sql=new SQL($table);
		$sql->criteria=new Criteria();
		$sql->criteria->pager=new Pager();
		$sql->criteria->pager->setSize(1);
		$sql->select();
		$rs=parent::query($sql);
		$numberfields=mysql_num_fields($rs);
			for($i=0;$i<$numberfields;$i++){
				$arr[]=mysql_field_flags($rs,$i);
			}
		return $arr;
	}
	
	//获取数据库表名
	function table_name(){
		$str_sql=mysql_list_tables(DB_NAME);
			while($data=mysql_fetch_array($str_sql))
			{
				$arr[]=$data[0];		
			}
		return $arr;
	}
	
	//生成entity文件夹对应表的文件
	function make_entity($table){
		$field_name=$this->field($table);
			foreach($field_name as $field_name){
				$string_dim.="	private $".$field_name.";\r\n";
				$string_function.="	function set".ucfirst($field_name)."($".$field_name.") {\r\n		\$this->".$field_name."=$".$field_name.";\r\n	}\r\n \r\n	function get".ucfirst($field_name)."() {\r\n		return \$this->".$field_name.";\r\n	}\r\n";
			}
		//生成的时间
		$createtime=$this->createtime();
		//组合字符串
		$string="<?php\r\n".$createtime."\r\nclass ".ucfirst($table)." {\r\n".$string_dim.$string_function."}\r\n?>";
		return $this->write("entity",$string,$table);
	}
	
	//生成dao文件夹对应表的文件
	function make_dao($table){
		$field_name=$this->field($table);
		$field_flags=$this->flags($table);
			foreach($field_name as $i=>$field_name){
				if($i==0){	
					$firstfield=$field_name;
						if(strpos("1".$field_flags[$i],"auto_increment")){
							$lastid="\r\n		$".$table."->set".ucfirst($firstfield)."(parent::lastId());";
						}
						else{
							$string_save="		\$sql->add(\"".$field_name."\",$".$table."->get".ucfirst($field_name)."());\r\n";
						
						}
				}
				else{
				//save的字符串
					$string_save.="		\$sql->add(\"".$field_name."\",$".$table."->get".ucfirst($field_name)."());\r\n";
				//updates的字符串
					$string_updates.="		\$sql->add(\"".$field_name."\",$".$table."->get".ucfirst($field_name)."());\r\n";
				}//end if $i==0
			//load的字符串
			$string_load.="			$".$table."->set".ucfirst($field_name)."(\$result['".$field_name."']);\r\n";	
			}
		//生成的时间
		$createtime=$this->createtime();
		//组合字符串
		$string="<?php\r\n".$createtime."\r\nclass ".ucfirst($table)."DAO extends DAO implements DAOInterface {\r\n\r\n	"
			."private \$table=\"".$table."\";\r\n\r\n	"
			."function save($".$table.") {\r\n		\$sql=new SQL(\$this->table);\r\n".$string_save."		\$sql->insert();\r\n		\$ret = parent::query(\$sql);".$lastid."\r\n		return \$ret;\r\n	}\r\n\r\n	"
			."function update($".$table.") {\r\n		\$criteria=new Criteria();\r\n		\$criteria->addRestrictions(Restrictions::eq(\"".$firstfield."\",$".$table."->get".ucfirst($firstfield)."()));\r\n		return \$this->updates($".$table.",\$criteria);\r\n	}\r\n\r\n	"
			."function updates($".$table.",\$criteria) {\r\n		\$sql=new SQL(\$this->table);\r\n		\$sql->criteria=\$criteria;\r\n".$string_updates."		\$sql->update();\r\n		return parent::query(\$sql);\r\n	}\r\n\r\n";
		$string.="	function delete($".$table.") {\r\n		if(!is_object($".$table.")) \$id=$".$table."; else \$id=$".$table."->get".ucfirst($firstfield)."();\r\n		\$criteria=new Criteria();\r\n		\$criteria->addRestrictions(Restrictions::eq(\"".$firstfield."\",\$id));\r\n		\$sql=new SQL(\$this->table);\r\n		\$sql->criteria=\$criteria;\r\n		\$sql->delete();\r\n		return parent::query(\$sql);\r\n	}\r\n\r\n\r\n	"
			."function deletes(\$criteria) {\r\n		\$sql=new SQL(\$this->table);\r\n		\$sql->criteria=\$criteria;\r\n		\$sql->delete();\r\n		return parent::query(\$sql);\r\n	}\r\n\r\n\r\n	"
			."function load(\$object) {\r\n		\$criteria=new Criteria();\r\n		if(!is_object(\$object)) \$criteria->addRestrictions(Restrictions::eq(\"".$firstfield."\",\$object)); else \$criteria=\$object;\r\n		\$sql=new SQL(\$this->table);\r\n		\$sql->criteria=\$criteria;\r\n		\$sql->select();\r\n		\$rs=parent::query(\$sql);\r\n";
		$string.="		if(parent::exist(\$rs)) {\r\n			$".$table."=new ".ucfirst($table)."();\r\n			\$result=parent::field(\$rs);\r\n".$string_load."			return $".$table.";\r\n		}\r\n	}\r\n\r\n	"
			."function rs(\$criteria) {\r\n		\$sql=new SQL(\$this->table);\r\n		\$sql->criteria=\$criteria;\r\n		\$sql->select();\r\n		return parent::query(\$sql);\r\n	}\r\n\r\n	"
			."function ls(\$criteria) {\r\n		return parent::getList(\$this->rs(\$criteria));\r\n	}\r\n}\r\n?>";
		return $this->write("dao",$string,ucfirst($table)."DAO");
	}
	
//生成数据所有表文件
	function make($type='')
	{
		foreach($this->table_name() as $table){
		switch($type){
			case "dao":
				$report.=$this->make_dao($table);
			break;
			case "entity":
				$report.=$this->make_entity($table);
			break;
			default:
				$report.=$this->make_entity($table);
				$report.=$this->make_dao($table);
			break;
		}
	}
		return $report;
	}
}

?>