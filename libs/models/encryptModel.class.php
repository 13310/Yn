<?php
	class encryptModel{
		public static function base64_url_encode($input){
			return strtr(base64_encode($input), '+/=', '._-');
		}

		public static function base64_url_decode($input) {
			return base64_decode(strtr($input, '._-', '+/='));
		}
		public static function md5Base64Rand(){
			return md5(self::base64_url_encode(mt_rand()));
		}
		public static function base64Md5Rand(){
			return self::base64_url_encode(md5(mt_rand()));
		}
		public static function base64md5Str($str){
			return self::base64_url_encode(md5($str));
		}
		public static function cryptStr($str){
			return crypt($str,'y2');
		}
		public static function password_hashStr($str){
			return password_hash($str,PASSWORD_DEFAULT);
		}
		public static function password_verifyStr($str,$pwd){
			return password_verify($str,$pwd);
		}
	}