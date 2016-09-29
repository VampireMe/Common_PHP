<?php

defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

require_once CURRENT_PATH . '/MatchData.php';
require_once CURRENT_PATH . '/Medoos.php';
require_once CURRENT_PATH . '/SNSMedoos.php';
require_once CURRENT_PATH . '/special_handler/BJPSpecialHandle.php';

//初始化 $testMedoo 数据库连接对象
$testMedoo = Medoos::getMedoo();

//初始化 $snsMedoo 数据库连接对象
$snsMedoo = SNSMedoos::getMedoo();
$wdKeshiTable = "wd_120answer_keshi";
//$wdKeshiTable = "wd_xyanswer_keshi";
//$wdKeshiTable = "wd_xyanswer_keshi";

$matchData = new MatchData($testMedoo, $snsMedoo, $wdKeshiTable);

//心理诊所 => 心理科    特殊关键词处理操作
//$specialHandle = new BJPSpecialHandle();
//$matchData->addSpecialHandle($specialHandle);

$seconds = 0;
$count = 62;
for($i = 1; $i <= $count; $i++){
	
	$num = 1000;
	$isEnd = $matchData->matchData($num);
	
	sleep($seconds);
	
	if ($isEnd) {
		break;
	}
}

?>