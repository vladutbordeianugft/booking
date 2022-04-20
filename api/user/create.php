<?php



$database = new Database();
$db = $database->getConnection();




$item = new User($db);
$data = json_decode(file_get_contents("php://input"));
$item->first_name = $data->first_name;
$item->last_name = $data->last_name;
$item->email = $data->email;
$item->password = $data->password;


$check_email = $item->checkUserEmail();
$check_email = $check_email->fetch();



if($check_email){
	echo json_encode(
		array(
			"status" => "error",
			"message" => "This email address have been already used."
		)
	);
}else{
	if($item->createUser()){
		echo json_encode(
			array(
				"status" => "success",
				"message" => "User created successfully."
			)
		);
	} else{
		echo json_encode(
			array(
				"status" => "error",
				"message" => "User could not be created."
			)
		);
		
	}
}


?>