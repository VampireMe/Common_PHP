<?php

/**
 * 常用工具类
 * @author gaoqing
 * 2015-10-16
 */
 class CommonUtils{
 	
 	/**
 	 * 判断参数是否为空
 	 * @author gaoqing
 	 * 2015年10月16日
 	 * @param mixed $arg 被处理的参数
 	 * @return boolean 是否为空（true: 为空；false: 不为空）
 	 */
 	public static function isEmpty($arg) {
 		$isEmpty = true;
 		
 		if (isset($arg) && !empty($arg) ) {
 			
 			if ($arg instanceof string) {
		 		//验证是否为空格
		 		$result = strpos($arg, ' ');
		 		if ($result != 0) {
		 			$isEmpty = false;
		 		}
 			}
 			else{
 				$isEmpty = false;
 			}
 		}
 		return $isEmpty;
 	}
 	
 	
 }

?>