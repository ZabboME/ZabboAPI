<?php
require_once 'config.php';
class Database extends PDO{
	public function __construct() {
		try{
			parent::__construct("mysql:dbname=".DB_NAME.";host=".DB_HOST.";charset=utf8mb4", DB_USER, DB_PASS);
		}catch(PDOException $e){
			error_log('Database Connection Error: ' . $e->getMessage());
			echo 'An error has occurred and cannot connect to the database.';
			exit;
		}
	}
}
?>