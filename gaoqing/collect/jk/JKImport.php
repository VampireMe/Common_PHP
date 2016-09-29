<?php

$basePath = dirname(__FILE__);
$basePath = substr($basePath, 0, strrpos($basePath, "/"));
defined("CURRENT_PATH") or define("CURRENT_PATH", $basePath);

require_once CURRENT_PATH . '/qas.php';
require_once CURRENT_PATH . '/utils.php';


class JKImport {

    public static function import_data(array $arr_files = array(), $tableName){
    	$qas = new qas();
    	
        $import_count = 0;
        foreach($arr_files as $txt_path){
            $d_array = parse_ini_file($txt_path,true);
            
            foreach($d_array as $k=>$v){
            	
            	$qid = intval($v['id']); 
            	$qid = trim($qid);
            	if (empty($qid)) {
            		continue;
            	}
            	
                $title =$v['title'];
                $title = trim($title);
                if (empty($title)) {
                	continue;
                }
                $reply = $v['reply']; 
                if ($reply == "escape") {
                	continue;
                }
                
                $content=$v['content'];

                //科室：
                $department = "";
                if (isset($v['department']) && !empty($v['department'])) {
                	$departmentArr = explode(" → ", $v['department']);

                    //只获取 2 级科室的信息，如果是 3 级科室的话，去掉最后一级的 疾病信息
                   if(isset($departmentArr[2])){
                        unset($departmentArr[2]);
                   }
                	$department = implode(",", $departmentArr);
                }
                
                //性别年龄
                $age = '0岁';
                $sex = '';
                $sexage = $v['sexage'];
                if (isset($sexage) && !empty(trim($sexage))) {
                	$sexageArr = explode(" | ", $sexage);
                	if (isset($sexageArr) && !empty($sexageArr)) {
                		$arr = $sexageArr;
                		$sex = empty($arr) ? '': isset($arr[0]) ? trim($arr[0]) : '';
                		$age = empty($arr) ? '0岁' : isset($arr[1]) ? trim($arr[1]) . '岁' : '0岁';
                	}
                }
                
                $createtime=  empty($v['createtime'])?time():strtotime($v['createtime']);
                
                $length = strlen($reply);
                if (strstr($reply, "<p>")) {
                	if (strpos($reply, "<p>") != 0) {
                		$reply = str_pad ( $reply ,  $length + 3 ,  "<p>" ,  STR_PAD_LEFT );
                	}
                }else{
                	$reply = str_pad ( $reply ,  $length + 3 ,  "<p>" ,  STR_PAD_LEFT );
                }
                
                $matches = array();
                preg_match_all("'<p>([\s\S]*?)</p>'i", $reply, $matches);
                $fst_matches = empty($matches) ? null : $matches[0];
                
                $reply1 = isset($fst_matches[0])? utils::removehtml($fst_matches[0]):'';
                $reply1 = self::dealReply($reply1);
                $reply2 = isset($fst_matches[1]) ?utils::removehtml($fst_matches[1]):'';
                $reply2 = self::dealReply($reply2);
                
                $result = $qas->import_data($qid, $title, $content, $createtime, $reply1, $reply2, $department, $age, $sex, $tableName);
                if($result){
                    $import_count++;
                }

            }
        }
        return $import_count;

    }
    
    private static function dealReply($initReply) {
    	$reply = $initReply;

    	//把 【医生建议】替换为 【指导意见】
    	$search = "医生建议";
    	$replace = "指导意见";
        $reply = str_replace($search, $replace, $initReply);
    	return $reply;
    }

}
