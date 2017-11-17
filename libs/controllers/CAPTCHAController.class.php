<?php
	class CAPTCHAController{
		public function index(){
			$provCode = provImageModel::createProvIMG();
			sessionModel::setSession('provCode',$provCode);
		}
	}