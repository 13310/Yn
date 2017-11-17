<?php
	class sController extends Controller {
		protected $_Hand_over;
		private  $shareValue;
		function __construct( $controller, $action ) {
			parent::__construct( $controller, $action );

			$this->shareValue = new shareModel();
		}

		function index($action=""){
			$share_data = $this->shareValue->read_share($action);
			if(!$share_data){
				$this->assign("title","链接失效了 -青苹果云盘");
				$this->assign("status",false);
			}else{
				$share_data = $share_data[0];
				if(!file_exists(urldecode($share_data['share_path']))){
					$this->assign("title",$share_data['share_file_name']."链接失效了 -青苹果云盘");
					$this->assign("status",false);
					$this->render();
					die();
				}
				$this->assign("status",true);
				$this->assign("file_name",$share_data['share_file_name']);
				$this->assign("file_time",$share_data['share_time']);
				$this->assign("file_code",$share_data['share_key']);
				$pass_tips ="";
				$pass =false;
				if(!empty($share_data['share_passwd'])){
					$pass =true;
				}
				if(!empty($_GET['p'])){
					if($_GET['p'] == $share_data['share_passwd']){
						$pass =false;
						cookieModel::setCookie('_s_pd',$_GET['p'],600);
					}else{
						$pass_tips="密码验证失败";
					}
				}
				if(!empty(sessionModel::getSession('user_id'))&&sessionModel::getSession('user_id')==$share_data['user_id']){
					$pass =false;
					$pass_tips="";
				}elseif(!empty(cookieModel::getCookie('_s_pd'))&&cookieModel::getCookie('_s_pd')==$share_data['share_passwd']){
					$pass =false;
					$pass_tips="";
				}
				if($pass){
					$this->assign("title","私密分享 -青苹果云盘");
				}else{
					$this->assign("title",$share_data['share_file_name']."下载 -青苹果云盘");
				}
				$this->assign("file_password",$pass);
				$this->assign("pass_tips",$pass_tips);
				$this->assign("file_size",$share_data['share_file_size']);
				$this->assign("file_user",DB::getValue('yn_user',"user_name","user_id = '".$share_data['user_id']."'"));
				//生成url
				$down_url = '{"n":"'.$share_data['share_file_name'].'","c":"'.$share_data['share_key'].'","t":"'.time().'","s":"'.$share_data['share_file_size'].'"}';
				$down_url = encryptModel::base64_url_encode($down_url);
				$down_url = ROOT_PATH."/s/down/".$down_url;
				$this->assign("url",$down_url);
			}
			$this->render();
		}
		function down($a=""){
			$this->shareValue->share_down($a);
		}
	}