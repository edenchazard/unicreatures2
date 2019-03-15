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

//confirmed abandon
if(isset($_GET['confirm'])){
	cfg::$db->query("DELETE FROM creatures_owned WHERE ID = '{$creature['ID']}' LIMIT 1");
	redirect_to_index();
}

require $template;
?>