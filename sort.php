<?php
require './inc/head.php';

if(!isset($_GET['area']) || !is_logged_in()) redirect_to_index();
if(!safe_digit($_GET['area'])) redirect_to_index();

//check area exists
//remember, 0 = wild
if($_GET['area'] != 0){
	//select from areas
	$area = cfg::$db->query("SELECT `name`, `holding`, description, sort_method
							FROM `user_owned_plots`
							WHERE `areaID` = {$_GET['area']} LIMIT 1")
					->fetch_assoc();

	//didn't find area
	if(!$area) redirect_to_index();

	$area['area'] = $_GET['area'];
}
else{
	$area = array(	'userID' 		=> $user->userID,
					'name'			=> 'Wild',
					'holding'		=> '100',
					'description' 	=> '',
					'area'			=> 0,
					'sort_method'	=> $user->wild_sort
				);
}
//doing an auto sort
if(isset($_POST['sorting'])){
	if(safe_digit($_POST['sorting'])){
		if($_POST['sorting'] >= 0
			&& $_POST['sorting'] <= 4){
			if($area['area'] == 0){
				cfg::$db->query("UPDATE users
								SET wild_sort = {$_POST['sorting']}
								WHERE userID = {$user->userID} LIMIT 1");
			}
			else {
				cfg::$db->query("UPDATE user_owned_plots
								SET sort_method = {$_POST['sorting']}
								WHERE areaID = {$area['area']} LIMIT 1");
			}
			//update locally
			$area['sort_method'] = $_POST['sorting'];
		}
	}
}

if($area['sort_method'] == 1){
	$sql = "SELECT ow.`ID`,  ow.`creatureID`, ow.`speciality`, ow.`variety`, ow.`nickname`, ow.`frozen`
	FROM `creatures_owned` AS ow
	LEFT JOIN creatures_db AS db ON ow.creatureID = db.creatureID
	WHERE ow.userID = {$user->userID} AND ow.areaID = {$area['area']}
	ORDER BY db.familyID, db.stage, ow.speciality";
}
elseif($area['sort_method'] == 2){
	$sql = "SELECT ow.`ID`,  ow.`creatureID`, ow.`speciality`, ow.`variety`, ow.`nickname`, ow.`frozen`
	FROM `creatures_owned` AS ow
	LEFT JOIN creatures_db AS db ON ow.creatureID = db.creatureID
	LEFT JOIN creatures_families AS fa ON db.familyID = fa.familyID
	WHERE ow.userID = {$user->userID} AND ow.areaID = {$area['area']}
	ORDER BY fa.release_day";
}
elseif($area['sort_method'] == 3){
	$sql = "SELECT `ID`,  `creatureID`, `speciality`, `variety`, `nickname`, `frozen`
	FROM `creatures_owned` AS ow
	WHERE ow.userID = {$user->userID} AND ow.areaID = {$area['area']}
	ORDER BY ow.`custom_sort`, ow.`ID` DESC";
}
//default
else{
	$sql = "SELECT `ID`,  `creatureID`, `speciality`, `variety`, `nickname`, `frozen`
	FROM `creatures_owned` AS ow
	WHERE ow.userID = {$user->userID} AND ow.areaID = {$area['area']}
	ORDER BY ow.`ID` DESC";
}

$creatures = cfg::$db->query($sql);

$areas = cfg::$db->query("
	SELECT `areaID`, `name`, `holding`
	FROM `user_owned_plots` WHERE `userID` = '{$user->userID}'");

require $template .$pagename;
?>