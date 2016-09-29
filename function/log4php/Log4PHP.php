<?php


class Log4PHP {
	private $log = null;
	
	public function __construct() {
		Logger::configure("config.xml");
		$this->log = Logger::getLogger(__CLASS__);
	}
	
	public function test() {
		$this->log->debug("this is my first log test!");
		$this->log->info("this is my first log test!");
		$this->log->warn("this is my first log test!");
	}
	
}

?>