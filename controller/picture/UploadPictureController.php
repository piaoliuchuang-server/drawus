<?php
/**
 * 处理上传图片
 */
class UploadPictureController extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function run()
	{
		header('Content-Type: text/html; charset=UTF-8');
		ini_set('date.timezone','Asia/Shanghai');//时区
		$inputname='picfile';//表单文件域name
		
		$res = new Result_process();
		$res->setSuccess_return(Game_result_Params::SUCCESS);
		$err = "";
		$msg = "''";
		
		$upfile=@$_FILES[$inputname];
		if(!isset($upfile))
		{
			$err='文件域的name错误';
			$this->AddUserResultHandle(false);
			return;
		}
		
		if($upfile['error'] > 0)
		{
			switch($upfile['error'])
			{
				case 1://文件大小超过了php.ini定义的upload_max_filesize值
					$err = 'File exceeded upload_max_filesize in php.ini';
					$res->_addErrorCode($err);
					break;
				case 2://文件大小超过了HTML定义的MAX_FILE_SIZE值
					$err = 'File exceeded max_file_size in HTML';
					$res->_addErrorCode($err);
					break;
				case 3://文件上传不完全
					$err = 'File only partially uploaded';
					$res->_addErrorCode($err);
					break;
				case 4://无文件上传
					$err = 'No file uploaded';
					$res->_addErrorCode($err);
					break;
				case 6://在php.ini文件中没有指定临时目录upload_tmp_dir
					$err = 'Cannot upload file: No temp directory specified';
					$res->_addErrorCode($err);
					break;
				case 7://将文件写入磁盘失败
					$err = 'Upload failed: Cannot write to disk';
					$res->_addErrorCode($err);
					break;
			}
			
			if ($res->_existError()){
				$res->AddUserResultHandle(false);
				return false;
			}
		}
		
		//Does the file have the right MIME type?
		if ($upfile['type'] != 'text/plain')
		{
			$err = 'Problem:file is not plain text';
			$res->_addErrorCode($err);
			$res->AddUserResultHandle(false);
			return false;
		}
		
		//put the file where we'd like it
		$upfile_path = '../pictures/'.$upfile['name'];
		
		//确保所处理文件已经被上传，而且不是一个本地文件
		if(is_uploaded_file($upfile['tmp_name']))
		{
			if(!move_uploaded_file($upfile['tmp_name'], $upfile_path))
			{
				$err = 'Problem: Coule not move file to destination directory';
				$res->_addErrorCode($err);
				$res->AddUserResultHandle(false);
				return false;
			}
		}
		else 
		{
			$err = 'Problem: Possible file upload attack. Filename:';
			$err .= $upfile['name'];
			$res->_addErrorCode($err);
			$res->AddUserResultHandle(false);
			return false;
		}
		
		$err = 'File uploaded successfully';
		$res->AddUserResultHandle(true);
		
		//remove possible HTML and PHP tags from the file's contents --- strip_tags()
		$contents = file_get_contents($upfile_path);
		$contents = strip_tags($contents);
		file_put_contents($upfile['name'], $contents);
		
		//show what was uploaded
		echo nl2br($contents);
		
		
			
	}
}
?>