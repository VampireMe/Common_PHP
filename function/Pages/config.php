<?php
header("Content-Type: text/html; charset=utf-8");

$host="localhost";
$db_user="root";
$db_pass="";
$db_name="ribbon";
$timezone="Asia/Shanghai";

$link=new mysqli($host, $db_user, $db_pass, $db_name);
$link->query("SET names UTF8");

?>