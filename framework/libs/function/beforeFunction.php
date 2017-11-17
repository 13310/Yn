<?php
/**
 * Created by PhpStorm.
 * User: neio
 * Date: 2017/10/10
 * Time: 8:59
 */
	// 读取Cookie值
	function getCookie($key){
		echo cookieModel::getCookie($key);
	}
	// 检测checkbox是否有对应值
	function checkCheckBox($key){
		echo !empty(cookieModel::getCookie($key))?"checked":"";
	}
	function head(){
		echo '<link rel="stylesheet" href="'.ROOT_PATH.'/static/css/normalize.css" type="text/css" />'."\n";
		echo '<link rel="stylesheet" href="'.ROOT_PATH.'/static/css/font.css" type="text/css" />'."\n";
		echo '<link rel="stylesheet" href="'.ROOT_PATH.'/static/css/main.css" type="text/css" />'."\n";
		echo '<link rel="shortcut icon" href="'.ROOT_PATH.'/static/img/favicon.ico">';
	}
	function foot(){
		echo '<script src="'.ROOT_PATH.'/static/js/main.js"></script>';
		echo '<script>window.present="'.ROOT_PATH.'"</script>';
	}
	function getUrl(){
		echo ROOT_PATH;
	}