<?php

class fileModel extends Model {
		// 主目录
		private $_HPath;
		// 回收目录
		private $_RPath;
		// 当前在磁盘的目录
		private $_LPath;
		// 当前目录
		private $_Path;
		private $user_id;
		public function __construct($path="") {
			$id =sessionModel::getSession("drive_id");
			//改变工作区
			$this->_HPath=APP_PATH."data/".$id."/home/";
			$this->_RPath=APP_PATH."data/".$id."/recycle/";
			// 解url编码
			$path = urldecode($path);
			$this->_Path = $path;
			$this->user_id = sessionModel::getSession('user_id');
			// 更新编码,防止无法打开文件夹
			$this->_LPath=$this->changeChar($this->_HPath.$path);
		}
		public function getFile(){
			// 更改工作区
			if(!@chdir($this->_LPath)){
				self::errput("读取失败");
			}
			// 将当前路径存入cookie
			cookieModel::setCookie("last_path",urlencode($this->_Path));
			sessionModel::setSession("last_path",$this->_Path);
			$dir=dir("./");
			$data = array();
			$fi = finfo_open(FILEINFO_MIME);;
			while (($file=$dir->read())!==false) {
				$_file = $this->changeChar($file,'utf-8','gbk');
				$_share = DB::getValue('yn_share','share_key',"share_file_name='$_file' and user_id='$this->user_id'");
				$_share = !empty($_share)>0?$_share:false;
				if(is_dir($file)){
					if($file=="."||$file==".."){continue;}
					// 输出所有文件夹
					$_path = $this->_Path."/".$_file;
					$_path = str_replace("//",'/',$_path);
					array_push($data, array(
						"id"=>encryptModel::base64_url_encode($file),
						"type"=>"folder",
						"name"=>$_file,
						"share"=>$_share,
						"path"=>urlencode($_path),
						"owner"=>fileowner($file),
						"ctime"=>date("Y-m-d H:i",filectime($file)),
						"xtime"=>date("Y-m-d H:i",filemtime($file))
					));
				}else{
					$mime=mime_content_type($file);
					//判断文件是否为默认的,提交给另一个文件读取
					if($mime=="application/octet-stream"){
						$mimeModel = new mimeModel($this->_LPath."/$file");
						$mime=$mimeModel->getType();
					}
					// 输出所有文件
					array_push($data, array(
						"id"=>encryptModel::base64_url_encode($file),
						"type"=>"file",
						"name"=>$_file,
						"size"=>$this->bytesize(filesize($file)),
						"path"=>urlencode($this->_Path."/$_file"),
						"fileType"=>filetype($file),
						"fileMime"=>$mime,
						"share"=>$_share,
						"owner"=>fileowner($file),
						"ctime"=>date("Y-m-d H:i",filectime($file)),
						"xtime"=>date("Y-m-d H:i",filemtime($file))
					));
				}
			}
			$pathArr = explode("/",$this->_Path);
			array_push($data,array(
				"url"=>$pathArr
			));
			return json_encode($data,true);
		}
		// 转码 默认转换成GBK
		public function changeChar($str,$newChar="gbk",$oldChar="utf-8"){
			return self::_changeChar($str,$newChar,$oldChar);
		}
		//        文件大小格式化
		public function byteSize($size){
			$kb = sprintf("%.2f",$size/1024);
			if($kb>1024){
				$mb = sprintf("%.2f",$kb/1024);
				if($mb>1024){
					$gb = sprintf("%.2f",$mb/1024);
					$gb = $gb."GB";
					return $gb;
				}
				$mb = $mb."MB";
				return $mb;
			}
			$kb = $kb."KB";
			return $kb;
		}
		// [严重问题]
		public function url_down($url){
			self::errput('禁止使用');
			$name = basename($url);
			$localPath = sessionModel::getSession("last_path");
			$name = $this->changeChar($this->_HPath.$localPath."/".$name);
			$fp_output = fopen($name, 'w');
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FILE, $fp_output);
			curl_exec($ch);
			curl_close($ch);
			if(is_file($name)){
				self::output("下载成功");
			}else{
				self::errput("下载失败");
			}
		}
		public function openFile(){
			$mime=mime_content_type($this->_LPath);
			//判断文件是否为默认的,提交给另一个文件读取
			if($mime=="application/octet-stream"){
				$mimeModel = new mimeModel($this->_LPath);
				$mime=$mimeModel->getType();
			}
			header("Content-Type:".$mime);
			$file_handle = @fopen($this->_LPath, "r");
			if(!$file_handle){
				self::errput("打开失败");
			}
			$file_type = explode('/',$mime)[0];
			if($file_type=='text'){
				while (!feof($file_handle)) {
					$line = fgets($file_handle);
					// 判断编码 并转换位utf-8
				echo $this->changeChar($line,'utf-8', mb_detect_encoding($line, array("ASCII","UTF-8","GB2312","GBK","BIG5")));
				}
			}else{
				while (!feof($file_handle)) {
					$line = fgets($file_handle);
					// 判断编码 并转换位utf-8
					echo $line;
				}
			}
			fclose($file_handle);
		}
		public function cut($name,$affirm){
			//旧文件的路径
			$oldfile = $this->_LPath;
			$localPath = sessionModel::getSession("last_path");
			// 判断文件是否存在
			$newFile = $this->changeChar($this->_HPath.$localPath."/".$name);
			if(file_exists($newFile)){
				if($affirm!=true){
					self::failput("01");
				}
			}
			if(@rename($oldfile,$newFile)){
				self::output("成功");
			}
			self::failput("失败");
		}
		public function copy($name,$affirm){
			//旧文件的路径
			$oldfile = $this->_LPath;
			$localPath = sessionModel::getSession("last_path");
			// 判断文件是否存在
			$newfile = $this->_HPath.$localPath."/".$name;
			if(is_file($oldfile)){
				if(file_exists($this->changeChar($newfile))){
					$num = substr_count($newfile,".");
					if($num==0){
						$newfile = $newfile." - 副本";
					}elseif($num==1){
						$newfile = str_replace('.'," - 副本.",$newfile);
					}else{
						$ads = strrpos($newfile,'.');
						$newfile = substr_replace($newfile,' - 副本.',$ads,1);
					}
					if(file_exists($this->changeChar($newfile))){
						$i=1;
						// 设置一个循环最大值,防止意外卡死
						while ($i<99){
							$i++;
							$testfile = str_replace(' - 副本'," - 副本 (".$i.")",$newfile);
							if (!file_exists($this->changeChar($testfile))){
								$newfile = $testfile;
								break;
							}
						}
					}
				}
				if(@copy($oldfile,$this->changeChar($newfile))){
					$size = filesize($this->changeChar($newfile))/1024/1024;
					$this->updateSpace($size,'add');
					self::output("成功");
				}
				self::failput("失败");
			}else{
				if(file_exists($newfile)){
					if($affirm!=true){
						self::failput("02");
					}
				}
				if($this->recurse_copy($oldfile,$newfile)){
					self::output("成功");
				}
				self::failput("失败");
			}
		}
		public function rename($name){
			//旧文件的路径
			$oldfile = $this->_LPath;
			$localPath = sessionModel::getSession("last_path");
			// 判断文件是否存在
			$newFile = $this->changeChar($this->_HPath.$localPath."/".$name);
			$changeNmae = false;
			if(file_exists($newFile)){
				$changeNmae = true;
				$i=0;
				while ($i<99){
					$i++;
					$testfile = str_replace('.'," (".$i.").",$newFile);
					if (!file_exists($this->changeChar($testfile))){
						$newFile = $testfile;
						$name = str_replace("."," (".$i.").",$name);
						break;
					}
				}
			}
			if(rename($oldfile,$newFile)){
				if($changeNmae){
					self::waring("01",$name);
				}
				self::output("成功");
			};
			self::errput("失败");
		}
		public function trash_moveIn($name){
			$localPath = sessionModel::getSession("last_path");
			$file = $this->_LPath;
			if(!file_exists($file)){self::errput("未找到");}
			if(is_file($file)){
				$size = filesize($file)/1024/1024;
				$this->updateSpace($size,'rem');
				if(!rename($file,$file.".qpg_disk.bak")){
					self::errput("失败");
				}
				if($this->trash_log($name,urlencode($file),$size,$localPath)){
					self::output('移入回收站成功');
				}
				self::errput('移入回收站失败');
			}else{
				$info = $this->addup_dir($file);
				if(!rename($file,$file.".qpg_disk.bak")){
					self::errput("失败");
				}
				$size = $info['size']/1024/1024;
				$this->updateSpace($size,'rem');
				if($this->trash_log($name,urlencode($file),$size,$localPath)){
					self::output('移入回收站成功');
				}
				self::errput('移入回收站失败');
			}
		}

	private function trash_log($file,$path,$size,$localPath){
		$time = date("Y年m月d日 H时i分s秒",time());
		$user = sessionModel::getSession('username');
		if(DB::insert('trash',"'$user','$file','$path','$size','$time','$localPath'")){
			return true;
		}
			return false;
	}
	/***
	 * 统计一个文件夹的大小即下面所有文件和文件夹的数量
	 * @param $dir_name
	 *
	 * @return array
	 */
		private function addup_dir($dir_name){
			// 初始化大小,文件数量,文件夹数量
			$size=0;$dir=0;$file=0;
			if ($handle = opendir($dir_name)){
				while (false!==($item=readdir($handle))){
					if ($item !='.' && $item !='..') {
						if (is_dir("$dir_name/$item")) {
							$data = $this->addup_dir("$dir_name/$item");
							$size+=$data['size'];
							$dir+=$data['dir'];
							$file+=$data['file'];
							$dir++;
						}
						else{
							$size += filesize("$dir_name/$item");
							$file++;
						}
					}
				}
				closedir( $handle );
			}
			return array('size'=>$size,'dir'=>$dir,'file'=>$file);
		}
		public function del(){
			$file = $this->_LPath;
			if(!file_exists($file)){self::errput("未找到");}
			if(is_file($file)){
				$size = filesize($file)/1024/1024;
				if(unlink($file)){
					$this->updateSpace($size,'rem');
					self::output("删除成功");
				}
				self::errput("未知错误");
			}else{
				if($this->delete_dir($file)){
					self::output("删除成功");
				}
				self::errput("未知错误");
			}
		}
		private function delete_dir($dir_name,$del_seif=true){
			if ($handle = opendir($dir_name)){
				$size = 0;
				while (false!==($item=readdir($handle))){
					if ($item !='.' && $item !='..') {
						if ( is_dir("$dir_name/$item")) {
							$this->delete_dir("$dir_name/$item");
						}
						else{
							$size += filesize("$dir_name/$item");
							unlink("$dir_name/$item");
						}
					}
				}
				closedir( $handle );
				if($del_seif){
					if(rmdir($dir_name)){
						$size = $size/1024/1024;
						$this->updateSpace($size,'rem');
						return true;
					};
				}else{
					$size = $size/1024/1024;
					$this->updateSpace($size,'rem');
					return true;
				}
			}
		}
		private function recurse_copy($src,$dst){
			$dir=opendir($src);
			@mkdir($dst);
			$size = 0;
			while(false!==($file=readdir($dir))){
				if(($file!='.' )&&($file!='..')){
					if(is_dir($src.'/'.$file)){
						$this->recurse_copy($src.'/'.$file,$dst.'/'.$file);
					}
					else{
						copy($src.'/'.$file,$dst.'/'.$file);

						$size += filesize($dst.'/'.$file);
					}
				}
			}
			closedir($dir);
			if(file_exists($dst)){
				$size = $size/1024/1024;
				$this->updateSpace($size,'add');
				return true;
			}
		}
		public function upload_file($file){
			// 统计文件大小
			$file_size = 0;
			foreach ($file['size'] as $value){
				$file_size += $value;
			}
			// 读取php.ini的上传限制大小
			$present_size = ( int ) ini_get('post_max_size');
			$file_size = $file_size/1024/1024;
			if ($file_size>=$present_size){
				self::failput('文件过大');
			}
			// 读取当前目录
			$localPath = sessionModel::getSession("last_path");
			$present_path = $this->changeChar($this->_HPath.$localPath);
			$success_num = 0;
			foreach ($file['name'] as $key=>$name){
				$tmp_name = $file['tmp_name'][$key];
				$file_name = $this->changeChar($name);
				if(move_uploaded_file($tmp_name,$present_path.'/'.$file_name)){
					$success_num++;
				}
			}
			$this->updateSpace($file_size,'add');
			if (sizeof($file['name'])==$success_num){
				self::output('上传完毕');
			}else{
				self::waring('364',sizeof($file['name'])-$success_num);
			}
		}
		// 如果文件创建失败将被循环创建上级目录,传入url
		private function while_mk_dir($init_val){

			$file_url_arr = explode('/',$init_val);
			$i =0;
			$file_url_arr_pop = $file_url_arr;
			while ($i<9){
				$i++;
				if(@mkdir(implode('/',$file_url_arr_pop))){
					if(!@mkdir($init_val)){
						$this->while_mk_dir($init_val);
					}
					break;
				}
				array_pop($file_url_arr_pop);
			}
		}
		// 更新大小
		private function updateSpace($size,$action){
			//更新大小
			$user = sessionModel::getSession('username');
			$Asize = DB::getValue('yn_user','file_size',"user_name='$user'");
			if($action=='add'){
				$file_size = $Asize+number_format($size,2);
				DB::update('yn_user',"file_size=$file_size","user_name='$user'");
			}else{
				$file_size = $Asize-number_format($size,2);
				DB::update('yn_user',"file_size=$file_size","user_name='$user'");
			}
		}
		public function upload_folder($file,$fileInfo){
			$_info = json_decode($fileInfo);
			$_par_name = $_info->_folderRoot;
			$localPath = sessionModel::getSession("last_path");
			$_root = $this->changeChar($this->_HPath.$localPath.'/'.$_info->_folderRoot);
			$_root_NC = $this->_HPath.$localPath.'/'.$_info->_folderRoot;
			$success = true;
			// 统计文件大小
			$file_size = 0;
			foreach ($file['size'] as $value){
				$file_size += $value;
			}
			// 读取php.ini的上传限制大小
			$present_size = ( int ) ini_get('post_max_size');
			$file_size = $file_size/1024/1024;
			if ($file_size>=$present_size){
				self::failput('文件过大');
			}
			if(is_dir($_root)){
				//更改文件名
				$i = 0;
				while ($i<99){
					$i++;
					$_temp = $_root."($i)";
					if(!is_dir($_temp)){
						$_root = $_temp;
						$_par_name = $_par_name."($i)";
						mkdir($_root);
						break;
					}
				}
				$success = false;
			}else{
				mkdir($_root);
			}
			$success_num = 0;
			$folder = array_unique((array)$_info);
			unset($folder['_folderRoot']);
			//创建文件
			foreach ($folder as $value){
				if($value==""){continue;}
				$file_url = $this->changeChar($_root.'/'.$value);
				if(!@mkdir($file_url)){
					$this->while_mk_dir($file_url);
				}
			}
			foreach ($file['name'] as $key=>$name){
				$tmp_name = $file['tmp_name'][$key];
				$file_url = $this->changeChar($_root.'/'.$_info->$name.'/'.$name);
				if(move_uploaded_file($tmp_name,$file_url)){
					$success_num++;
				}
			}
			//更新大小
			$this->updateSpace($file_size,'add');
			if (sizeof($file['name'])==$success_num){
				if($success){
					self::output('上传完毕');
				}else{
					self::waring('01',$_par_name);
				}
			}else{
				self::waring('364',sizeof($file['name'])-$success_num);
			}
		}
		public function new_folder($name){
			$localPath = sessionModel::getSession("last_path");
			$_root = $this->changeChar($this->_HPath.$localPath.'/'.$name);
			if(is_dir($_root)){
				self::failput('已存在');
			}
			if(mkdir($_root)){
				self::output('创建成功');
			}
			self::errput('意外错误');
		}
		//创建文件
		public function create_user_dir_770($id){
			$userDir = APP_PATH."data/".$id;
			if(file_exists($userDir)){return;}
			mkdir($userDir);
			mkdir($userDir.'/home');
			mkdir($userDir.'/recycle');
			mkdir($userDir.'/cooperation');
		}
		public function new_file($name,$value){
			$localPath = sessionModel::getSession("last_path");
			$_root = $this->changeChar($this->_HPath.$localPath.'/'.$name);
			$file = fopen($_root,"w");
			fwrite($file,$this->changeChar($value));
			fclose($file);
			if(is_file($_root)){
				self::output('创建成功');
			}
			self::failput('创建失败');
		}
		public function c_share($info){
			$info['u'] = urldecode($info['u']);
			$_root = $this->_HPath.'/'.$info['u'];
			$_root = str_replace('\\','/',$_root);
			$_root = str_replace('////','/',$_root);
			$_root = str_replace('//','/',$_root);
			//用户名
			$s_user = sessionModel::getSession('user_id');
			//url值
			$s_url_key = encryptModel::base64_url_encode($info['n']+rand());
			//创建时间
			$s_create_time = date('Y-m-d H-i-s',time());
			//相对路径
			$s_rele_path=$info['u'];
			//绝对路径
			$s_abse_path=urlencode($this->changeChar($_root));
			//文件密码
			$s_password = $info['p'];
			//文件名
			$s_filename = $info['n'];
			//文件mime类型
			$s_mime = $info['m'];
			//文件大小
			$s_size = $info['s'];
			$data = array(
				'user_id'=>$s_user,
				'share_key'=>$s_url_key,
				'share_time'=>$s_create_time,
				'share_path'=>$s_abse_path,
				'share_passwd'=>$s_password,
				'share_file_name'=>$s_filename,
				'share_file_mime'=>$s_mime,
				'share_file_size'=>$s_size,
				'share_validity'=>0
			);
			$status = DB::insert('yn_share',$data);
			if($status==1){
				self::output("分享成功");
			}else{
				self::errput("分享失败");
			}
		}
		public function c_unshare($key){
			$status = DB::delete('yn_share',"share_key='$key'");
			if($status>0){
				self::output("取消成功");
			}else{
				self::failput("取消失败");
			}
		}
		/**
		--------------
		 * trash 回收站
		--------------
		 **/
		public function Move_in_trash(){
			$file = $this->_LPath;
			if(!file_exists($this->_RPath)||!file_exists($file)){
				self::errput("错误:7");
			}
			$file_new_name = '$s_'.time().rand();
			$file_id =rand()+rand();
			$file_trash_time = date('Y-m-d H:i:s',time());
			$file_original_name = $this->changeChar(basename($file),'utf-8','gbk');
			$file_original_include_dir = 0;
			$file_original_include_file = 0;
			if(is_file($file)){
				$file_original_size = filesize($file)/1024/1024;
				$file_original_type = "file";
			}else{
				$file_original_type = "dir";
				$info = $this->addup_dir($file);
				$file_original_include_dir = $info['dir'];
				$file_original_include_file = $info['file'];
				$file_original_size = $info['size']/1024/1024;
			}
			$file_original_path =urlencode(sessionModel::getSession("last_path"));
			//移动文件到回收站
			if(rename($file,$this->_RPath.$file_new_name)){
				//直接将回收站数据存储在数据库中
				$status = DB::insert('yn_trash',array(
					'user_id'=>sessionModel::getSession('user_id'),
					'trash_id'=>$file_id,
					'original_size'=>$file_original_size,
					'original_name'=>$file_original_name,
					'original_path'=>$file_original_path,
					'original_type'=>$file_original_type,
					'original_include_dir'=>$file_original_include_dir,
					'original_include_file'=>$file_original_include_file,
					'trash_path'=>urlencode($this->_RPath.$file_new_name),
					'trash_time'=>$file_trash_time,
				));
				if($status){
					self::output("移至回收站成功");
				}
				self::errput("移至回收站失败");
			}else{
				self::errput("移至回收站失败");
			}
		}
		public function Del_all_trash(){
			$user_id = sessionModel::getSession('user_id');
			$status = DB::delete('yn_trash',"user_id='$user_id'");
			if(!$status){self::errput('未知错误');}
			$status = $this->delete_dir($this->_RPath,false);
			if($status){self::output('清空回收站成功');}
		}
		public function Del_trash($id){
			$user_id = sessionModel::getSession('user_id');
			$path = DB::getValue('yn_trash','trash_path',"user_id='$user_id' and trash_id='$id'");
			$path = urldecode($path);
			$status = DB::delete('yn_trash',"user_id='$user_id' and trash_id='$id'");
			if(!$status){self::errput('未知错误');}
			if(!file_exists($path)){
				self::output("删除成功");
			}
			if(is_file($path)){
				if(unlink($path)){
					self::output("删除成功");
				}
			}else{
				if($this->delete_dir($path)){
					self::output("删除成功");
				}
			}
		}
		public function Move_out_trash($id,$assign=false){
			$user_id = sessionModel::getSession('user_id');
			$info = DB::select("yn_trash","user_id='$user_id' and trash_id='$id'")[0];
			$path = $info['original_path'];
			$name = $info['original_name'];
			$t_path = $info['trash_path'];
			$t_path = urldecode($t_path);
			if($assign){
				$localPath = sessionModel::getSession("last_path");
				$path = $this->changeChar($this->_HPath.$localPath.'/'.$name);
			}else{
				$localPath = urldecode($path);
				$path = $this->_HPath.$localPath."/".$name;
			}
			if(!file_exists($path)){
				$this->while_mk_dir($path);
			}
			if(!$this->recurse_rename($t_path,$path)){
				DB::delete('yn_trash',"user_id='$user_id' and trash_id='$id'");
				self::output("还原成功");
			}
			self::errput("未知错误");
		}

		/***
		 * 移动函数
		 * @param $oldsrc
		 * @param $newsrc
		 * @param bool $replace
		 */
		private function recurse_rename($oldsrc,$newsrc,$replace=false){
				if(file_exists($newsrc)){
					if(is_dir($newsrc)){
						if($replace){
							$this->delete_dir($newsrc);
							rename($oldsrc,$newsrc);
						}
						$dir=opendir($oldsrc);
						while(false!==($file=readdir($dir))){
							if(($file!='.' )&&($file!='..')){
								$this->recurse_rename($oldsrc.'/'.$file,$newsrc.'/'.$file);
							}
						}
						closedir($dir);
					}else{
						if($replace){
							unlink($newsrc);
							rename($oldsrc,$newsrc);
						}
						$ext = substr(strrchr($newsrc, '.'), 1);
						if(empty($ext)){
							$i = 0;
							while ($i<99){
								$i++;
								$_temp = $newsrc."($i)";
								if(!is_dir($_temp)){
									$newsrc = $_temp;
									break;
								}
							}
						}else{
							$i = 0;
							$newsrc = substr_replace($newsrc,'',strrpos($newsrc, '.'));
							while ($i<99){
								$i++;
								$_temp = $newsrc."($i).".$ext;
								if(!is_dir($_temp)){
									$newsrc = $_temp;
									break;
								}
							}
						}
						rename($oldsrc,$newsrc);
					}
				}else{
					rename($oldsrc,$newsrc);
				}
		}
		private function dir_to_zip(){

		}
	}