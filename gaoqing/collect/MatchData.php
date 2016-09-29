<?php

defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

/**
 * 匹配数据：
 * 	将采集的数据的科室 和 wd_keshi 的科室进行匹配
 */

class MatchData{
	
	private $testMedoo = null;
	private $snsMedoo = null;
	private $wdKeshiTable = "";
	private $specialArr = array();
	private $matchDepartmentArr = array();
	
	public function __construct($testMedoo, $snsMedoo, $wdKeshiTable) {
		$this->testMedoo = $testMedoo;
		$this->snsMedoo = $snsMedoo;
		$this->wdKeshiTable = $wdKeshiTable;
		$this->matchDepartmentArr = require CURRENT_PATH.'/data/collect_department_match.php';
	}
	
	/**
	 * 添加特殊处理科室对应关系
	 * @author gaoqing
	 * 2015年12月3日
	 * @param Object $specialHandle 特殊处理对象
	 * @return array 特殊处理后的科室对应关系集
	 */
	public function addSpecialHandle(ISpecialHandle $specialHandle){
		$this->specialArr[] = $specialHandle;
	}
	
	public function matchData($num) {
		$isEnd = false;
		
		/*
		 * 1、从  wd_120answer_keshi 表中，每次查询 500 条数据 $wdAnswerKeshiArr
		 * 2、循环 $wdAnswerKeshiArr 中的数据到 $wdAnswerKeshi，并得到 $departmentStr 信息，拆分科室到 $departmentArr 数组中
		 * 3、拿 $departmentArr 的值，到 wd_keshi 中，查询相应的科室的科室信息（id, pid, class_level1, class_level2, class_level3）$keshiArr
		 * 4、将查询到的 $keshiArr 信息，设置到 $wdAnswerKeshi 中，并更新相应数据的信息
		 */
		
		//1、从  wd_120answer_keshi 表中，每次查询 500 条数据 $wdAnswerKeshiArr
		$wdAnswerKeshiArr = $this->getWdAnswerKeshiArr($num);
		
		if (!empty($wdAnswerKeshiArr)) {
			
			//2、循环 $wdAnswerKeshiArr 中的数据到 $wdAnswerKeshi，并得到 $departmentStr 信息，拆分科室到 $departmentArr 数组中
			foreach ($wdAnswerKeshiArr as $key => $wdAnswerKeshi){
				$departmentStr = isset($wdAnswerKeshi["department"]) ? $wdAnswerKeshi["department"] : "";
				$id = $wdAnswerKeshi['id'];
				$kid = isset($wdAnswerKeshi['kid']) ? $wdAnswerKeshi['kid'] : 0;
				
				if (!empty($departmentStr)) {
					$departmentArr = explode(",", trim($departmentStr));
					if (isset($departmentArr) && !empty($departmentArr)) {
						
						//精确匹配
						//$this->exactMatchAndUpdate($id, $departmentArr);
						
						//解决抓取科室不能与现有科室匹配的问题
						$this->matchDepartment($departmentArr);
						
						//模糊匹配
						$this->blurMatchAndUpdate($id, $departmentArr, $kid);
					}
				}
			}
		}else{
			$isEnd = true;
		}
		return $isEnd;
	}
	
	/**
	 * 解决抓取科室不能与现有科室匹配的问题
	 * @author gaoqing
	 * 2015年12月16日
	 * @param array $departmentArr 抓取的科室信息
	 * @return array 匹配后的科室信息
	 */
	private function matchDepartment(&$departmentArr) {
		foreach ($departmentArr as $key => $department){
			$var = trim($department);
			if (isset($this->matchDepartmentArr[$var])) {
				$departmentArr[$key] = $this->matchDepartmentArr[$var]['name'];
			}
		}
	}
	
	/**
	 * 精确匹配方案 <br />
	 * 	如果指定的科室或疾病不存在，则动态创建，保证匹配的准确性
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $id 当前问答的 id 
	 * @param array $departmentArr 问答科室数组
	 * @return void 空
	 */
	public function exactMatchAndUpdate($id, $departmentArr) {
		/*
		 * 一、精确匹配：
		 * 	操作流程：
		 * 		（1）判断当前拆分的科室数组级数 $departmentLevel
		 *
		 * 			（1.1）如果级数 $departmentLevel < 4 ，则从最小级别的子科室 $department 开始匹配
		 * 				（1.1.1）如果匹配到了相应科室信息 $keshiArr ，则将当前 $wdAnswerKeshi 的科室信息设置为 $keshiArr 的值，并更新 $wdAnswerKeshi 的科室信息到数据库中
		 * 				（1.1.2）如果未匹配到相应科室（$keshiArr = null）,则在 wd_keshi_test 表中，创建以 $department 为 name 的科室信息 $newDepartment
		 * 					（1.1.2.1）首先根据当前 $department 科室的父科室 $parentDepartment，到 wd_keshi 表中，进行匹配科室信息 $keshiArr
		 * 						（1.1.2.1.1）若匹配到了科室信息，则设置 $newDepartment 的 pid, class_level1, class_level2, class_level3 信息，并插入到 wd_keshi_test 表中
		 * 						（1.1.2.1.2）若未匹配到科室信息，则再获取 $parentDepartment 的父级科室信息，以此类推，更新到数据库中
		 *
		 *
		 * 			（1.2）如果级数 $departmentLevel = 4 ，则将 第四级 $disease ，单独提取出来
		 *
		 * 				（1.2.1）将前面 三级科室，按照 （1.1）的处理方式，并得到第三级科室的 id: $thirdLevelID
		 * 				（1.2.2）将 $disease，和 $thirdLevelID 组装成 $diseaseArr ，更新到 disease 表中
		 */	
		
		$departmentArrSize = count($departmentArr);
		
		//（1.1）如果级数 $departmentLevel < 4 ，则从最小级别的子科室 $department 开始匹配
		if ($departmentArrSize > 0 && $departmentArrSize < 4) {
			$leastLevelDepartmentID = $this->matchLessThan3Department($id, $departmentArr);
		}
		
		//（1.2）如果级数 $departmentLevel = 4 ，则将 第四级 $disease ，单独提取出来
		elseif ($departmentArrSize == 4){
			$this->match4LevelDepartment($id, $departmentArr);
		}
	}
	
	/**
	 * 匹配科室级别数等于 4 的科室信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $id 当前问答的 id 
	 * @param array $departmentArr 问答科室数组
	 * @return int 新增疾病的id
	 */
	private function match4LevelDepartment($id, $departmentArr) {
		$diseaseID = 0;
		
		$fourthLevelDepartment = array_pop($departmentArr);
		/*
		 * 	操作流程：
		 * 			（1.2）如果级数 $departmentLevel = 4 ，则将 第四级 $disease ，单独提取出来
		 *
		 * 				（1.2.1）将前面 三级科室，按照 （1.1）的处理方式，并得到第三级科室的 id: $thirdLevelID
		 * 				（1.2.2）将 $disease，和 $thirdLevelID 组装成 $diseaseArr ，更新到 disease 表中
		 */		
			
		//（1.2.1）将前面 三级科室，按照 （1.1）的处理方式，并得到第三级科室的 id: $thirdLevelID
		$leastLevelDepartmentID = $this->matchLessThan3Department($id, $departmentArr);
			
		//判断以 $fourthLevelDepartment 为名称的 disease 表中，是否存在当前记录，如果未存在，则插入到 disease 表中
		$tempDisease = $this->getDiseaseByName($fourthLevelDepartment);
		if (!isset($tempDisease) || empty($tempDisease)) {
			
			//（1.2.2）将 $disease，和 $thirdLevelID 组装成 $diseaseArr ，更新到 disease 表中
			$diseaseArr = array();
			$diseaseArr['pid'] = $leastLevelDepartmentID;
			$diseaseArr['name'] = $fourthLevelDepartment;
			$diseaseArr['source'] = 2;
		
			$diseaseID = $this->testMedoo->insert("disease", $diseaseArr);
		}else{
			$diseaseID = isset($tempDisease[0]) ? $tempDisease[0]['id'] : 0;
		}	
		
		//更新问答的疾病id
		$this->updateWDKeshiDisease($diseaseID, $id);
		
		return $diseaseID;
	}
	
	/**
	 * 更新问答的疾病id
	 * @author gaoqing
	 * 2015年11月3日
	 * @param int $diseaseID 疾病id
	 * @param int $id 当前问答的id
	 * @return void 空
	 */
	private function updateWDKeshiDisease($diseaseID, $id) {
		//如果疾病 id 不为 0 的话，则说明当前问答 $id ，存在具体的疾病归属
		if ($diseaseID != 0) {
			$data = array("diseaseid" => $diseaseID);
			$where = array(
					"id" => $id
			);
			$this->testMedoo->update($this->wdKeshiTable, $data, $where);
		}
	}
	
	/**
	 * 根据疾病的名称，查询疾病的信息（disease 表）
	 * @author gaoqing
	 * 2015年11月3日
	 * @param string $diseaseName 疾病的名称
	 * @return array 相关疾病的信息
	 */
	private function getDiseaseByName($diseaseName) {
		$diseaseArr = array();
		
		$where = array(
				"name" => $diseaseName
		);
		$diseaseArr = $this->testMedoo->select("disease", "*", $where);
		
		return $diseaseArr;
	}
	
	/**
	 * 匹配科室级别数小于 4 的科室信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $id 当前问答的 id 
	 * @param array $departmentArr 问答科室数组
	 * @return int 更新后的最小子科室的科室 id 
	 */
	private function matchLessThan3Department($id, $departmentArr) {
		$departmentArrSize = count($departmentArr);
		/*
		 * 	操作流程：
	 	* 		（1.1）如果级数 $departmentLevel < 4 ，则从最小级别的子科室 $department 开始匹配
	 	* 			（1.1.1）如果匹配到了相应科室信息 $keshiArr ，则将当前 $wdAnswerKeshi 的科室信息设置为 $keshiArr 的值，并更新 $wdAnswerKeshi 的科室信息到数据库中
	 	* 			（1.1.2）如果未匹配到相应科室（$keshiArr = null）,则在 wd_keshi_test 表中，创建以 $department 为 name 的科室信息 $newDepartment
	 	* 				（1.1.2.1）首先根据当前 $department 科室的父科室 $parentDepartment，到 wd_keshi 表中，进行匹配科室信息 $keshiArr
	 	* 					（1.1.2.1.1）若匹配到了科室信息，则设置 $newDepartment 的 pid, class_level1, class_level2, class_level3 信息，并插入到 wd_keshi_test 表中
	 	* 					（1.1.2.1.2）若未匹配到科室信息，则再获取 $parentDepartment 的父级科室信息，以此类推，更新到数据库中
		*/
		$notMatchDepartmentStack = new SplStack();
		$matchArr = array();
		$lastIndex = $departmentArrSize - 1;
		for ($i = $lastIndex; $i >= 0; $i--){
			$department = trim($departmentArr[$i]);
			
			//（1.1）如果级数 $departmentLevel < 4 ，则从最小级别的子科室 $department 开始匹配
			$keshiArr = $this->getKeshiArr(array($department));
			
			//（1.1.1）如果匹配到了相应科室信息 $keshiArr ，则将当前 $wdAnswerKeshi 的科室信息设置为 $keshiArr 的值，并更新 $wdAnswerKeshi 的科室信息到数据库中
			if (!empty($keshiArr)) {
				
				//最小子科室时：
				if ($i == $lastIndex) {
					$this->updateWDKeshi($id, $keshiArr);
					return isset($keshiArr[0]) ? $keshiArr[0]['id'] : 0;
				}
				
				//非最小子科室时：
				else{
					$matchArr[] = $keshiArr;
					break ;
				}
			}
			
			//匹配科室是空时：
			else{
				$departmentAndLevel = array();
				$departmentAndLevel[0] = $i + 1;
				$departmentAndLevel[1] = $department;
				$notMatchDepartmentStack->push($departmentAndLevel);
			}
		}
		//处理子科室未匹配的情况
		$childDepartmentID = $this->dealWithChildDepartmentNotMatch($notMatchDepartmentStack, $matchArr);
		
		//将新增的子科室信息，更新到当前的问答数据中
		$newKeshiArr = $this->getNewKeshi($childDepartmentID);
		$this->updateWDKeshi($id, $newKeshiArr);
		
		return $childDepartmentID;
	}
	
	/**
	 * 根据科室的 id,查询新增的科室信息
	 * @author gaoqing
	 * 2015年11月3日
	 * @param int $keshiID 科室id
	 * @return array 新增的科室信息集
	 */
	private function getNewKeshi($keshiID) {
		$newKeshiArr = array();
		$colum = array(
				"id", "pID", "class_level1", "class_level2", "class_level3" 
		);
		$where = array(
				"id" => $keshiID
		);
		$newKeshiArr = $this->testMedoo->select("wd_keshi_test", $colum, $where);
		
		return $newKeshiArr;
	}
	
	/**
	 * 匹配最小子科室未匹配到科室信息的情况
	 * @author gaoqing
	 * 2015年11月2日
	 * @param SplStack $notMatchDepartmentStack 未匹配的子科室栈
	 * @param array $matchArr 已匹配的父科室数组
	 * @return int 更新后的最小子科室id 
	 */
	private function dealWithChildDepartmentNotMatch($notMatchDepartmentStack, $matchArr) {
		$currentDepartmentID = 0;
		
		//都未匹配时：
		$pid = 0;
		
		//部分匹配时：
		if (!empty($matchArr)) {
			$pid = isset($matchArr[0]) ? (isset($matchArr[0][0]) ? $matchArr[0][0]['id'] : 0 ) : 0;
		}
		
		$notMatchDepartmentStack->rewind();
		while ($notMatchDepartmentStack->valid()) {
			if ($notMatchDepartmentStack->isEmpty()) {
				break;
			}
			$departmentAndLevel = $notMatchDepartmentStack->pop();
			
			//向 wd_keshi_test 表中，插入科室信息
			$currentDepartmentID = $pid = $this->insertKeshiInfo($departmentAndLevel, $pid);
		}
		return $currentDepartmentID;
	}
	
	/**
	 * 向 wd_keshi_test 表中，插入新的科室信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param array $departmentAndLevel 科室的名称及当前的科室级别（1：一级科室；2：二级科室；3：三级科室）
	 * @param int 当前科室的父科室id
	 * @return int 插入后的科室id
	 */
	private function insertKeshiInfo($departmentAndLevel, $pid) {
		$currentid = 0;
		
		$level = $departmentAndLevel[0];
		$department = $departmentAndLevel[1];
		
		/*
		 * 如果 $pid != 0 的话，则通过 $pid 的值，查询父科室的信息 $parentArr
		 */
		$data = array(
				"pID" => $pid,
				"class_level1" => 0, 
				"class_level2" => 0, 
				"class_level3" => 0, 
				"child" => 1,
				"name" => $department,
				"indexpush" => 2,
				"keywords" => $department,
				"isCreate" => 1
		);
		
		//根据父科室id 查询当前父科室的信息
		$this->getKeshiByID($level, $pid, $data);
		
		$currentid = $this->testMedoo->insert("wd_keshi_test", $data);
		
		//根据 $level ,再次更新 class_level1、class_level2、class_level13 的值
		$this->updateKeshiByLevel($level, $currentid);
		
		return $currentid;
	}
	
	/**
	 * 根据科室级别，更新科室的信息
	 * @author gaoqing
	 * 2015年11月3日
	 * @param int $level 科室级别（1：一级科室；2：二级科室；3：三级科室）
	 * @param int $keshiid 当前科室的 id 
	 * @return void 空
	 */
	private function updateKeshiByLevel($level, $keshiid){
		$data = array();
		
		switch ($level){
			case 1:
				$data['class_level1'] = $keshiid;
				break;
			case 2:
				$data['class_level2'] = $keshiid;
				break;
			case 3:
				$data['class_level3'] = $keshiid;
				break;
			default:
				break;
		}
		$where = array("id" => $keshiid);
		$this->testMedoo->update("wd_keshi_test", $data, $where);
	}
	
	/**
	 * 通过 科室的id,查询科室的相关信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $level 新增科室的级别（1：一级科室；2：二级科室；3：三级科室）
	 * @param int $pid 科室的 id 
	 * @param array &$data 更新科室的数据集的引用
	 * @return void 空
	 */
	private function getKeshiByID($level, $pid, &$data) {
		if ($pid != 0) {
			$colum = array(
					"id", "pID", "class_level1", "class_level2", "class_level3"
			);
			$where = array(
					"id" => $pid
			);
			$keshiArr = $this->testMedoo->select("wd_keshi_test", $colum, $where);
			if (!empty($keshiArr)) {
				if (isset($keshiArr[0])) {
					
					switch ($level){
						case 2:			//二级科室
							$data['class_level1'] = isset($keshiArr[0]['id']) ? $keshiArr[0]['id'] : 0;
							$data['child'] = 1;
							break;
						case 3:			//三级科室
							$data['class_level1'] = isset($keshiArr[0]['pID']) ? $keshiArr[0]['pID'] : 0;
							$data['class_level2'] = isset($keshiArr[0]['id']) ? $keshiArr[0]['id'] : 0;
							$data['child'] = 0;
							break;
						default:
							$data['class_level1'] = isset($keshiArr[0]['pID']) ? $keshiArr[0]['pID'] : 0;
							$data['class_level2'] = isset($keshiArr[0]['id']) ? $keshiArr[0]['id'] : 0;
							$data['child'] = 0;
							break;
					}
				}
			}
		}
	}
	
	/**
	 * 模糊匹配方案<br />
	 * （通过 科室的信息，进行模糊匹配，只要匹配到科室，就按照匹配到的科室信息，更新当前问答信息）
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $id 当前问答的 id 
	 * @param array $departmentArr 问答科室数组
	 * @return void 空
	 */
	private function blurMatchAndUpdate($id, $departmentArr, $kid){
		
		//3、拿 $departmentArr 的值，到 wd_keshi 中，查询相应的科室的科室信息（id, pid, class_level1, class_level2, class_level3）$keshiArr
		$keshiArr = $this->getKeshiArr($departmentArr, $kid);
		
		$this->updateWDKeshi($id, $keshiArr);
	}
	
	/**
	 * 更新问答信息的相关科室信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $id 当前问答的 id 
	 * @param array $keshiArr 匹配到的科室信息集
	 * @return void 空
	 */
	private function updateWDKeshi($id, $keshiArr) {
		//4、将查询到的 $keshiArr 信息，设置到 $wdAnswerKeshi 中，并更新相应数据的信息
		$data = array();
		if (isset($keshiArr) && !empty($keshiArr)) {
			$data["kid"] = isset($keshiArr[0]["id"]) ? $keshiArr[0]["id"] : 0;
			$data["pid"] = isset($keshiArr[0]["pID"]) ? $keshiArr[0]["pID"] : 0;
			$data["class_level1"] = isset($keshiArr[0]["class_level1"]) ? $keshiArr[0]["class_level1"] : 0;
			$data["class_level2"] = isset($keshiArr[0]["class_level2"]) ? $keshiArr[0]["class_level2"] : 0;
			$data["class_level3"] = isset($keshiArr[0]["class_level3"]) ? $keshiArr[0]["class_level3"] : 0;
			$data["isMatch"] = 1;
		}else {
			$data["isMatch"] = 2;
		}
		$this->updateKeshi($id, $data);
	}
	
	/**
	 * 更新科室的信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $id 更新科室所在问答的 id 
	 * @param array 科室信息
	 * @return void 空
	 */
	private function updateKeshi($id, $data) {
		$where = array(
				"id" => $id
		);
		$this->testMedoo->update($this->wdKeshiTable, $data, $where);
	}
	
	/**
	 * 根据科室名称，得到科室的相关信息
	 * @author gaoqing
	 * 2015年11月2日
	 * @param array $departmentArr 科室名称数组
	 * @return array 当前科室的相关信息集
	 */
	private function getKeshiArr($departmentArgsArr, $kid = 0) {
		$keshiArr = array();
		
		if (isset($departmentArgsArr) && !empty($departmentArgsArr)) {
			array_walk($departmentArgsArr, function (&$val, $key){
				$val = trim($val);
			});
			
			//将特殊的科室，进行指定的对应匹配
			$departmentArr = $departmentArgsArr;
			foreach ($this->specialArr as $specialHandle){
				$departmentArr = $specialHandle->specialHandle($departmentArgsArr);
			}
			
			$colum = array(
					"id", "pID", "class_level1", "class_level2", "class_level3" 
			);
			$order = "id DESC";
			$where = array(
					"name" => $departmentArr, 
					"ORDER" => $order,
					"LIMIT" => [0, 1]
			);
			$keshiArr = $this->testMedoo->select("wd_keshi_test", $colum, $where);
		}
		return $keshiArr;
	}
	
	/**
	 * 从  wd_120answer_keshi 表中，每次查询 500 条数据 $wdAnswerKeshiArr
	 * @author gaoqing
	 * 2015年11月2日
	 * @param int $num 查询的个数
	 * @return array 查询到的问答信息集
	 */
	private function getWdAnswerKeshiArr($num) {
		$wdAnswerKeshiArr = array();
		
		$colum = array("id", "department", "kid", "pID", "class_level1", "class_level2", "class_level3", "isMatch");
		$where = array(
				"isMatch" => 0,
				"ORDER" => "id asc",
				"LIMIT" => [0, $num]
		);
		
		$wdAnswerKeshiArr = $this->testMedoo->select(
				$this->wdKeshiTable, 
				$colum, 
				$where
			);
		return $wdAnswerKeshiArr;
	}
	
}


?>