<?php
require './inc/head.php';

if(!safe_digit($_GET['id']))
	redirect_to_index();

if(!is_logged_in())
	redirect_to_index();
	
$creature = cfg::$db->query("
	SELECT	`creatureID`,
			`userID`,
			`care`,
			`speciality`,
			`nickname`,
			`gender`,
			`variety`,
			`frozen`
	FROM `creatures_owned`
	WHERE `ID` = '{$_GET['id']}'
	LIMIT 1
")->fetch_assoc();

//check it exists
if(!$creature)
	redirect_to_index();

//check they own it
if($creature['userID'] != $user->userID)
	redirect_to_index();

$creature['ID'] = $_GET['id'];

if(isset($_POST['name'])){
	//do query
	$new_name = escape($_POST['name']);
	try{
		cfg::$db->query("
		UPDATE creatures_owned SET nickname = '$new_name'
		WHERE ID = '{$creature['ID']}' LIMIT 1");
		$page->s = array(1, "<p class='center allow'>You have successfully renamed your creature!</p>");
	}
	catch(sql_error $e){
		$page->s = array(1, "<p class='center deny'>Sorry, an error has occured.</p>");
	}
}

require $template .$pagename;
?>