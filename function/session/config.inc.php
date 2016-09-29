<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：配置常用信息
* @author gaoqing
* 2015年7月17日
*/

//数据库的相关连接信息
$DSN_driver = "mysql";
$DSN_host = "localhost";
$DSN_dbname = "test";
$user = "root";
$pass = "";

$DSN = $DSN_driver . ":" . "host=" . $DSN_host . ";" . "dbname=" .$DSN_dbname . ";charset=utf8mb4" ;

?>