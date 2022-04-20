<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require '../config/defines.php';
require '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if($_GET['obj'] == "user"){
	
	
}else if($_GET['obj'] == "trip"){
	
	include_once '../class/trip.php';
	include_once 'trip/read.php';

}else{
	
	echo json_encode(
		array("message" => "Invalid object call")
	);
	
}
?>