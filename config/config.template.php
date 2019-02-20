<?php
// 将此文件修改为config.php即可
 $config = array(
 	// 数据库配置
	 'DBConfig'=>array(
		 "db_host"=>"", // docker中填写mysql容器名
		 "db_database"=>"",
		 "db_user"=>"",
		 "db_pass"=>""
	 ),
	 // 邮件配置
	 'MLConfig'=>array(
	 	 "user"=>"", // 邮箱地址
		 "password"=>"", // 邮箱密码
		 "host"=>"", // SMTP地址
		 "port"=>"", // 端口
		 'type'=>"smtp"
	 ),
	 // 其他配置
	 'DBType'=>'sql',
	 'defaultController'=>'home',
	 'defaultAction'=>'index',
	 'RootPath'=>'' // 服务器地址
 );
 return $config;