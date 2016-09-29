<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

/**
 * 查询 member_detail_2 中，医生所属科室的名称，对应医生的 id
 * 	格式：['doc_keshi' => 'uid']
 */

echo "aaa";


require('/home/web/wapask-9939-com/public/medoo.php');
$params = require('/home/web/wapask-9939-com/public/sns.db.inc.php');

$medoo = new medoo($params);

$colum = ['uid'];
$where = ['doc_from' => 1];
$memberArr = $medoo->select("member", $colum, $where);
$uidArr = array();
foreach ($memberArr as $val){
	$uidArr[] = $val['uid'];
}

$colum = ['uid', 'doc_keshi'];
$where = ['uid' => $uidArr];
$keshiName2IDArr = $medoo->select("member_detail_2", $colum, $where);

$dataStr = "";
$dataStr = "<?php" . PHP_EOL;
$dataStr .= "return " . PHP_EOL;
$dataStr .= "array(" . PHP_EOL;

foreach ($keshiName2IDArr as $key => $val){
	$keshi = "". $val['doc_keshi'] ." => '". $val['uid'] ."',";

	$dataStr .= $keshi . PHP_EOL;
}

$dataStr .= ");" . PHP_EOL;
$dataStr .= "?>";

echo $dataStr;

//写入到文件中
$filename = "/home/web/wapask-9939-com/public/KName2DocID.php";
file_put_contents($filename, $dataStr);



?>