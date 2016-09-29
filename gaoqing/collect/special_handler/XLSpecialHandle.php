<?php

$basePath = dirname(__FILE__);
$basePath = substr($basePath, 0, strrpos($basePath, "/"));
defined("CURRENT_PATH") or define("CURRENT_PATH", $basePath);

require CURRENT_PATH . '/special_handler/ISpecialHandle.php';

/**
 * 心理科室相关的特殊科室匹配类
 * @author gaoqing
 * 2015年12月4日
 */
class XLSpecialHandle implements ISpecialHandle {
	
	public function specialHandle($departmentArr) {
		$newDepartmentArr = array();
		foreach ($departmentArr as $department){
			
			//转换字符编码
			$initEncoding = mb_detect_encoding($department, array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS', 'eucjp-win','sjis-win','EUC-JP'));
			$departmentName = iconv($initEncoding, "UTF-8", $department);
			
			$matchMap = $this->matchMap();
			
			if (isset($matchMap[$departmentName])) {
				$newDepartmentArr[] = $matchMap[$departmentName];
			}else {
				$newDepartmentArr[] = $departmentName;
			}
		}
		return $newDepartmentArr;
	}
	
	/**
	 * 特殊匹配的 Map 集
	 * @author gaoqing
	 * 2015年12月3日
	 * @return array  Map 集
	 */
	public function matchMap() {
		return ["心理诊所" => "心理科"];
	}
	
}