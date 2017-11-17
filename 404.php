<!doctype html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>404-云盘系统</title>
	<style>
		html,body{width: 100%;height: 100%;margin: 0}
		.wrapper{
			background: url("<?php echo ROOT_PATH?>/static/img/404.jpg");
			width: 100%;
			height:100%;
			overflow: hidden;
			background-size: cover;
		}
		.place{
			margin:40vh auto;
			width: 500px;
			color: #ffffff;
			height: 300px;
		}
		.place h1{
			font-size: 60px;
			font-weight: 100;
		}
		.place a{color: #fff;text-decoration: none}
		.place a:hover{text-decoration: underline}
	</style>
</head>
<body>
	<div class="wrapper">
		<div class="place">
			<h1>404</h1>
			<p>您正在尝试访问另一个平行时空中的页面，或许您可以先尝试返回上一页或者首页</p>
			<a href="javascript:history.go(-1)">上一页</a>
			<a href="<?php echo ROOT_PATH?>">首页</a>
		</div>
	</div>
</body>
</html>