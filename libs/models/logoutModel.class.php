<?php
	class logoutModel{
		public static function haslogin(){
			if(!empty(sessionModel::getSession('user_id'))){
				return true;
			}
			return false;
		}
		public static function run(){
			// 销毁session
			logModel:log(sessionModel::getSession('user_id'),"登出");
			sessionModel::removeSession();
			// 销毁自动登录
			if(!empty(cookieModel::getCookie('UID'))){
				cookieModel::removeCookie('UID');
				cookieModel::removeCookie('UID_ckMd5');
			}
		}
	}