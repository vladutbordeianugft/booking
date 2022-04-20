<?php
if($logged_in){
	
	echo "Welcome ".$logged_in->data->lastname;
}else{
	echo "Welcome guest";
	
	
}
?>