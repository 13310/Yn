<?php
header("Content-type:text/html;charset=utf-8");
//设置根目录
define('APP_PATH',__DIR__.'/');
//开启debug
define('APP_DEBUG',true);
//载入核心文件
require (APP_PATH.'framework/Framework.php');
//载入配置文件
$config = require (APP_PATH.'/config/config.php');
define('ROOT_PATH',$config['RootPath']);
//实例化框架类
Framework::run($config);