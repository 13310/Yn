<?php
	class isRightModel extends Model {
		public static function is_username($username){
			$strLen = mb_strlen($username,'utf8');
			if(!preg_match('/^[\x{4e00}-\x{9fa5}a-z0-9A-Z_]+$/u',$username)){
				return false;
			}elseif ($strLen<=3||$strLen>=9){return false;}
			return true;
		}
		public static function is_password($password){
			$strLen = mb_strlen($password,'utf8');
			if(preg_match('/^[0-9]*$/',$password)){return false;}
			else if($strLen<6){return false;}
			return true;
		}
		public static function is_phone($phone){
			if(!preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/',$phone)){return false;}
			return true;
		}
		public static function is_email($email){
			if(!preg_match('/([a-zA-Z0-9]{2,}([a-zA-Z0-9_.-]+[a-zA-Z0-9])?)@([a-zA-Z]+[.]*([a-zA-Z0-9]*[-]?[a-zA-Z0-9]))\.([a-zA-Z]{2,13})/',$email))
			{return false;}
			return true;
		}
		public static function unique_username($str){
			$uinfo = DB::select("yn_user","user_name='$str'");
			if(sizeof($uinfo)==0){
				return true;
			}
			return false;
		}
		public static function unique_email($str){
			$uinfo = DB::select("yn_user","user_email='$str'");
			if(sizeof($uinfo)==0){
				return true;
			}
			return false;
		}
		public static function except_email($str,$except){
			$uinfo = DB::getValue('yn_user','user_name',"user_email='$str'");
			if(empty($uinfo)){
				return true;
			}
			if($uinfo == $except){
				return true;
			}
			return false;
		}
	}