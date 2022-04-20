<?php
	use \Firebase\JWT\JWT;
	use \Firebase\JWT\Key;
	
	class User{
		
		private $conn;
		private $db_table = "User";
		
		
		public $id;
		public $first_name;
		public $last_name;
		public $email;
		
		public $token;
		public $fail_attempts;
		public $fail_attempts_date;
		
		
		public function __construct($db){
			$this->conn = $db;
		}
		
		
		
		// GET ALL
		public function getUsers(){
			$sqlQuery = "SELECT id,first_name,last_name,email FROM " . $this->db_table . "";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->execute();
			return $stmt;
		}
		
		
		// CHECK USER EMAIL IF USED
		public function checkUserEmail(){
			$sqlQuery = "SELECT id,first_name,last_name,email,password,fail_attempts,fail_attempts_date FROM " . $this->db_table . " where email=?";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->execute(array($this->email));
			return $stmt;
		}
		
		
		
		// CHECK USER IS LOGGED IN
		public function checkUserLoggedIn(){
			if(!empty($this->token)){
				
				try{
					$decoded = JWT::decode($this->token, new Key(TOKEN_LOGIN_SECRET_KEY, 'HS256'));
					
					return $decoded;
				}catch(Exception $e){
					//$e->getMessage()
					return false;
				}
				
			}
		}
		
		//ADD USER USER FAIL ATTEMPT
		public function updateUserLoginFailAttempt(){
			
			
			$sqlQuery = "Update " . $this->db_table . " set fail_attempts=?,fail_attempts_date=? where email=?";
		
			$stmt = $this->conn->prepare($sqlQuery);
			
			echo $this->$fail_attempts;
			if($stmt->execute(array(
				$this->fail_attempts,
				$this->fail_attempts_date,
				$this->email,
				
			))){
			   return true;
			}
			return false;
		}
		
		
		// ADD USER
		public function createUser(){
			
			
			$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
			
			
			$sqlQuery = "INSERT INTO
						". $this->db_table ."
					SET
						first_name = ?, 
						last_name = ?, 
						email = ?,
						password = ?";
		
			$stmt = $this->conn->prepare($sqlQuery);
		
			if($stmt->execute(array(
				$this->first_name,
				$this->last_name,
				$this->email,
				$password_hash
				
			))){
			   return true;
			}
			return false;
		}
		      
		
		
	}
?>