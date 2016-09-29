<?php
header("Content-Type:text/html;charset=utf-8");

require 'Person.php';

$person = new Person("gaoqing", 28); 


$person->height = 150;

echo $person->height;

$person->say();




?>