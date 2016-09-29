<?php

$basePath = dirname(__FILE__);
$basePath = substr($basePath, 0, strrpos($basePath, "/"));
defined("CURRENT_PATH") or define("CURRENT_PATH", $basePath);

/**
 * 生成缓存文件：
 * 	科室的 id 对应 科室的名称：
 * 	['id' => 'name']
 */

/*
 * 1、查询出所有的科室信息
 * 2、生成对应格式的文件
 */

require CURRENT_PATH . '/data/medoo.php';
$params = require CURRENT_PATH . '/data/db.inc.php';

$medoo = new medoo($params);

$colum = ['id', 'name'];
$where = [];
$allKeshi = $medoo->select('wd_keshi_test', $colum, $where);

$dataStr = "";
$dataStr = "<?php" . PHP_EOL;
$dataStr .= "return " . PHP_EOL;
$dataStr .= "array(" . PHP_EOL;

foreach ($allKeshi as $key => $val){
	$keshi = "". $val['id'] ." => '". $val['name'] ."',";
	
	$dataStr .= $keshi . PHP_EOL;
}

$dataStr .= ");" . PHP_EOL;
$dataStr .= "?>";

//写入到文件中
$filename = CURRENT_PATH . "/data/KID2Name.php";
file_put_contents($filename, $dataStr);

?>