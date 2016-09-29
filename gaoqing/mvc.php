<?php

class Yii{
	public $controller_id = 'index';
	public $action_id = 'index';
	public static $db;

	public function run(){
		session_start();
		//路由
		$this->route();
		//链接数据库
		require_once(ROOT.'/../System/Class/db.class.php');
		$db_config = require_once(ROOT.'/../Config/db.php');
		SELF::$db= new db($db_config['db_host'],$db_config['db_user'],$db_config['db_password'],$db_config['db_name'],'utf8');

		if(file_exists(ROOT.'/../Controller/'.$this->controller_id.'Controller.php')){
			include_once(ROOT.'/../Controller/'.$this->controller_id.'Controller.php');
		}else{
			exit(ROOT.'/../Controller/'.$this->controller_id.'Controller.php not exists');
		}
		//执行Controller
		$controller_class = $this->controller_id.'Controller';
		if(class_exists($controller_class)){
			$controller_obj  = new $controller_class;
			if(method_exists($controller_obj, 'init')){
				$controller_obj->init();
			}
			$action_function = $this->action_id;
			echo $controller_obj->$action_function();
		}else{
			exit($controller_class.' not exists');
		}
		SELF::$db->close();
	}

	 
	//渲染视图
	protected function render($file,$data=[]){
		$this->route();
		$view_path = ROOT.'/../View/'.$this->controller_id.'/'.$file.'.php';
		if(file_exists($view_path)){
			extract($data);
			ob_start();
			include_once($view_path);
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}else{
			exit('/View/'.$this->controller_id.'/'.$file.'.php not exists');
		}
	}

	public function include_dir($path){
		$handle = opendir($path);
		if($handle){
			while ( ($file_name=readdir($handle)) !==false ) {
				if($file_name=='.' || $file_name=='..') continue;
				include_once($path.'/'.$file_name);
			}
			closedir($handle);
		}
	}

	public function route(){
		$rt = $_SERVER['REQUEST_URI'];
		$rt = explode('?', $rt);
		$rt = explode('/',$rt[0]);

		(!empty($rt[1])&&stripos($rt[1], '.php')===false) && $this->controller_id=$rt[1];
		!empty($rt[2]) && $this->action_id=$rt[2];
	}
}

?>