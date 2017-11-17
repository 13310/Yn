<?php
	class sessionModel{
		public static function setSession($key,$value){
			$_SESSION[$key]=$value;
		}
		public static function getSession($key){
			return !empty($_SESSION[$key])?$_SESSION[$key]:"";
		}
		public static function removeSession(){
			// 销毁所有session
			session_destroy();
			session_unset();
		}
	}