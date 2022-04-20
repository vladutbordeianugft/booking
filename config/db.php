<?php 
	class Database {
		private $host = "127.0.0.1";
		private $database_name = "ezygygafwc";
		private $username = "ezygygafwc";
		private $password = "nhvFKAg6D4";
		public $conn;
		public function getConnection(){
			$this->conn = null;
			try{
				$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
				$this->conn->exec("set names utf8");
				$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			}catch(PDOException $exception){
				echo "Database could not be connected: " . $exception->getMessage();
			}
			return $this->conn;
		}
	}  
?>