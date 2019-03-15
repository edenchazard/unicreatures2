<?php
require './inc/head.php';

$page->s = array();
#todo: make it possible to login with userid + password
if(isset($_POST['username'], $_POST['userid'], $_POST['password'])){
	
	$username = escape($_POST['username']);
	$password = escape($_POST['password']);

	if(was_successful($user->login($username, $password))){
		$page->s = array(1, "<div class='allow'>Thanks, you're now logged in.</div>");
	}
	else{
		$page->s = array(2, "<div class='deny'>Sorry, wrong account name and/or password was entered.</div>");
	}
}

//making new account
elseif(isset($_GET['id']) && !is_logged_in()){
	if(safe_digit($_GET['id'])){
		$password = substr(md5(time()-mt_rand(0,1000)), 0, 8);
		$id = $user->register_guest_account($password);
		$user->login("#$id", $password);
		$page->s = array(3, "<div class='allow'>Thank you for registering! Your password is $password, and your account #ID is $id . DO NOT forget this!");
	}
}
if(!is_logged_in()){
	$page->s = array(4, "");

	//pets to adopt
	$creatures = cfg::$db->query("
		SELECT	`creatures_families`.`familyID`,
				`creatures_families`.family_name,
				`creatures_db`.`creatureID`,
				`creatures_db`.`creature_name`
		FROM `creatures_families`
		LEFT JOIN `creatures_db`
		ON `creatures_families`.`familyID` = `creatures_db`.`familyID`
		WHERE `creatures_families`.`in_basket` = 1
		AND `creatures_db`.`stage` = 1");
}


require $template .$pagename;
?>