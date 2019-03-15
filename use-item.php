<?php
require './inc/head.php';

$profile_user = new user();

//by user id
if(isset($_GET['id'])){
	if(filter_var($_GET['id'], FILTER_VALIDATE_INT)){
		try{
			$profile_user->bind_data('#' . escape($_GET['id']));
		}
		catch (sql_error $e){
			redirect_to_index();
		}
	}
}
//by username
else if(isset($_GET['username'])){
	try{
		$profile_user->bind_data(escape($_GET['username']));
	}
	catch (sql_error $e){
		redirect_to_index();
	}
}
//default to own
else{
	if(is_logged_in()){
		$profile_user = &$user;
	}
}

//$result = cfg::$db->query("
	//SELECT 

require('/inc/foot.php');
?>