<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：模拟服务器端
* @author gaoqing
* 2015年7月23日
*/

//接收来自客户端的请求
$name = urldecode($_POST['name']);
$pass = urldecode($_POST['pass']);

echo $name . " ******* " . $pass;



?>