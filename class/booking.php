<?php

class Booking{
	
	private $conn;
	private $db_table = "Booking";
	
	
	
	public $id;
	public $trip_id;
	public $user_id;
	
	
	
	
	public function __construct($db){
		$this->conn = $db;
	}
	
	
	
	//GET BOOKING INFO BY ID
	public function getTripBookingByTripIdAndUserId(){
		$sqlQuery = "SELECT id,trip_id,user_id  FROM " . $this->db_table . " where trip_id=? and user_id=?";
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute(array($this->trip_id,$this->user_id));
		return $stmt;
	}
	
	// ADD BOOKING TRIP
	public function bookTrip(){
		
		
		
		$sqlQuery = "INSERT INTO
					". $this->db_table ."
				SET
					trip_id = ?, 
					user_id = ?
					
					";
	
		$stmt = $this->conn->prepare($sqlQuery);
	
		if($stmt->execute(array(
			$this->trip_id,
			$this->user_id
			
		))){
		   return true;
		}
		return false;
	}
	

}