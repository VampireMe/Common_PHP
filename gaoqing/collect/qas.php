<?php

defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));

//require_once './medoo.php';
require_once CURRENT_PATH . '/Medoos.php';

class qas
{
	//private $medoo = null;
	
	public function __construct(){
		//$datas = require './db.inc.php';
		//$this->medoo = new medoo($datas);
	}
	
    public function import_data($qid, $title, $content, $createtime, $reply1, $reply2, $department, $age, $sex, $tableName) {
    	$insertID = 0;
    	$sexInt = 0;
    	$sexInt = ($sex == '') ? 0: ($sex == '男' ? 1 : 2);
    	
    	$datas = array(
    			'qid' => $qid, 
    			'title' => $title, 
    			'content' => $content, 
    			'ctime' => $createtime, 
    			'reply1' => trim($reply1), 
    			'reply2' => trim($reply2), 
    			'department' => $department, 
    			'age' => $age, 
    			'sex' => $sexInt
    	);
    	
    	//$insertID = $this->medoo->insert("wd_120answer", $datas);
    	$insertID = Medoos::getMedoo()->insert($tableName, $datas);
    	//$insertID = Medoos::getMedoo()->insert("wd_xyanswer", $datas);
    	//$insertID = $this->medoo->insert("wd_xyanswer", $datas);
    	
    	return $insertID;
    }
    
}