<?php
	class Framework{
		protected static $_config = array();
		public static function run($config){
			//设置时区
			date_default_timezone_set('Asia/Shanghai');
			spl_autoload_register(array(self::class,'loadClass'));
			self::$_config = $config;
			self::setReporting();
			self::removeMagicQuotes();
			self::unregisterGlobals();
			self::setDbConfig();
			self::route();
		}
		// 路由处理
		private static function route(){
			$controllerName = self::$_config['defaultController'];
			$actionName = self::$_config['defaultAction'];
			$param = array();
			// 合并后使用配置的地址清除替换
			$url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$url = str_replace(self::$_config['RootPath'],'',$url);
			// 清除?之后内容,strpos 查找字符串首次出现的位置
			$position = strpos($url,'?');
			$url = $position === false ? $url : substr($url,0,$position);
			// 清除前后的"/",trim功能是清除字符串前后的相匹配的
			$url = trim($url,"/");
			// 清除后缀
			$url = str_replace('.php','',$url);
			$urlArray=array();
			if($url){
				// 使用/分割字符串,存入数组
				$urlArray = explode('/', $url);
				// 删除空的数组元素
				$urlArray = array_filter($urlArray);

				// 获取控制器名
				$controllerName = $urlArray[0];
				// 获取动作名
				array_shift($urlArray);
				$actionName = $urlArray ? $urlArray[0] : $actionName;
				// 获取url参数
				array_shift($urlArray);
				$param = $urlArray ? $urlArray : array();
			}
			// 判断控制器是否存在
			$controller = $controllerName.'Controller';
			if(!class_exists($controller)){
				self::page_404();
			}
			// 判断方法是否存在
			if(!method_exists($controller,$actionName)){
				// 判断此类是否存在握手属性
				if(!property_exists ($controller,"_Hand_over")){
					self::page_404();
				}
				// 将不存在的方法和后面的url地址存入数组并转交给index
				$param = array($actionName,$param);
				// 变更方法名
				$actionName="index";
			}
			$dipatch = new $controller($controllerName,$actionName,$urlArray);
			// 调用方法,第一个数组为类,方法. 第二个数组为参数
			call_user_func_array(array($dipatch,$actionName),$param);
		}
		// 检查开发环境
		private static function setReporting(){
			if(APP_DEBUG === true){
				error_reporting(E_ALL);
				ini_set('display_errors','On');
			}else{
				error_reporting(E_ALL);
				ini_set('display_errors','Off');
				ini_set('log_errors','On');
			}
		}
		// 检测敏感字符
		private static function stripSlashesDeep($value){
			$value = is_array($value) ? array_map(array(self::class,'stripSlashesDeep'),$value) : stripslashes($value);
			return $value;
		}
		// 检测敏感字符并删除
		private static function removeMagicQuotes(){
			if(get_magic_quotes_gpc()){
				$_GET = isset($_GET) ? self::stripSlashesDeep($_GET) : '';
				$_POST = isset($_POST) ? self::stripSlashesDeep($_POST) : '';
				$_COOKIE = isset($_COOKIE) ? self::stripSlashesDeep($_COOKIE) : '';
				$_SESSION = isset($_SESSION) ? self::stripSlashesDeep($_SESSION) : '';
			}
		}
		// 检测自定义全局变量并移除
		private static function unregisterGlobals(){
			if(ini_get('register_globals')){
				$array = array('_SESSION','_POST','_GET','_COOKIE','_REQUEST','_SERVER','_ENV','_FILES');
				foreach ($array as $value){
					foreach ($GLOBALS[$value] as $key => $var){
						if($var === $GLOBALS[$key]){
							unset($GLOBALS[$key]);
						}
					}
				}
			}
		}
		// 配置数据库信息
		public static function setDbConfig()
		{
			if (self::$_config['DBConfig']) {
				Model::$dbConfig = self::$_config;
				Model::ModelInit();
			}
		}
		private static function page_404(){
			header("HTTP/1.1 404 Not Found");
			include APP_PATH."/404.php";
			exit();
	}
		// 自动加载控制器和模型类
		private static function loadClass($class){
			$frameworks = __DIR__.'/libs/core/'.$class.'.class.php';
			$controllers = APP_PATH.'libs/controllers/'.$class.'.class.php';
			$models = APP_PATH.'libs/models/'.$class.'.class.php';
			if(file_exists($frameworks)){
				// 加载框架核心类
				include $frameworks;
			}elseif (file_exists($controllers)){
				// 加载应用控制器类
				include $controllers;
			}elseif (file_exists($models)){
				// 加载应用模型类
				include $models;
			}else{
				self::page_404();
			}
		}
	}