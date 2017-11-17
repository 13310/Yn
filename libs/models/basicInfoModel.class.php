<?php
	class basicInfoModel extends  Model {
		public static function getUserName(){
			return sessionModel::getSession('user_name');
		}
		public static function getUserID(){
			return sessionModel::getSession('user_id');
		}
		public static function getUserInfo($name='user_name'){
			$id = sessionModel::getSession('user_id');
			return DB::getValue('yn_user',$name,"user_id='$id'");
		}
		public static function getUserShare(){
			$id = sessionModel::getSession('user_id');
			return sizeof(DB::select('yn_share',"user_id='$id'"));
		}
	}