<?php

/**
 * 自动加载类
 * @author gaoqing
 * 2015年10月16日
 */
class Loader{
	
	private $basePath;
	
	public function __construct($basePath) {
		$this->basePath = $basePath;
	}
	
	/**
	 * 自动加载指定类文件
	 * @author gaoqing
	 * 2015年10月16日
	 * @param string $basePath 加载类文件的基本路径
	 * @param string $classname 被加载的类名称
	 * @return void 空
	 */
	public function loader($classname) {
	
		/*
		 * 1、得到基本路径下的所有文件及文件夹
		 * 2、根据 $classname 组织成文件，判断当前文件是否存在
		 * 	2.1、如果不存在，则进行循环查找
		 * 	2.2、如果存在，则直接引入进来
		 */
	
		//1、得到基本路径下的所有文件及文件夹
		$basePathFileArr = scandir($this->basePath);
		if (isset($basePathFileArr) && !empty($basePathFileArr)) {
			$this->executeInclude($this->basePath, $basePathFileArr, $classname);
		}
	}
	
	/**
	 * 执行包含的具体操作
	 * @author gaoqing
	 * 2015年10月14日
	 * @param string $basePath
	 * @param array $basePathFileArr
	 * @param string $classname
	 * @return void 空
	 */
	private function executeInclude($basePath, $basePathFileArr, $classname){
		foreach ($basePathFileArr as $basePathFile){
			if ($basePathFile != '.' && $basePathFile != '..') {
	
				//2、根据 $classname 组织成文件，判断当前文件是否存在
				$includeFile = $classname . ".php";
					
				//2.2、如果存在，则直接引入进来
				if ($includeFile == $basePathFile) {
					$this->includeNeedFile($basePath, $classname);
					return;
				}
				//2.1、如果不存在，则进行循环查找
				else{
					$subDir = $basePath . $basePathFile;
					if (is_dir($subDir)) {
						$subDirArr = scandir($subDir);
						$this->executeInclude($subDir, $subDirArr, $classname);
					}
				}
			}
		}
	}
	
	/**
	 * 包含所需的类文件
	 * @author gaoqing
	 * 2015年10月14日
	 * @param string $baseURL 文件的基本路径
	 * @param string $className 类文件的名称
	 * @return void 空
	 */
	private function includeNeedFile($baseURL, $className){
		if (isset($baseURL) && !empty($baseURL) && is_dir($baseURL)) {
				
			$fullPathClass = $baseURL . $className . ".php";
			if (file_exists($fullPathClass) && is_file($fullPathClass)) {
				require $fullPathClass;
			}
			return null;
		}
	}
	
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}
	
}


?>