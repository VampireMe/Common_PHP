<?php
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set("PRC");

include('Loader.class.php');
spl_autoload_register(array(new Loader(__DIR__ . "/main/php/"), 'loader'));

include('Log4PHP.php');
$log = new Log4PHP();

$log->test();


?>