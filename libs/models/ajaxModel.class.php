<?php
	class ajaxModel extends Model {
		public static function run(){
			if(sizeof($_GET)){
				self::get();
			}
			if(sizeof($_POST)){
				self::post();
			}
		}
		private static function post(){
			if(empty($_POST['action'])){
				self::errput('not action');
			}
			$action = $_POST['action'];
			self::$action();
		}
		private static function get(){
			if(empty($_GET['action'])){
				self::errput('not action');
			}
			$action = $_GET['action'];
			self::$action();
		}
		private static function user_exists(){
			if(!sessionModel::getSession('user_id')){
				self::errput("非法访问");
			}
		}
		private static function u_file(){
			self::user_exists();
			$fileModel = new fileModel();
			if(empty($_FILES['file'])){
				self::errput('缺失关键');
			}
			$fileModel->upload_file($_FILES['file']);
		}
		private static function u_folder(){
			self::user_exists();
			$fileModel = new fileModel();
			if(empty($_FILES['file'])){
				self::errput('缺失关键');
			}
			if(empty($_POST['file_info'])){
				self::errput('缺失关键');
			}
			$affirm=!empty($_GET['affirm'])?$_GET['affirm']:"";
			$fileModel->upload_folder($_FILES['file'],$_POST['file_info']);
		}
		private static function checkUser(){
			$rname = $_GET['rname'];
			// 验证成功返回true
			if(isRightModel::unique_username($rname)){
				self::output('用户名可以使用');
			}
			self::failput('用户名已被使用');
		}
		private static function checkEmail(){
			$remail = $_GET['remail'];
			// 验证成功返回true
			if(isRightModel::unique_email($remail)){
				self::output('邮箱可以使用');
			}
			self::failput('邮箱已被使用');
		}
		private static function check_act(){
			$email = $_GET['email'];
			$user = $_GET['user'];
			// 验证成功返回true
			if(isRightModel::except_email($email,$user)){
				self::output('邮箱可以使用');
			}
			self::failput('邮箱已被使用');
		}
		private static function file(){
			self::user_exists();
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			$path = $_GET['p'];
			$fileModel = new fileModel($path);
			print_r($fileModel->getFile());
			exit();
		}
		private static function open(){
			self::user_exists();
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			$path = $_GET['p'];
			$fileModel = new fileModel($path);

			$fileModel->openFile();
		}
		private static function cut(){
			self::user_exists();
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			if(empty($_GET['append'])){
				self::errput('参数错误');
			}
			$affirm=!empty($_GET['affirm'])?$_GET['affirm']:"";
			$fileModel = new fileModel($_GET['p']);
			$fileModel->cut($_GET['append'],$affirm);
		}
		private static function copy(){
			self::user_exists();
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			if(empty($_GET['append'])){
				self::errput('参数错误');
			}
			$fileModel = new fileModel($_GET['p']);
			$affirm=!empty($_GET['affirm'])?$_GET['affirm']:"";
			$fileModel->copy($_GET['append'],$affirm);
		}
		private static function rename(){
			self::user_exists();
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			if(empty($_GET['append'])){
				self::errput('参数错误');
			}
			$fileModel = new fileModel($_GET['p']);
			$fileModel->rename($_GET['append']);
		}
		private static function del(){
			self::user_exists();
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			$fileModel = new fileModel($_GET['p']);
			$fileModel->del();
		}
		private static function trash_moveIn(){
			self::user_exists();
			if(empty($_GET['p'])||empty($_GET['append'])){
				self::errput('无路径');
			}
			$fileModel = new fileModel($_GET['p']);
			$fileModel->trash_moveIn($_GET['append']);
		}
		private static function new_folder(){
			self::user_exists();
			$fileModel = new fileModel();
			if(empty($_GET['append'])){
				self::errput('无路径');
			}
			$fileModel->new_folder($_GET['append']);
		}
		private static function new_file(){
			self::user_exists();
			$fileModel = new fileModel();
			if(empty($_POST['file_name'])){
				self::errput('参数错误');
			}
			if(empty($_POST['file_value'])){
				self::errput('参数错误');
			}
			$fileModel->new_file($_POST['file_name'],$_POST['file_value']);
		}
		private static function share(){
			self::user_exists();
			if(empty($_POST['u'])){self::errput('参数错误');}
			if(empty($_POST['m'])){self::errput('参数错误');}
			if(empty($_POST['s'])){self::errput('参数错误');}
			if(empty($_POST['n'])){self::errput('参数错误');}
			$info = array(
				'u'=>$_POST['u'],
				'm'=>$_POST['m'],
				'p'=>$_POST['p'],
				's'=>$_POST['s'],
				'n'=>$_POST['n']
			);
			$fileModel = new fileModel();
			$fileModel->c_share($info);
		}
		private static function unshare(){
			self::user_exists();
			if(empty($_GET['key'])){self::errput('参数错误');}
			$fileModel = new fileModel();
			$fileModel->c_unshare($_GET['key']);
		}
		private static function un_share(){
			self::user_exists();
			if(empty($_GET['key'])){self::errput('参数错误');}
			$shareModel = new shareModel();
			$shareModel->removeShare($_GET['key']);
		}
		private static function trash(){
			$data = trashModel::getAllTrashValue();
			if(sizeof($data)==0){
				self::waring("无数据");
			}
			print_r($data);
		}
		private static function Move_in_trash(){
			if(empty($_GET['p'])){
				self::errput('无路径');
			}
			$file = new fileModel($_GET['p']);
			$file->Move_in_trash();
		}
		private static function Del_all_trash(){
			$file = new fileModel();
			$file->Del_all_trash();
		}
		private static function Del_trash(){
			if(empty($_GET['id'])){
				self::errput('缺失参数');
			}
			$file = new fileModel();
			$file->Del_trash($_GET['id']);
		}
		private static function Move_out_trash(){
			if(empty($_GET['id'])){
				self::errput('缺失参数');
			}
			$file = new fileModel();
			if(!empty($_GET['assign'])){
				$file->Move_out_trash($_GET['id'],true);
			}else{
				$file->Move_out_trash($_GET['id']);
			}
		}
		private static function url_down(){
			if(empty($_GET['url'])){self::errput("参数错误");}
			$file = new fileModel();
			$file->url_down($_GET['url']);
		}
		private static function change_user_share_password(){
			if(empty($_GET['id'])||empty($_GET['value'])){self::errput("参数错误");}
			$shareModel = new shareModel();
			$shareModel->change_user_share_password($_GET['id'],$_GET['value']);
		}
	}