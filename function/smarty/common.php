<?php

//设置项目的基本路径
defined("BASE_PATH") or define("BASE_PATH", dirname(__FILE__));
$GLOBALS['BASE_PATH'] = BASE_PATH;

//引入公共的文件（设置 Smarty 参数）
require_once BASE_PATH . '/setting.inc.php';

$title = "Smarty 模板测试";
$assign_str = "表面上是运气问题，那么多门柱与横梁。可仔细研究，真正缺乏运气的是于";


/* function cut_str($var) {
	$cut_str = $var;
	
	$num = mb_strlen($var, 'utf-8');
	if ($num > 10) {
		$cut_str = mb_substr($var, 0, 10, 'utf-8') . "...";
	}
	return $cut_str;
}

function input_func($args, $smarty) {
	return '<input name = "'. $args['name'] .'" widht = "'. $args['width'] .'" value = "'. $args['value'] .'"  >';
}


$smarty->registerPlugin("modifier",  "cut", "cut_str");
$smarty->registerPlugin("function", "input", "input_func"); */


$smarty->assign("title", $title);
$smarty->assign("str", $assign_str);

$smarty->display("common.tpl");


?>