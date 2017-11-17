<?php
	class indexController{
		function __construct() {
			// 判断是否登录
			if(!loginModel::haslogin()){
				header("location:".ROOT_PATH."/login");
			}

			// 此处填写单用户登录判断[!功能不需要]
			/*
			 * 实现方法
			 * 在数据库存储session id ,每次操作判断session id*/
		}

		function index(){
			header("location:".ROOT_PATH."/home");
		}
	}