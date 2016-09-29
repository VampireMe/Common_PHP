<?php

$basePath = dirname(__FILE__);
$basePath = substr($basePath, 0, strrpos($basePath, "/"));
defined("CURRENT_PATH") or define("CURRENT_PATH", $basePath);

require_once CURRENT_PATH . '/xy/utils.php';
require_once CURRENT_PATH . '/xy/XYThread.php';


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
class XYImport_thread {
    
    
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
    public static function import_data(array $arr_files = array()){
    	
        $import_count = 0;
        foreach($arr_files as $txt_path){
            $d_array = parse_ini_file($txt_path,true);
            
            $oneArr = array();
            $twoArr = array();
            
            if (isset($d_array) && !empty($d_array)) {
            	
            	$i = 0;
            	foreach($d_array as $k=>$v){
            		if ($i % 2 == 0) {
            			$oneArr[] = $v;
            		}else {
            			$twoArr[] = $v;
            		}
            		$i++;
            	}
            }
            
            //开启一个线程：
            $oneThread = new XYThread($oneArr, 1);
            $oneThread->start();
            $twoThread = new XYThread($twoArr, 2);
            $twoThread->start();
            
        }
        return $import_count;

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
