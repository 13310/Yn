<?php
	class shareModel extends Model {
		private static $user;
		public function __construct() {
			self::$user = sessionModel::getSession('user_id');
		}
		public function share_get_all(){
			$temp = array();
			$all_share = DB::select('yn_share',"user_id='".self::$user."'");
			foreach ($all_share as $key=>$value){
				if(file_exists(urldecode($value['share_path']))){
					$isDir =false;
					if(is_dir(urldecode($value['share_path']))){
						$isDir=true;
					}
					array_push($temp,array(
						'name'=>$value['share_file_name'],
						'dir'=>$isDir,
						'size'=>$value['share_file_size'],
						'time'=>$value['share_time'],
						'url'=>ROOT_PATH."/s/".$value['share_key'],
						'password'=>$value['share_passwd'],
						'num_of_download'=>$value['share_download_count'],
						'num_of_look'=>$value['share_view_count'],
						'num_of_save'=>$value['share_save_count'],
						'key'=>$value['share_key']
					));
				}else{
					continue;
				}
			}
			return $temp;
		}
		public function removeShare($key){
			$status = DB::delete('yn_share',"share_key='$key' and user_id='".self::$user."'");
			if($status>0){
				self::output("取消成功");
			}else{
				self::failput("取消失败");
			}
		}
		public function change_user_share_password($key,$passwd){
			$status = DB::update('yn_share',"share_passwd='$passwd'","share_key='$key' and user_id='".self::$user."'");
			if($status>0){
				self::output("更改成功");
			}else{
				self::failput("更改成功");
			}
		}
		public function read_share($code){
			$data = DB::select('yn_share',"share_key='".$code."'");
			if(sizeof($data)!=1){
				return false;
			}
			return $data;
		}
		public function share_down($code){
			$data = json_decode(encryptModel::base64_url_decode($code),true);
			if(time()>strtotime("+1 days",$data['t'])){
				header("HTTP/1.1 404 Not Found");
			}
			print_r($data);
			$path = DB::getValue('yn_share','share_path',"share_key='".$data['c']."'");
			$path = urldecode($path);
			if(!file_exists($path)){
				header("HTTP/1.1 404 Not Found");
			}
			Header( "Content-type:  application/octet-stream ");//设置内容类型为字节流
			Header( "Accept-Ranges:  bytes ");//设置文件所接收的范围为字节
			Header( "Accept-Length: " .$data['s']);//设置文件接收的最大长度
			header( "Content-Disposition:  attachment;  filename= {$data['n']}");//内容设置为：附件
			echo file_get_contents($path);
			readfile($path);//读一个文件，如果成功则输出到
		}
	}