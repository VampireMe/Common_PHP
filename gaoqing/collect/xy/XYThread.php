<?php

$basePath = dirname(__FILE__);
$basePath = substr($basePath, 0, strrpos($basePath, "/"));
defined("CURRENT_PATH") or define("CURRENT_PATH", $basePath);

require_once CURRENT_PATH . '/xy/qas.php';
require_once CURRENT_PATH . '/xy/utils.php';

class XYThread extends Thread {
	
	public $data = array();
	private $flag = 1;
	private $qas = null;
	public $import_count = 0;
	
	public function __construct($data, $flag){
		$this->data = $data;
		$this->flag = $flag;
		$this->qas = new qas();
	}
	
	public function run() {
		
		echo "当前执行的线程是：" . $this->flag . " 个！";
		
		$d_array = $this->data;
		
		$import_count = 0;
		foreach($d_array as $k=>$v){
			 
			$qid = intval($v['id']);
			$title =$v['title'];
			$content=$v['content'];
		
			$department = "";
			if (isset($v['department']) && !empty($v['department'])) {
				$departmentArr = explode(" → ", $v['department']);
				//删除头和尾的数据
				array_shift($departmentArr);
				array_shift($departmentArr);
				array_pop($departmentArr);
				 
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
					$age = empty($arr) ? '0岁' : isset($arr[1]) ? trim($arr[1]) : '0岁';
				}
			}
		
			$createtime=  empty($v['createtime'])?time():strtotime($v['createtime']);
			$reply = $v['reply'];
		
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
		
			$result = $this->qas->import_data($qid, $title, $content, $createtime, $reply1, $reply2, $department, $age, $sex);
			if($result){
				$import_count++;
			}
		}		
		$this->import_count = $import_count;
	}
	
	private static function dealReply($initReply) {
		$reply = $initReply;
		 
		$findStr = "意见";
		$desc = "问题分析";
		if (isset($initReply) && !empty($initReply)) {
	
			//判断参数中，是否存在“意见”
			if (mb_strstr($initReply, $findStr, 'utf-8')) {
		   
				if (mb_strstr($initReply, $desc, 'utf-8')) {
					if (mb_strpos($initReply, $desc, 0, 'utf-8') != 0) {
						$reply = "问题分析" . "：" . $initReply;
					}
				}else{
					$reply = $desc . "：" . $initReply;
				}
			}else{
				if (mb_strstr($initReply, "指导意见", 'utf-8')) {
					$reply = "问题分析" . "：" . $initReply;
				}else{
					$reply = "指导意见" . "：" . $initReply;
				}
			}
		}
		return $reply;
	}
	
}


?>