<?php

class Database extends PDO{
	private $tipodb 	= "mysql";
	private $servername = "localhost:3306";
	private $user 		= "root";
	private $password 	= "yourpass";
	private $dbname 	= "yourdb";

	public function __construct() {
		try{
			parent::__construct("{$this->tipodb}:dbname={$this->dbname};host={$this->servername};charset=utf8mb4", $this->user, $this->password);
		}catch(PDOException $e){
			echo 'An error has occurred and cannot connect to the database. Details: ' . $e->getMessage();
			exit;
		}
	}
}

?>
