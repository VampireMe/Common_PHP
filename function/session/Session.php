<?php

/**
 * session 单例类
 * @author gaoqing
 * 2015年10月19日
 */
class Session{
	private static $session = null;
	
	private function __construct() {
		
	}
	
	private function __clone(){
		
	}
	
	public static function getSession(){
		if (CommonUtils::isEmpty(self::$session)) {
			self::$session = new UserDefineSession();
		}
		return self::$session;
	}
	
}


?>