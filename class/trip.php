<?php

class Trip{
	
	private $conn;
	private $db_table = "Trip";
	
	
	public $id;
	public $slug;
	public $title;
	public $description;
	public $start_date;
	public $end_date;
	public $location;
	public $price;
	
	public $search;
	public $orderBy;
	public $priceRange;
	
	private $orderByMethods = array(
		"price-asc" => array(
			'column' => 'price',
			'way' => 'asc',
		),
		"price-desc" => array(
			'column' => 'price',
			'way' => 'desc',
		),
		"start-date-asc" => array(
			'column' => 'start_date',
			'way' => 'asc',
		),
		
		"start-date-desc" => array(
			'column' => 'start_date',
			'way' => 'desc',
		)
	);
	
	
	
	public function __construct($db){
		$this->conn = $db;
	}
	
	
	
	
	// CHECK SLUG IS USED BEFORE. WE WILL USE THIS FUNCTION ALSO TO RETURN TRIP BY SLUG
	public function getTripBySlug(){
		$sqlQuery = "SELECT id,slug,title,description,start_date,end_date,location,price FROM " . $this->db_table . " where slug=?";
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute(array($this->slug));
		return $stmt;
	}
	
	
	
	//GET TRIP INFO BY ID
	public function getTripById(){
		$sqlQuery = "SELECT id,slug,title,description,start_date,end_date,location,price FROM " . $this->db_table . " where id=?";
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute(array($this->id));
		return $stmt;
	}
	
	
	
	// ADD TRIP
	public function createTrip(){
		
		
		
		$sqlQuery = "INSERT INTO
					". $this->db_table ."
				SET
					slug = ?, 
					title = ?, 
					description = ?,
					start_date = ?,
					end_date = ?,
					location = ?,
					price = ?
					
					";
	
		$stmt = $this->conn->prepare($sqlQuery);
	
		if($stmt->execute(array(
			$this->slug,
			$this->title,
			$this->description,
			$this->start_date,
			$this->end_date,
			$this->location,
			$this->price
			
		))){
		   return true;
		}
		return false;
	}
	
	//GET ALL TRIPS 
	//USE CAN USE SORT BY AND RANGE FILTER
	public function getTrips(){
		$sqlQuery_params = array();
		
		$sqlQuery = "SELECT id,slug,title,description,start_date,end_date,location,price FROM " . $this->db_table . "";
		
		
		//search
		if(!empty($this->search)){
			$sqlQuery[] = "title like ?";
			$sqlQuery_params[] = '%'.$this->search.'%';
		}
		
		
		//price range
		if(!empty($this->priceRange)){
			
			$prices = explode("-",$this->priceRange);
			$prices_from = (int) $prices[0];
			$prices_to = (int) $prices[1];
			
			$sqlQuery_add[] = "(price between ? and ?)";
			$sqlQuery_params[] = $prices_from;
			$sqlQuery_params[] = $prices_to;
		}
		
		if(isset($sqlQuery_add)){
			$sqlQuery .= ' where '.implode(" and ",$sqlQuery_add);
		}
		
		//order by
		if(!empty($this->orderBy) && isset($this->orderByMethods[$this->orderBy])){
			
			$order_column = $this->orderByMethods[$this->orderBy]['column'];
			$order_way = $this->orderByMethods[$this->orderBy]['way'];
			
			$sqlQuery .= " order by ".$order_column." ".$order_way;
			
		}
		
		
		
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute($sqlQuery_params);
		return $stmt;
	}
	
	
	//CREATE SLUG FROM TITLE
	public function createSlug(){
		
		$separator = "-";
		$string = $this->title;
		$accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
		$special_cases = array( '&' => 'si', "'" => '');
		$string = mb_strtolower( trim( $string ), 'UTF-8' );
		$string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
		$string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
		$string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
		$string = preg_replace("/[$separator]+/u", "$separator", $string);
		
		$last_char_check = substr($string, -1);
		if($last_char_check == "-"){
			$string = substr($string, 0, -1);
		}
		return $string;
	}
	
	

}