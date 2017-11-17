<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	class ML{
		public static $mail;
		public static function init($MLconfig=array()){
			include_once APP_PATH."framework/libs/mail/Exception.php";
			include_once APP_PATH."framework/libs/mail/PHPMailer.php";
			include_once APP_PATH."framework/libs/mail/SMTP.php";
			if(sizeof($MLconfig)==0){
				exit('初始化邮件系统失败');
			}
			$fn = strtoupper($MLconfig['type']);
			if(!method_exists(self::class,$fn)){
				exit('不存在此邮件类型');
			}
			self::$mail=new PHPMailer();
			self::$fn();
			self::$mail->Host=$MLconfig['host'];
			self::$mail->Port=$MLconfig['port'];
			self::$mail->From=$MLconfig['user'];
			self::$mail->Username=$MLconfig['user'];
			self::$mail->Password=$MLconfig['password'];
		}
		public static function set($address,$Subject,$name="user"){
			self::$mail->AddAddress($address,$name);
			self::$mail->FromName="青苹果云盘系统";
			self::$mail->Subject=$Subject;
		}
		public static function send($content=""){
			if($content==""){exit("邮件无正文内容");}
			self::$mail->CharSet = 'UTF-8';
			self::$mail->Body=$content;
			self::$mail->MsgHTML($content);
			self::$mail->IsHTML(true);
			if(self::$mail->send()){
				return true;
			}
			return false;
		}
		public static function SMTP(){
			self::$mail->IsSMTP();
			self::$mail->SMTPDebug = 0;
			self::$mail->SMTPAuth=true;
			self::$mail->SMTPSecure = 'ssl';
			self::$mail->SMTPKeepAlive=true;
		}
	}