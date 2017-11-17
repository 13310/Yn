<?php
/**
 * Created by PhpStorm.
 * User: neio
 * Date: 2017/10/9
 * Time: 20:01
 */
	class cookieModel{
		public static function setCookie($key,$value,$expire="86400",$path="",$domain="",$secure=false,$httponly=false){
			setrawcookie($key,$value,(time()+$expire),$path,$domain,$secure,$httponly);
		}
		public static function getCookie($key){
			return !empty($_COOKIE[$key])?$_COOKIE[$key]:"";
		}
		public static function removeCookie($key){
			setrawcookie($key,"",time()-1);
		}
	}