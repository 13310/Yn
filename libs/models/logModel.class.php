<?php
	class logModel{
		public static function log($user_id,$behavior){
			function getBrowser() {
				$sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
				if (stripos($sys, "Firefox/") > 0) {
					preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
					$exp = "火狐浏览器".$b[1];  //获取火狐浏览器的版本号
				} elseif (stripos($sys, "Maxthon") > 0) {
					preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
					$exp = "傲游浏览器".$aoyou[1];
				} elseif (stripos($sys, "MSIE") > 0) {
					preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
					//$exp = "Internet Explorer ".$ie[1];
					$exp = "IE浏览器".$ie[1];  //获取IE的版本号
				} elseif (stripos($sys, "OPR") > 0) {
					preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
					$exp = "Opera浏览器".$opera[1];
				} elseif (stripos($sys, "Chrome") > 0) {
					preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
					$exp = "Chrome浏览器".$google[1];  //获取google chrome的版本号
				} else {
					$exp = "未知浏览器";
				}
				return $exp;
			}
			DB::insert("yn_userlog",array(
				'user_id'=>$user_id,
				'log_time'=>date('Y-m-d H-i-s',time()),
				'log_ip'=>$_SERVER["REMOTE_ADDR"],
				'log_browser'=>getBrowser(),
				'log_behavior'=>$behavior
			));
		}
	}