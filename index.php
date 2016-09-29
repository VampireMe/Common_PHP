<?php

/**
 * 功能：PHP 相关信息
 * 
 * @author gaoqing
 *         2015年6月26日
 *        
 */


$person = [
    'gaoqing',
    'wushijie',
    'caoxingdu'
];
$pages = [
    'index',
    'content',
    'share'
];

$indexes = [];
foreach ($person as $key => $name){
    $index = 0;

    while (true){
        $index = rand(0, 2);
        if (!in_array($index, $indexes)){
            $indexes[] = $index;
            break;
        }
    }

    $page = $pages[$index];
    echo $name . ' ---- 随机抽到的页面是： ' . $page . PHP_EOL;
}




