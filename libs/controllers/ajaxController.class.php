<?php
	class ajaxController{
		function __construct() {
			if(empty($_GET['c'])&&empty($_POST['c'])){
				header("HTTP/1.1 403");
				exit();
			}
		}

		function index(){
			ajaxModel::run();
		}
	}