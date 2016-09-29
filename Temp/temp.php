<?php

$arr = [
    'name' => ['user' => 'gaoqing', 'test' => 'test'],
    'key' => ['hieght' => 160, 'width' => 200],
];

$array = unserialize('a:2:{s:4:"name";a:2:{s:4:"user";s:7:"gaoqing";s:4:"test";s:4:"test";}s:3:"key";a:2:{s:6:"hieght";i:160;s:5:"width";i:200;}}');

print_r($array);
?>



