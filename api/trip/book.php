<?php



include_once '../../config/defines.php';
include_once '../../config/db.php';
include_once '../../class/user.php';
include_once '../../class/trip.php';
include_once '../../class/booking.php';

//JWT TOKEN
require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");





$database = new Database();
$db = $database->getConnection();


$user = new User($db);
$user->token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';

$logged_in = $user->checkUserLoggedIn();



if($logged_in){
	$trip_obj = new Trip($db);
	
	$data = json_decode(file_get_contents("php://input"));
	$trip_obj->id= $data->id;
	
	$check_trip = $trip_obj->getTripById();
	$check_trip = $check_trip->fetch();
	
	
	//CHECK IF TRIP EXIST
	if(!$check_trip){
		echo json_encode(
			array(
				"status" => "error",		
				"message" => "Trip not found."
			)
		);
	}else{
		
		
		//SET TRIP ID AND USER ID TO BOOKING OBJ
		$book_obj = new Booking($db);
		$book_obj->trip_id = $trip_obj->id;
		$book_obj->user_id = $logged_in->data->id;
		
		
		//CHECK IF TRIP HAVE BEEN BOOKED BEFORE BY SAME USER
		$check_booking = $book_obj->getTripBookingByTripIdAndUserId();
		$check_booking = $check_booking->fetch();
		
		if($check_booking){
			echo json_encode(
				array(
					"status" => "error",		
					"message" => "It seems you already booked this trip."
				)
			);
		}else{
			//DO THE BOOKING
			if($book_obj->bookTrip()){
				echo json_encode(
					array(
						"status" => "success",		
						"message" => "Your trip have been succesfully booked."
					)
				);
			}else{
				echo json_encode(
					array(
						"status" => "error",		
						"message" => "Unable to book your trip. Please try again later."
					)
				);
			}
		}
	}
	
}else{
	http_response_code(401);
	echo json_encode(
		array(
			"status" => "error",		
			"message" => "You must login first."
		)
	);
	
}







?>
