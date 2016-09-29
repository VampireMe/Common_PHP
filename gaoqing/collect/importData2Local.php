<?php
defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

/**
 * 将数据从 wd_120answer_keshi 表中，导入到 wd_120answer_temp 表中
 */

/*
 * 1、从 wd_120answer_keshi 中，获取指定数量 （$num = 1000 ）的问答信息 $initAskArr
 * 2、循环 $initAskArr 中的问答信息，匹配插入到 wd_120answer_temp 表中
 */

/**
 * 得到 wd_120answer_keshi 中的指定条数的问答
 * @author gaoqing
 * 2015年11月9日
 * @param medoo $connection 连接数据库对象
 * @param int $num 获取的条数
 * @return array 指定条数的问答集
 */
function getInitAsk($connection, $num) {
	$initAskArr = array();
	
	$where = array(
			"isimport" => 0,
			"LIMIT" => [0, $num],
			"ORDER" => "age"
	);
	$initAskArr = $connection->select(SELECT_TABLE, "*", $where);
	
	return $initAskArr;
}

/**
 * 导入数据到 wd_120answer_temp 中
 * @author gaoqing
 * 2015年11月9日
 * @param array $initAskArr 初始要导入的问答信息集
 * @param int $preInsertCount 每次插入的条数
 * @param medoo $connection 连接数据库对象
 * @param int $sleepSeconds 休眠的秒数
 * @return int $importCount 成功导入的信息数
 */
function importData($initAskArr, $preInsertCount, $connection, $sleepSeconds){
	$importCount = 0;
	
	$data = array("isimport" => 1);
	
	$insertArr = array();
	$updateIDArr = array();
	$initAskArrCount = count($initAskArr);
	
	foreach ($initAskArr as $key => $initAsk){
		
		if ($key % 100 == 0) {
			sleep($sleepSeconds);
		}
		
		$insertArr[] = getInserArr($initAsk);
		$updateIDArr[] = $initAsk['id'];
		
		if ($key % $preInsertCount == ($preInsertCount - 1) || ($key == $initAskArrCount - 1)) {
			
			//将 $insertArr 数组集，插入到 wd_fhanswer_temp 中
			if (!empty($insertArr)) {
				
				//插入到 wd_120answer_temp 中
				$idArr = $connection->insert(IMPORT_TABLE, $insertArr);
				$importCount += count($insertArr);
				$insertArr = array();
				
				//更新 wd_120answer_keshi 中的信息
				$where = array(
					'id' => $updateIDArr	
				);
				$connection->update(SELECT_TABLE, $data, $where);
				$updateIDArr = array();
			}
		}
	}
	return $importCount;
}

/**
 * 得到插入的数据
 * @author gaoqing
 * 2015年11月9日
 * @param array $initAsk 初始的问答信息
 * @return array 插入的数据集
 */
function getInserArr($initAsk) {
	$insertArr = array();
	
	$insertArr['title'] = $initAsk['title'];
	$insertArr['content'] = $initAsk['content'];
	$insertArr['bestreply'] = '';
	$insertArr['reply1'] = $initAsk['reply1'];
	$insertArr['reply2'] = $initAsk['reply2'];
	$insertArr['reply3'] = '';
	$insertArr['depart1'] = empty($initAsk['class_level1']) ? 0 : $initAsk['class_level1'];
	$insertArr['depart2'] = empty($initAsk['class_level2']) ? 0 : $initAsk['class_level2'];
	$insertArr['depart3'] = empty($initAsk['class_level3']) ? 0 : $initAsk['class_level3'];
	$insertArr['depart9939'] = empty($initAsk['kid']) ? 0 : $initAsk['kid'];
	$insertArr['fromsite'] = '';
	$insertArr['age'] = $initAsk['age'];
	$insertArr['sexnn'] = $initAsk['sex'];
	$insertArr['broadcast'] = 0;
	$insertArr['point'] = 0;
	$insertArr['classid'] = empty($initAsk['kid']) ? 0 : $initAsk['kid'];
	$insertArr['answerUid'] = 0;
	$insertArr['userid'] = 0;
	$insertArr['status'] = 0;
	$insertArr['ip'] = '';
	$insertArr['ctime'] = $initAsk['ctime'];
	
	return $insertArr;
}

function executeImport(){
	require_once CURRENT_PATH.'/Medoos.php';
	$medoo = Medoos::getMedoo();
	//1、从 wd_120answer_keshi 中，获取指定数量 （$num = 1000 ）的问答信息 $initAskArr
	$num = 1000;
	$preInsertCount = 50;
	$sleepSeconds = 1;
	$importCount = 0;
	
	$count = 30;
	for ($k = 0; $k < $count; $k++){
		
		$initAskArr = getInitAsk($medoo, $num);
		
		if (!empty($initAskArr)) {
			
			//2、循环 $initAskArr 中的问答信息，匹配插入到 wd_120answer_temp 表中
			$importCount += importData($initAskArr, $preInsertCount, $medoo, $sleepSeconds);
		}else{
			break;
		}
	}
	return $importCount;
}

defined("SELECT_TABLE") or define("SELECT_TABLE", "wd_120answer_keshi");
defined("IMPORT_TABLE") or define("IMPORT_TABLE", "wd_120answer_temp");

$importCount = executeImport();
echo "成功导入问答的条数是：" . $importCount;

?>