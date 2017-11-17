<?php
	class validateModel{
		public static function check($str){
			if(strcasecmp($str,sessionModel::getSession('provCode'))!=0){
				return false;
			}
			return true;
		}
	}