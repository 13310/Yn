<?php
	class Model{
		protected static $_model;
		protected static $_table;
		public static $dbConfig = [];
		public static function ModelInit() {
			DB::init(self::$dbConfig['DBType'],self::$dbConfig['DBConfig']);
			// 获取表名
			if(!self::$_table){
				self::$_model = self::class;
				self::$_model = substr(self::$_model,0,-5);
				self::$_table = strtolower(self::$_model);
			}
			ML::init(self::$dbConfig['MLConfig']);
			// 启动session
			session_start();
		}
		// 屏蔽
		public static function daddslashes($str){
			// htmlspecialchars
			return (!get_magic_quotes_gpc())?addslashes($str):$str;
		}
		public static function errput($str){
			exit('{"status":"error","code":"'.$str.'"}');
		}
		public static function failput($str){
			exit('{"status":"fail","code":"'.$str.'"}');
		}
		public static function output($str){
			exit('{"status":"success","code":"'.$str.'"}');
		}
		public static function waring($str,$data=""){
			exit('{"status":"warn","code":"'.$str.'","data":"'.$data.'"}');
		}
		// 转码 默认转换成GBK
		public static function _changeChar($str,$newChar="gbk",$oldChar="utf-8"){
			$str=mb_convert_encoding($str,$newChar,$oldChar);
			return $str;
		}
	}