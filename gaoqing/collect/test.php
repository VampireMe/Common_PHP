<?php
set_time_limit(0);

defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

//require_once CURRENT_PATH.'/120/Import.php';
require_once CURRENT_PATH.'/xy/XYImport.php';
//require_once CURRENT_PATH.'/fh/FHImport.php';

//数据库表的名称
$tableName = "wd_xyanswer";

//120
//$txt_path = CURRENT_PATH."/120/120ask_list.txt";

//寻医问药：
$txt_path = CURRENT_PATH."/xy/xunyiwenyao_kswd_2_2.txt";

//飞华：
//$txt_path = CURRENT_PATH."/fh/feihua_TEST.txt";

$file_paths = array($txt_path);

//120:s
//$import_count = Import::import_data($file_paths);

//寻医问药：
 //$import_count = XYImport_thread::import_data($file_paths);
//$import_count = XYImport::import_data($file_paths);

//飞华
$import_count = XYImport::import_data($file_paths, $tableName);

echo sprintf('共导入%d条问题',$import_count);


?>