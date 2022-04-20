<?php

$database = new Database();
$db = $database->getConnection();




$item = new Trip($db);
$data = json_decode(file_get_contents("php://input"));



$item->title = $data->title;
$item->description = $data->description;
$item->start_date = $data->start_date;
$item->end_date = $data->end_date;
$item->location = $data->location;
$item->price = $data->price;

$item->slug = $item->createSlug();



$check_trip = $item->getTripBySlug();
$check_trip = $check_trip->fetch();





if($check_trip){
	echo json_encode(
		array(
			"status" => "error",
			"message" => "This trip seems to be inserted. Please use another title."
		)
	);
}else{
	
	
	if($item->createTrip()){
		echo json_encode(
			array(
				"status" => "success",
				"message" => "Trip created successfully."
			
			)
		);
	} else{
		echo json_encode(
			array(
				
				"status" => "error",
				"message" => "Trip could not be created."
			
			)
		);
		
	}
	
}




?>