<?php
require './inc/head.php';
$page->title = 'Trainer';

if(!is_logged_in()) redirect_to_index();

//fetch from db
//first check if there's any eggs in the basket
//table for user after current time (in the basket, eggs
//"exist" until the next hour at xx:00:01
$basket_eggs = cfg::$db->query("
	SELECT basket.exists_until, basket.slot, basket.creatureID, basket.speciality,
	basket.variety, basket.claimed, creatures_db.creature_name, creatures_db.visual_description
	FROM basket
	LEFT JOIN creatures_db ON basket.creatureID = creatures_db.creatureID
	WHERE basket.userID = '{$user->userID}' AND basket.exists_until = '".date("Y-m-d H:00:00", time()+3600)."'
	LIMIT ". env::get('caretaker_eggs_to_show'));


//eggs this hour
$found_eggs = false;

while($egg = $basket_eggs->fetch_assoc()){
	$found_eggs = true;

	if(!$egg['claimed']){
		$claimed_all = false;
		break;
	}
}

//reset pointer for later
$basket_eggs->data_seek(0);

//no eggs found. generate a new set
if(!$found_eggs){
	$basket_eggs = $user->generate_random_family_collection(env::get('caretaker_eggs_to_show'), 'basket');
	$slot = 0;

	//take mins and seconds away, and add in one hour
	//make into a mysql-friendly format
	$exist_until = date("Y-m-d H:00:00", time()+3600);

	foreach($basket_eggs as $egg){
		++$slot; $variety = '';

		if(isset($egg['variety'])) $variety = $egg['variety'];

		//select creatureID
		$extra = cfg::$db->query("SELECT `creatureID` FROM `creatures_db`
								WHERE `stage`= '1' AND `familyID`='{$egg['familyID']}'
								LIMIT 1")->fetch_assoc();

		$data[] = array(	$user->userID, $extra['creatureID'],
							$slot, $exist_until, $egg['speciality'], $variety
						);
	}

	//insert into DB
	cfg::$db->batch_insert('basket', array(	'userID', 'creatureID', 'slot',
									'exists_until', 'speciality',
									'variety'
								), $data);

	//reload
	header("Location: /trainer.php"); exit;
}

require $template .$pagename;
?>