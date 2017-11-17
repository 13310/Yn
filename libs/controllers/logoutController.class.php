<?php
	class logoutController extends Controller {
		function index(){
			//判断是否登录
			if(logoutModel::haslogin()){
				logoutModel::run();
				header('location:login');
			}else{
				header('location:login');
			}
		}
	}