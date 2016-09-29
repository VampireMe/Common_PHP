<?php

/**
 * 自动加载类
 * @author gaoqing
 * 2015年10月16日
 */
class Loader{
	
	/** 基础路径数组 */
	private $basePath;
	
	public function __construct($basePath) {
		if (!CommonUtils::isEmpty($basePath)) {
			if (strstr($basePath, ";")) {
				$this->basePath = explode(";", $basePath);
			}else {
				$this->basePath = $basePath;
			}
		}
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
		$isFound = false;
		
		foreach ($this->basePath as $path){
			
			//1、得到基本路径下的所有文件及文件夹
			$basePathFileArr = scandir($path);
			if (isset($basePathFileArr) && !empty($basePathFileArr)) {
				$isFound = $this->executeInclude($path, $basePathFileArr, $classname);
				if ($isFound) {
					return ;
				}
			}
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
		$isFound = false;
		
		foreach ($basePathFileArr as $basePathFile){
			if ($basePathFile != '.' && $basePathFile != '..') {
	
				//2、根据 $classname 组织成文件，判断当前文件是否存在
				$includeFile = $classname . ".php";
					
				//2.2、如果存在，则直接引入进来
				if ($includeFile == $basePathFile) {
					$isFound = $this->includeNeedFile($basePath, $classname);
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
		return $isFound;
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
		$isRequireSuccess = false;
		
		if (isset($baseURL) && !empty($baseURL) && is_dir($baseURL)) {
				
			$fullPathClass = $baseURL . $className . ".php";
			if (file_exists($fullPathClass) && is_file($fullPathClass)) {
				require $fullPathClass;
				$isRequireSuccess = true;
			}
			return $isRequireSuccess;
		}
		return $isRequireSuccess;
	}
	
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}
	
}


?>