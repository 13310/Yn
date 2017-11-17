<?php
	class loginModel extends Model {
		public static function haslogin(){
			if(!empty(sessionModel::getSession('user_id'))){
				return true;
			}
			// 未检测到登录,尝试启动自动登录
			if(self::autoLogon()){
				return true;
			}
			return false;
		}
		public static function logon(){
			$userData = $_POST;
			if(empty($userData['uname'])){
				self::failput('用户名未输入');
			}
			if(empty($userData['upass'])){
				self::failput('密码未输入');
			}
			if(empty($userData['uvalidate'])){
				self::failput('验证码未输入');
			}
			$userData['uname'] = parent::daddslashes($userData['uname']);
			$userData['checkMEM'] = !empty($userData['checkMEM'])?true:false;
			$userData['checkAvoid'] = !empty($userData['checkAvoid'])?true:false;
			// 数据进行数据库验证
			$uinfo = provModel::check($userData['uname'],$userData['upass'],$userData['uvalidate']);
			// 登录成功,将ID存储于session
			sessionModel::setSession('user_id',$uinfo['user_id']);
			sessionModel::setSession('user_name',$userData['uname']);
			// 存储云盘id
			sessionModel::setSession('drive_id',$uinfo['user_drive_id']);
			// 检测是否选择用户
			if($userData['checkMEM']){
				cookieModel::setCookie('UName',$userData['uname'],'999999999');
			}
			// 检测是否自动登录
			if($userData['checkAvoid']){
				$randPass = encryptModel::md5Base64Rand();
				DB::update('yn_user','auto_login_check="'.$randPass.'"','user_id="'.$uinfo['user_id'].'"');
				cookieModel::setCookie('UID',$uinfo['user_id'],'2592000');
				cookieModel::setCookie('UID_ckMd5',$randPass,'2592000');
			}
			logModel::log($uinfo['user_id'],"登录");
			self::output("登录成功");
		}
		public static function autoLogon(){
			$UID = cookieModel::getCookie('UID');
			$UID_ckMd5 = cookieModel::getCookie('UID_ckMd5');
			$uinfo = provModel::autoCheck($UID,$UID_ckMd5);
			if($uinfo!=false){
				// 登录成功,将ID存储于session
				logModel::log($uinfo['user_id'],"自动登录");
				sessionModel::setSession('user_id',$uinfo['user_id']);
				sessionModel::setSession('user_name',$uinfo['user_name']);
				// 存储云盘id
				sessionModel::setSession('drive_id',$uinfo['user_drive_id']);
				return true;
			}
			return false;
		}
		public static function register(){
			$userData = $_POST;
			if(empty($userData['rname'])){
				self::failput('用户名未输入');
			}
			if(!isRightModel::unique_username($userData['rname'])){
				self::failput('用户名已存在');
			}
			if(!isRightModel::is_username($userData['rname'])){
				self::failput('用户名不符合规范');
			}
			if(empty($userData['rpass'])){
				self::failput('密码未输入');
			}
			if(!isRightModel::is_password($userData['rpass'])){
				self::failput('密码不符合规范');
			}
			if(empty($userData['remail'])){
				self::failput('邮箱未输入');
			}
			if(!isRightModel::unique_email($userData['remail'])){
				self::failput('邮箱已存在');
			}
			if(!isRightModel::is_email($userData['remail'])){
				self::failput('邮箱不符合规范');
			}
			if(empty($userData['rvalidate'])){
				self::failput('验证码未输入');
			}
			if(!validateModel::check($userData['rvalidate'])){
				self::failput('验证码错误');
			}
			$userData['rname'] = htmlspecialchars($userData['rname']);
			$userData['rpass'] = encryptModel::password_hashStr($userData['rpass']);
			$create_time = date('Y-m-d H-i-s',time());
			$netdisk_id = encryptModel::base64md5Str($userData['rname'].rand());
			$tempdata = array(
				'user_name'=>$userData['rname'],
				'user_passwd'=>$userData['rpass'],
				'user_email'=>$userData['remail'],
				'user_registered_time'=>$create_time,
				'user_drive_id'=>$netdisk_id,
				'user_registered_ip'=>$_SERVER["REMOTE_ADDR"]
			);
			$info = DB::insert('yn_user',$tempdata);
			if($info){
				$password = DB::getValue('yn_user','user_passwd',"user_name='".$userData['rname']."'");
				$userkey = encryptModel::password_hashStr($userData['rname'].$password);
				$user = encryptModel::base64_url_encode($userData['rname']."+".$userkey."+".time());
				$url = '{"u":"'.$user.'","k":"'.$userkey.'","e":"'.$userData['remail'].'","t":"'.time().'"}';
				$url = ROOT_PATH."/login/check/?p=".encryptModel::base64_url_encode($url);
				mailModel::activate_mail($userData['remail'],$url,'账户激活');
				self::output("注册成功");
			}else{
				self::errput("未知错误");
			}
		}
		public static function forget(){
			$userData = $_POST;
			if(empty($userData['fname'])){
				self::failput('用户名未输入');
			}
			if(empty($userData['femail'])){
				self::failput('邮箱未输入');
			}
			if(empty($userData['fvalidate'])){
				self::failput('验证码未输入');
			}
			$userData['fname'] = parent::daddslashes($userData['fname']);
			provModel::checkForget($userData['fname'],$userData['femail'],$userData['fvalidate']);
		}
		public static function activation(){
			$userData = $_POST;
			if(!isRightModel::except_email($userData['email'],$userData['user'])){
				self::errput('邮箱已被使用,不能用作激活账户');
			}
			$password = DB::getValue('yn_user','user_passwd',"user_name='".$userData['user']."'");
			$userkey = encryptModel::password_hashStr($userData['user'].$password);
			$user = encryptModel::base64_url_encode($userData['user']."+".$userkey."+".time());
			$url = '{"u":"'.$user.'","k":"'.$userkey.'","e":"'.$userData['email'].'","t":"'.time().'"}';
			$url = ROOT_PATH."/login/check/?p=".encryptModel::base64_url_encode($url);
			$status = mailModel::activate_mail($userData['email'],$url,'账户激活');
			if($status){
				self::output("发送成功");
			}
			self::errput("发送失败");
		}
		public static function activate_account($data){
			$data = json_decode(encryptModel::base64_url_decode($data),true);
			$userinfo = explode('+',encryptModel::base64_url_decode($data['u']));
			$userdata = DB::select('yn_user',"user_name='$userinfo[0]'");
			if(sizeof($userdata)==0||sizeof($userdata)>1){
				return "链接已失效";
			}
			$userdata = $userdata[0];
			if($userdata['user_validated']!=0){
				return '账户已经激活';
			}
			if($userinfo[2]!=$data['t']){
				return "链接已失效";
			}
			if(time()>strtotime("+1 days",$data['t'])){
				return "链接已失效";
			}
			$pd = $userdata['user_passwd'];
			if(!encryptModel::password_verifyStr(($userinfo[0].$pd),$userinfo[1])){
				return "链接已失效";
			}
			$status = DB::update('yn_user','user_email="'.$data['e'].'",user_validated="1"','user_name="'.$userinfo[0].'"');
			if($status){
				$netdisk_id = DB::getValue('yn_user','user_drive_id','user_name="'.$userinfo[0].'"');
				//提交准备创建云盘数据
				$fileModel = new fileModel();
				$fileModel->create_user_dir_770($netdisk_id);
				return '账户激活成功';
			}else{
				return "链接已失效";
			}
		}
	}