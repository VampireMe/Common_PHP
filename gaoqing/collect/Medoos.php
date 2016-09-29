<?php
defined("CURRENT_PATH") or define("CURRENT_PATH", dirname(__FILE__));
defined("BASE_PATH") or define("BASE_PATH", dirname(CURRENT_PATH));
require_once BASE_PATH . '/medoo.php';

class Medoos{
	private static $medoo = null;
	
	private function __construct() {
		
	}
	
	private function __clone() {
		
	}
	
	public static function getMedoo() {
		if (self::$medoo == null) {
			$params = require_once(BASE_PATH . '/data/db.inc.php');
			self::$medoo = new medoo($params);
		}
		return self::$medoo;
	}
	
}


?>