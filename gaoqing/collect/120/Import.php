<?php

$basePath = dirname(__FILE__);
$basePath = substr($basePath, 0, strrpos($basePath, "/"));
defined("CURRENT_PATH") or define("CURRENT_PATH", $basePath);

require_once CURRENT_PATH . '/qas.php';
require_once CURRENT_PATH . '/utils.php';


/**
 * Enter description here...
 *
 * example：
 * <pre>
 *
 * </pre>
 *
 * @name QLib_Helper_Client
 * @since   1.0
 */
class Import {
    
    
    /**
     * 导入采集的问答数据
     * 
     * 
    $txt_path = Yii::getPathOfAlias('webroot'). "/res/120ask_list.txt";
    $file_paths = array($txt_path);
    $import_count = utils::import_data($file_paths);
    echo sprintf('共导入%d条问题',$import_count);
    echo $d_array['rs.reply3'];
     * 
     */
    public static function import_data(array $arr_files = array(), $tableName){
    	$qas = new qas();
    	
    	$seconds = 10;
    	
    	
        $import_count = 0;
        foreach($arr_files as $txt_path){
            $d_array = parse_ini_file($txt_path,true);
            foreach($d_array as $k=>$v){
            	
            	if ($k != 0 && $k % 100 == 0) {
            		sleep($seconds);
            	}
            	
            	$qid = intval($v['id']); 
                $title =$v['title'];
                $content=$v['content'];
                
                $department = "";
                if (isset($v['department']) && !empty($v['department'])) {
                	$departmentArr = explode(" → ", $v['department']);
                	//删除头和尾的数据
                	array_shift($departmentArr);
                	array_pop($departmentArr);
                	
                	$department = implode(",", $departmentArr);
                }
                
                //性别年龄
                $age = '0岁';
                $sex = '';
                $sexage = $v['sexage'];
                if (isset($sexage) && !empty(trim($sexage))) {
                	$sexageArr = explode("岁", $sexage);
                	if (isset($sexageArr) && !empty($sexageArr)) {
                		$sexageVal = $sexageArr[0];
                		$arr = explode("|", $sexageVal);
                		$age = empty($arr) ? '0岁' : isset($arr[1]) ? trim($arr[1]) . '岁' : '0岁';
                		$sex = empty($arr) ? '': isset($arr[0]) ? trim($arr[0]) : '';
                	}
                }
                
                $createtime=  empty($v['createtime'])?time():strtotime($v['createtime']);
                $reply = $v['reply']; 
                
                $matches = array();
                preg_match_all("'<p>([\s\S]*?)</p>'i", $reply, $matches);
                $fst_matches = empty($matches) ? null : $matches[0];
                
                $reply1 = isset($fst_matches[0])? utils::removehtml($fst_matches[0]):'';
                $reply2 = isset($fst_matches[1]) ?utils::removehtml($fst_matches[1]):'';
                
                $result = $qas->import_data($qid, $title, $content, $createtime, $reply1, $reply2, $department, $age, $sex, $tableName);
                if($result){
                    $import_count++;
                }

            }
        }
        return $import_count;

    }

}
