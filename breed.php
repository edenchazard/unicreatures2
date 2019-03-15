<?php
require './inc/head.php';

if(!isset($_GET['id']) || !is_logged_in())
	redirect_to_index();

if(!safe_digit($_GET['id']))
	redirect_to_index();

$id = $_GET['id'];
$page->s = array(0, '');

$creature = cfg::$db->query("
	SELECT	creatures_owned.`creatureID`, creatures_owned.`userID`, creatures_owned.`care`, creatures_owned.`speciality`,
			creatures_owned.`nickname`, creatures_owned.`gender`, creatures_owned.`variety`, creatures_owned.`frozen`,
			creatures_db.`stage`, creatures_db.familyID
	FROM `creatures_owned`
	LEFT JOIN creatures_db ON creatures_owned.creatureID = creatures_db.creatureID
	WHERE creatures_owned.`ID` = '$id'
	LIMIT 1
")->fetch_assoc();

if(!$creature)
	redirect_to_index();

$creature['ID'] = $id;

//check limitations
if(	$creature['frozen']
	|| $creature['userID'] != $user->userID){
	header("Location: /view.php?id={$creature['ID']}") and exit;
}

//find what can be bred to
if(!isset($_GET['to'])){
	$can_breed_to = cfg::$db->query("
	SELECT	creatures_owned.ID, creatures_owned.`creatureID`, creatures_owned.`care`, creatures_owned.`speciality`,
			creatures_owned.`nickname`
	FROM creatures_db_breeding
	LEFT JOIN creatures_owned ON creatures_db_breeding.creatureID_female = creatures_owned.creatureID
	OR creatures_db_breeding.creatureID_male = creatures_owned.creatureID
	WHERE (
			creatureID_female = {$creature['creatureID']}
						OR
			creatureID_male = {$creature['creatureID']}
	)
	AND creatures_owned.userID = {$user->userID}
	AND creatureID != {$creature['creatureID']}");
}
//trying to breed to something
else{
	if(safe_digit($_GET['to'])){
		//check partner exists
		if($partner = cfg::$db->query("SELECT creatureID, userID
									FROM creatures_owned
									WHERE ID = '{$_GET['to']}' LIMIT 1
							")->fetch_assoc()){
			//make sure they own partner
			if($partner['userID'] == $user->userID){

				//fetch breeding option exists (indirectly check the combo is possible)
				$option = cfg::$db->query("SELECT creatureID_result AS creatureID FROM creatures_db_breeding
										WHERE	(creatureID_female = {$creature['creatureID']} AND creatureID_male = {$partner['creatureID']})
										OR		(creatureID_male = {$creature['creatureID']} AND creatureID_female = {$partner['creatureID']})
								")->fetch_assoc();
				//breed
				$bred_egg = give_creature($user->userID, $option['creatureID']);
				$page->s = array(1, "<p class='center allow'>You've bred an egg!</p>
									<p class='center'><a href='/view.php?id=$bred_egg'><img src='".mkimg($option)."' /></a></p>");
			}
		}
	}
}

								
								//creatures_owned.`creatureID`, creatures_owned.`care`, creatures_owned.`speciality`,
								//	creatures_owned.`nickname`
							//	LEFT JOIN creatures_owned ON creatures_owned.creatureID = creatures_db.creatureID
//get creatureID of resulting familyID
/*$creatureID = cfg::$db->query("SELECT creatureID
							FROM creatures_db WHERE familyID = 192
							AND stage = 1")
					->fetch_assoc();
*/
//breed it.
//give_creature($user->userID, $creatureID['creatureID']);

require $template .$pagename;
?>