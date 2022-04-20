<?php


$database = new Database();
$db = $database->getConnection();



$trips_data = array();

$item = new Trip($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->slug)){

	$item->slug = $data->slug;
	
	$check_trip = $item->getTripBySlug();
	$check_trip = $check_trip->fetch();
	
	if($check_trip){
		$trips_data['status'] = "success";
		$trips_data['body'] = $check_trip;
	}else{
		$trips_data['status'] = "error";
		$trips_data['message'] = "The trip you are looking for could not be found.";
	}
}else{
	
	$item->search = isset($data->search) ? $data->search : '';
	$item->orderBy = isset($data->orderBy) ? $data->orderBy : '';
	$item->priceRange = isset($data->priceRange) ? $data->priceRange : '';
	
	
	
	
	$get_trips = $item->getTrips();
	$get_trips = $get_trips->fetchAll();
	
	
	$trips_data['status'] = "success";
	$trips_data['total'] = count($get_trips);
	$trips_data['body'] = $get_trips;
	
	
}







echo json_encode($trips_data);
?>