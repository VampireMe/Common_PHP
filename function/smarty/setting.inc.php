<?php

/**
 * 共有引用文件
 * @author gaoqing
 * 2015-09-04
 */
 
require_once './class/Smarty.class.php';

/*
 * 1、实例化 Smarty 对象
 * 2、设置其参数
 */

//1、实例化 Smarty 对象
$smarty = new Smarty();

//2、设置其参数
$smarty->setConfigDir("config.conf");
$smarty->setTemplateDir("tpl");
$smarty->setCompileDir("tpl_c");
$smarty->addPluginsDir("plugins");
$smarty->setLeftDelimiter("<{");
$smarty->setRightDelimiter("}>");
$smarty->setAutoLiteral(false);



?>