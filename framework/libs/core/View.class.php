<?php
	class View{
		protected $variables = array();
		protected $_controller;
		protected $_action;

		function __construct($controller,$action) {
			$this->_controller = strtolower($controller);
			$this->_action = strtolower($action);
		}
		// 分配变量
		public function assign($name,$value){
			$this->variables[$name] = $value;
		}
		// 渲染
		public function render(){
			// 渲染入内置函数
			extract($this->variables);
			include (APP_PATH.'framework/libs/function/beforeFunction.php');
			$defaultHeader = APP_PATH.'libs/views/header.php';
			$defaultFooter = APP_PATH.'libs/views/footer.php';
			$controllerHeader = APP_PATH.'libs/views/'.$this->_controller.'/header.php';
			$controllerFooter = APP_PATH.'libs/views/'.$this->_controller.'/footer.php';
			$controllerLayout = APP_PATH.'libs/views/'.$this->_controller.'/'.$this->_action.'.php';
			// 页头文件
			if(file_exists($controllerHeader)){
				include ($controllerHeader);
			}else{
				include ($defaultHeader);
			}

			include ($controllerLayout);

			// 页脚文件
			if(file_exists($controllerFooter)){
				include ($controllerFooter);
			}else{
				include ($defaultFooter);
			}

		}
	}