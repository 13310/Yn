<?php
	class loginController extends Controller {
		function index(){
			if(loginModel::haslogin()){
				header("location:index");
			}
			// 调用金山词霸api使用句子
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,"http://open.iciba.com/dsapi/");
			curl_setopt($curl,CURLOPT_TIMEOUT,.1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			$output = curl_exec($curl);
			curl_close($curl);
			// 无法加载时使用默认的
			if(!empty($output)){
				$data = json_decode($output);
				$en = $data->content;
				$zh = $data->note;
				$pic = $data->picture2;
			}else{
				$en="Your time is limited, so don't waste it living someone else's life. – Steve Jobs";
				$zh = "你的生命有限，所以别浪费时间去过别人的生活。——史蒂夫•乔布斯";
				$pic = ROOT_PATH.'/static/img/bg.jpg';
			}
			parent::assign('login_en',$en);
			parent::assign('login_zh',$zh);
			parent::assign('login_pic',$pic);
			parent::assign('title','登录');
			parent::assign('deg',0);
			// 有post提交,登录
			if(!empty($_GET['a'])&&$_GET['a']=='login'){
				loginModel::logon();
			}else if(!empty($_GET['a'])&&$_GET['a']=='register'){
				loginModel::register();
			}else if(!empty($_GET['a'])&&$_GET['a']=='forget'){
				loginModel::forget();
			}else if(!empty($_GET['a'])&&$_GET['a']=='activation'){
				loginModel::activation();
			}else{
				parent::render();
			}
		}
		function check(){
			if(empty($_GET['p'])){
				exit();
			}
			$info = loginModel::activate_account($_GET['p']);
			parent::assign('title',$info);
			parent::assign('data',$info);
			parent::render();
		}
	}