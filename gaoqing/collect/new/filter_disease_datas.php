<?php
/**
 * @version 0.0.0.1
 */
defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));
defined("BASE_PATH") or define("BASE_PATH", dirname(CURRENT_PATH));

require_once BASE_PATH . '/Medoos.php';

/*
 * 1、查询 wd_tiwen_data7 的数据 wd_tiwen_data7s
 * 2、循环指定数量（$pre_count = 50）的数据, 循环判断 department 中，是否包含有疾病
 *  2.1、如果有疾病，则将当前数据量 $pre_count 的数据，更新到数据库中（is_include_disease = 1）
 */
$size = 100;
$conditions = [
    'is_check_disease' => 0,
    "LIMIT" => [0, $size]
];
$wd_tiwen_data7s = get_tiwen_datas('wd_tiwen_data7', $conditions);

if (!empty($wd_tiwen_data7s)){

    $pre_count = 10;
    $include_disease_ids = [];
    foreach ($wd_tiwen_data7s as $key => $wd_tiwen_data7){

    }

}



/**
 * 得到提问问题集
 * @author gaoqing
 * @date 2016-08-11
 * @param string $table_name 表名
 * @param array $conditions 查询条件
 * @return array 提问问题集
 */
function get_tiwen_datas($table_name, $conditions){
    $db = Medoos::getMedoo();
    return $db->select($table_name, ['id', 'department'], $conditions);
}