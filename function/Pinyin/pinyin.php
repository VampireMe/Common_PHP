<?php

require_once("Pinyin.class.php"); 
$pinyin = new Pinyin(); 
$str = $_POST['str']; 
echo $pinyin->strtopin($str,1); 

