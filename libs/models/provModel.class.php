<?php
	class provModel extends Model {
		/*
		 * 校验用户名
		 * */
		public static function check($uname,$upass,$uvalidate){
			if(!validateModel::check($uvalidate)){
				self::errput('验证码输入错误');
			}
			$uinfo = DB::select('yn_user',"user_name='$uname'");
			if(sizeof($uinfo)==0){
				self::errput('用户不存在');
			}elseif (sizeof($uinfo)>1){
				self::errput('用户存在多位');
			}
			$uinfo = $uinfo[0];
			if(!$uinfo['user_validated']){
				self::waring("用户未激活",$uinfo['user_email']);
			}
			if(encryptModel::password_verifyStr($upass,$uinfo['user_passwd'])){
				return $uinfo;
			}else{
				logModel::log($uinfo['user_id'],"登录密码错误");
				self::errput('密码错误');
			}
		}
		public static function autoCheck($UID,$UID_ckMd5){
			$uinfo = DB::select('yn_user',"user_id='$UID'");
			if(sizeof($uinfo)==0){
				return false;
			}elseif (sizeof($uinfo)>1){
				return false;
			}
			$uinfo = $uinfo[0];
			if($uinfo['auto_login_check']==$UID_ckMd5){
				return $uinfo;
			}else{
				return false;
			}
		}
		public static function checkForget($fname,$femail,$fvalidate){
			if(!validateModel::check($fvalidate)){
				self::failput('验证码输入错误');
			}
			$uinfo = DB::select('yn_user',"user_name='$fname'");
			if(sizeof($uinfo)==0){
				self::failput('用户不存在');
			}elseif (sizeof($uinfo)>1){
				self::failput('用户存在多位');
			}
			$uinfo = $uinfo[0];
			if($uinfo['user_email']==$femail){
				self::errput('无法忘记');
			}else{
				self::failput('邮箱不正确');
			}
		}
	}