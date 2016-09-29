<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：登录页面
* @author gaoqing
* 2015年7月23日
*/

//引入基本的类
include 'Loader.class.php';
include 'CommonUtils.php';
spl_autoload_register(array(new Loader(
		__DIR__ . "/../log4php/main/php/" . 
		PATH_SEPARATOR . 
		__DIR__ . "/"
		), 
		"loader")
	);
	
//注册自定义的 session 操作
$session = Session::getSession();
$session->register();	

//设置 sesion 的相关参数
session_set_cookie_params(5*60);
session_cache_expire(5);

session_start();

/*
 * 用户登录后，将用户的相关信息，自定的保存到数据库中
 */

//判断是否是通过 form 表单提交的
if (isset($_POST['submit'])) {
	$submit = $_POST['submit'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['passwd'] = $_POST['passwd'];
}

if (isset($_SESSION['username'])) {
	echo $_SESSION['username'] . "\n";
	echo session_cache_expire();
}


?>

<form action = "" method = "post" name = "loginForm">
    <input type = "text" name = "username" value = "" /> <br>
    <input type = "password" name = "passwd"/><br>
    
    <input type = "submit" name = "submit" value = "登录" />
</form>