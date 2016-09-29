<?php
set_time_limit(0);

defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

require_once CURRENT_PATH.'/120/Import.php';

//数据库表的名称
$tableName = "wd_120answer";

//120
$txt_path = CURRENT_PATH."/120/120ask_list.txt";

$file_paths = array($txt_path);

//120:s
$import_count = Import::import_data($file_paths, $tableName);


echo sprintf('共导入%d条问题',$import_count);


?>