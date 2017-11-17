<?php
	class homeController extends Controller {
		// 子行为
		private $childAction=array();
		public function __construct( $controller, $action,$urlArray ) {
			if(!loginModel::haslogin()){
				header("location:".ROOT_PATH."/login");
			}
			parent::__construct( $controller, $action );
			$this->childAction = $urlArray;
			$this->assign('UserName',basicInfoModel::getUserName());
			$this->assign('ASize',basicInfoModel::getUserInfo('total_storage_size'));
			$this->assign('TSize',basicInfoModel::getUserInfo('file_size'));
			$this->assign('RSize',basicInfoModel::getUserInfo('recycled_size'));
			$this->assign('CreateTime',basicInfoModel::getUserInfo('user_registered_time'));
			$this->assign('UserEmail',basicInfoModel::getUserInfo('user_email'));
			$this->assign('shareNum',basicInfoModel::getUserShare());
		}

		function index(){
			//主页
			$this->assign('title','我的信息');
			$this->render();
		}
		function netdisk(){
			//网盘
			$this->assign('title','我的文件');
			$this->render();
		}
		function share(){
			//分享
			$shareValue = new shareModel();
			$all_share = $shareValue->share_get_all();
			$this->assign('title','我的分享');
			$this->assign('all_share',$all_share);
			$this->render();
		}
		function account(){
			//账户
			if(sizeof($this->childAction)==0){
				$this->assign('title','账户信息');
				$this->assign('contain','info');
			}else{
				switch ($this->childAction[0]){
					case 'history':
						$this->assign('title',"用户日志");
						$user_id = sessionModel::getSession('user_id');
						$data = DB::select(array(
							"table"=>"yn_userlog",
							"key"=>"log_time,log_behavior",
							"where"=>"user_id=$user_id",
							"order"=>"log_time"
						));
						$this->assign('contain',"history");
						$this->assign('data',$data);
						break;
					case 'info':
						$this->assign('title',"账户信息");
						$this->assign('contain',"info");
						break;
					default:
						// 这里理论应该执行404
						$this->assign('title',"账户信息");
						$this->assign('contain',"info");
						break;

				}
			}
			$this->render();

		}
		function recycle(){
			//回收站
			$this->assign('title','回收站');
			$this->render();
		}
	}