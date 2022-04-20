<?php
include_once '../../config/defines.php';
include_once '../../config/db.php';
include_once '../../class/user.php';
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

$item = new User($db);
$data = json_decode(file_get_contents("php://input"));
$item->email = $data->email;
$item->password = $data->password;



$check_email = $item->checkUserEmail();
$get_user_data = $check_email->fetch();

if(!$get_user_data){
	http_response_code(401);
	echo json_encode(
		array(
			"status" => "error",
			"message" => "Invalid auth data."
		)
	);
}else{
	
	$fail_login_attempts = $get_user_data['fail_attempts'];
	$fail_login_attempts_date = $get_user_data['fail_attempts_date'];
	$fail_login_attempts_date_expire = time()-$fail_login_attempts_date;
	
	
	if($fail_login_attempts == 0 || $fail_login_attempts == 6 || $fail_login_attempts_date_expire > 60){
		$item->fail_attempts = 1;
		$item->fail_attempts_date = time();
	}else{
		$item->fail_attempts_date = $fail_login_attempts_date;
		$item->fail_attempts = ($fail_login_attempts+1);
	}
	
	if($fail_login_attempts >= 5 && $fail_login_attempts_date_expire <= 60){
		
		http_response_code(401);
		echo json_encode(
			array(
				"status" => "error",		
				"message" => "You have been blocked from login system. Please try again in 1 minute."
			)
		);
		
		
	}else{
		
		if(password_verify($item->password,$get_user_data['password'])){
			
			$issuer_claim = "MADALIN"; 
			$audience_claim = "http://phpstack-624413-2577689.cloudwaysapps.com";
			$issuedat_claim = time(); // issued at
			$notbefore_claim = $issuedat_claim + 1; 
			$expire_claim = $issuedat_claim + 3600; 
			$token = array(
				"iss" => $issuer_claim,
				"aud" => $audience_claim,
				"iat" => $issuedat_claim,
				"nbf" => $notbefore_claim,
				"exp" => $expire_claim,
				"data" => array(
					"id" => $get_user_data['id'],
					"firstname" => $get_user_data['first_name'],
					"lastname" => $get_user_data['last_name'],
					"email" => $get_user_data['email']
			));
			
			http_response_code(200);
			
			$jwt = JWT::encode($token, TOKEN_LOGIN_SECRET_KEY,'HS256');
			
			setcookie("token",$jwt,0,"/");
			
			
			
			//RESET LOGIN ATTEMPTS ON SUCCES LOGIN
			$item->fail_attempts = 0;
			$item->fail_attempts_date = 0;
			$item->updateUserLoginFailAttempt();
			
				
			echo json_encode(
				array(
					"status" => "success",
					"message" => "You have been successfully logged in.",
					"jwt" => $jwt,
					"email" => $get_user_data['email'],
					"expireAt" => $expire_claim
				)
			);
			
			
		}else{
			http_response_code(401);
			
			$item->updateUserLoginFailAttempt();
			
			
			echo json_encode(
				array(
					"status" => "error",
					"message" => "Invalid auth data. Login attempts: ".$item->fail_attempts." of 5."
				),
			);
		}
		
	}
	
	
	
	
	
	
}



?>