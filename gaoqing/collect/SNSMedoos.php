<?php
defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));
require_once CURRENT_PATH . '/medoo.php';

class SNSMedoos{
	private static $medoo = null;
	
	private function __construct() {
		
	}
	
	private function __clone() {
		
	}
	
	public static function getMedoo() {
		if (self::$medoo == null) {
			$params = require_once(CURRENT_PATH . '/data/sns.db.inc.php');
			self::$medoo = new medoo($params);
		}
		return self::$medoo;
	}
	
}


?>