<?php
set_time_limit(0);

defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

require_once CURRENT_PATH.'/jk/JKImport.php';

//数据库表的名称
$tableName = "wd_jkanswer";

//寻医问药：
$txt_path = CURRENT_PATH."/jk/jk.txt";

$file_paths = array($txt_path);

$import_count = JKImport::import_data($file_paths, $tableName);

echo sprintf('共导入%d条问题',$import_count);


?>