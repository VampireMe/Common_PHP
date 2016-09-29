<?php


class Person {
	private $name = "";
	private $age = 20;
	
	public function __construct($name, $age) {
		$this->name = $name;
		$this->age = $age;
	}
	
	public function say() {
		echo "<br />";
		echo $this->name . ", " . $this->age;
	}
	
	public function __set($name, $value){
		$this->$name = $value;
	}
	
	public function __get($name) {
		return isset($this->$name) ? $this->$name : "aaa";
	}
	
	
	
}



?>