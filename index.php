<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require "vendor/autoload.php";
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

include_once 'config/defines.php';
include_once 'config/db.php';
include_once 'class/user.php';
include_once 'class/trip.php';






$database = new Database();
$db = $database->getConnection();



//include view files
$loaded_page = 'home';
if(isset($_GET['page'])){
	$loaded_page = $_GET['page'];
}





//check if user is logged in
$user = new User($db);
$user->token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';

$logged_in = $user->checkUserLoggedIn();

//logout user if logout page is called
if($loaded_page == "logout"){
	setcookie("token",null,0,"/");
	$logged_in = null;
}




?>


<!DOCTYPE html>
<html>
<head>
<title>Booking Site</title>
		
	<script>
		var BASE_URL = '<?=BASE_URL;?>';
	</script>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
	 <script src="<?=BASE_URL.'/assets/js/custom.js';?>"></script> 
	 
	 <style>
	 .container{
		 max-width: 800px;
		 margin: 0 auto;
		 border: 1px solid #ccc;
		 padding: 1rem;
		 text-align: center;
	 }
	 
	 .menu{
		 list-style: none;
		 max-width: 500px;
		 
		 margin: 0 auto;
		 margin-bottom: 50px;
		 text-align: center;
		 padding: 0;
	 }
	 
	 .menu li{
		 display: inline-block;
		 padding-left: 10px;
		 padding-right: 10px;
	 
	 }
	 </style>
	 
	 
</head>


<body>

	<ul class="menu">
		<li><a href="/">Home</a></li>
		<li><a href="/app/trips">View trips</a></li>
		<?php
		if($logged_in){
		?>
			<li><a href="/app/logout">Log out?</a></li>
		<?php
		}else{
		?>
			<li><a href="/app/login">Login</a></li>
		<?php
		}
		?>
		
	</ul>
	
	<div class="container">
		<?php
		
		
		
		if($loaded_page == "home"){
			include "view/home.php";
		}else if($loaded_page == "trips"){
			include "view/trips.php";
		}else if($loaded_page == "login"){
			include "view/login.php";
		}else if($loaded_page == "logout"){
			include "view/logout.php";
		}
		
		?>
	</div>
</body>
</html> 
