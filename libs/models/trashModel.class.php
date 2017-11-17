<?php
	class trashModel{
		private $user;
		public static function getAllTrashValue(){
			$user = sessionModel::getSession('user_id');
			$data = DB::select('yn_trash','user_id="'.$user.'"');
			return json_encode($data);
		}
	}