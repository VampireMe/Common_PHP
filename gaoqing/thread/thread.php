<?php
//header("Content-Type:text/html;charset=utf-8");

class MyThread extends Thread{
	private $args; 
	
	
	
	public function __construct($args){
		$this->args = $args;
	}
	
	public function run(){
		echo $this->args;
	}
	
}

$myThread = new MyThread("thread !");

if ($myThread->start()) {
	$myThread->join();
}

?>