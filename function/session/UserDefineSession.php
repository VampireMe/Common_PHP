<?php

/**
* 功能：自定义 Session 类
* @author gaoqing
* 2015年7月23日
*/
class UserDefineSession{
    
    private $medoo;
    
    public function __construct() {
    	$params = require_once('db.inc.php');
    	$this->medoo = new medoo($params);
    }
    
    /*
     * session 的生命周期分为：
     *      （1）开启 session: start()
     *      （2）读取 session 中的信息：read()
     *      （3）写入 session ：write()
     *      （4）关闭 session: close()
     *      （5）销毁 session: destory()
     *      （6）sesssion 的垃圾回收：gc()
     */
    
    /**
     * 开始 session
     * @author gaoqing
     * 2015年7月23日
     * @return void 空
     */
    public function register(){
       /*
        * 1、首先开启 session
        * 2、读取 session 中的信息
        * 3、向 session 中，写入信息
        * 4、当前脚本运行完之后，关闭 session 
        */
    	
        session_set_save_handler(
            array($this, "start"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
        return true;
    }
    
    /**
     * 开启 session
     * @author gaoqing
     * 2015年10月19日
     * @param string $savePath session文件保存路径
     * @param string $sessionName session 文件的名称
     * @return
     */
    public function start($savePath, $sessionName) {
    	return true;
    }
    
    /**
     * 关闭 session
     * @author gaoqing
     * 2015年7月23日
     * @return void 空
     */
    public function close(){
       return true;
    }
    
    /**
     * 读取 session
     * @author gaoqing
     * 2015年7月23日
     * @return void 空
     */
    public function read($id){
        /*
         * 根据 $sessionid 得到其数据库中，相对应的值，并返回
         */
        $sessionInfo = array();
        if ( isset($id) && !empty($id) ) {
            
        	//从数据库中，得到指定 PHPSESSIONID 的 session 信息
           $sessionInfoArr = $this->medoo->select(
            		"session", 
            		array("create_time", "content"), 
            		array('sessionid' => $id)
            	);
           
           //将保存的 session 信息，解析成所需的数组
           if (!CommonUtils::isEmpty($sessionInfoArr)) {
           	if (!CommonUtils::isEmpty($sessionInfoArr[0]['content'])) {
           		$sessionInfo = $this->decodeSession($sessionInfoArr[0]['content']);
           	}
           }
        }
        return $sessionInfo;
    }
    
    /**
     * 将数据库中查询的 session 信息，解析成所需的数组
     * @author gaoqing
     * 2015年10月16日
     * @param array $sessionInfoStr session原始信息
     * @return array 解析后的所需数组
     */
    private function decodeSession($sessionInfoStr) {
    	$sessionInfo = array();
    	
    	if (!CommonUtils::isEmpty($sessionInfoStr)) {
    		$sessionArr = explode(";", $sessionInfoStr);
    		foreach ($sessionArr as $session ){
    			$sessionKeyAndVal = explode("|", $session);
    			if (CommonUtils::isEmpty($sessionKeyAndVal)) {
    				foreach ($sessionKeyAndVal as $key => $lengthAndVal){
    					$sessionInfo[$key] = substr($lengthAndVal, strrpos($lengthAndVal, ":"));
    				}
    			}
    		}
    	}
    	return $sessionInfo;
    }
    
    /**
     * 写入 session
     * @author gaoqing
     * 2015年7月23日
     * @param string $sessionid session 的 id
     * @param string $content 要保存的数据
     * @return void 空
     */
    public function write($sessionid, $content){
    	
        /*
         * 1、判断 $id 下，是否存在当前 session 信息
         *      （1.1）如果不存在，则直接插入一条数据
         *      （1.2）如果存在，则更新当前 session 信息
         */
        
    	//1、判断 $id 下，是否存在当前 session 信息
       $tempSessionArr = $this->medoo->select(
        		"session", 
        		array("content"), 
        		array('sessionid' => $sessionid)
        	);
       if (CommonUtils::isEmpty($tempSessionArr)) {
       		//（1.1）如果不存在，则直接插入一条数据
       		$data = array(
       				'sessionid' => $sessionid,
       				'create_time' => time(),
       				'content' => $content
       		);
       		$id = $this->medoo->insert("session", $data);
       }
       else {
       		//（1.2）如果存在，则更新当前 session 信息
       		$data = array('content' => $content);
       		$this->medoo->update("session", $data, array('sessionid' => $sessionid));
       }
       return true;
    }
    
    /**
     * 销毁 session
     * @author gaoqing
     * 2015年7月23日
     * @param string $sessionid session的id
     * @return void 空
     */
    public function destroy($sessionid){
        //删除对应的 sessionid 的记录
        $this->medoo->delete("session", array('sessionid' => $sessionid));
    	
    	return true;
    }
    
    /**
     * 回收 session
     * @author gaoqing
     * 2015年7月23日
     * @param int $maxlifetime 最大的回收时间
     * @return void 空
     */
    public function gc($maxlifetime){
    	//删除过期的数据记录
    	$this->medoo->delete("session", array('create_time[>]' => time() - $maxlifetime));
    	
    	return true;
    }
}

?>