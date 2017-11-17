<?php
	class provImageModel{
		public static $fontCount = 4;
		public static $fontSize = 22;
		public static $fontName = APP_PATH.'static/font/prov.ttf';
		public static $fontBackgroundColor=array('255','255','255');
		public static $line = 10;
		private static $provCode;
		public static function createProvIMG(){
			$width=self::$fontCount*self::$fontSize+5;
			$height=self::$fontSize+18;
			$arrText='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			$image = imagecreate($width,$height);
			imagecolorallocate($image,self::$fontBackgroundColor[0],self::$fontBackgroundColor[1],self::$fontBackgroundColor[2]);
	//        创建验证码数字
			for($i=0;$i<self::$fontCount;$i++){
				$randText = $arrText[rand(0, strlen($arrText) - 1)];
				self::$provCode.=$randText;
				imagettftext($image, self::$fontSize, rand(-45, 45), self::$fontSize*$i+5, self::$fontSize+8, self::getRndColor($image),self::$fontName , $randText);
			}
	//        创建干扰线
			for ($i = 1; $i <= self::$line; $i ++) {
				imageline($image, rand(1, $width), rand(1, $height), rand(1, $width), rand(1, $height), self::getRndColor($image));
			}
	//        创建干扰点
			for ($i = 1; $i <= 200; $i ++) {
				imagesetpixel($image, rand(1, $width), rand(1, $height), self::getRndColor($image));
			}
			header("Content-Type:image/png");
			imagepng($image);
			imagedestroy($image);
			return self::$provCode;
		}
		private static function getRndColor($image){
			return imagecolorallocate($image, rand(0, 180), rand(0, 180), rand(0, 180));//随机字符较深颜色
		}
	}