<?php
require './inc/head.php';

if(!is_logged_in()) header('Location: /login.php') and exit;
if(!isset($_GET['egg'])) header("Location: /trainer.php") and exit;
if(!safe_digit($_GET['egg'])) header("Location: /trainer.php") and exit;

$page->title = "Take an egg";

$index = $_GET['egg'];

//we only want to do things if this egg is actually
//available
if(!$egg = cfg::$db->query("
	SELECT 	ba.creatureID, ba.speciality, ba.variety, db.creature_name,
			db.visual_description, db.lifestyle, fa.family_name
	FROM basket AS ba
	LEFT JOIN creatures_db AS db ON ba.creatureID = db.creatureID
	LEFT JOIN creatures_families AS fa ON db.familyID = fa.familyID
	WHERE ba.userID = {$user->userID}
	AND db.stage = 1
	AND ba.slot = $index
	AND ba.exists_until = '".date("Y-m-d H:00:00", time()+3600)."'
	AND ba.claimed = 0
	LIMIT 1")->fetch_assoc())
	header("Location: /trainer.php") and exit;
	
//count unhatched eggs
$number_of_eggs = cfg::$db->query("
SELECT COUNT(*) AS `number`
FROM creatures_owned
LEFT JOIN creatures_db
ON creatures_owned.creatureID = creatures_db.creatureID
WHERE userID = {$user->userID}
AND creatures_db.stage = 1
AND creatures_owned.frozen = 0")->fetch_assoc();

//are we collecting this egg, or just displaying
//its individual page?
if(isset($_GET['collect']) && $number_of_eggs['number'] <= 5){
	//give to user
	$creature_id = give_creature($user->userID, $egg['creatureID'],
		array(
			"speciality"	=>	$egg['speciality'],
			"variety"		=>	$egg['variety']
		)
	);

	if($creature_id !== false){
		//set it as claimed from basket
		//expired eggs will be deleted by cleanup
		//utility
		cfg::$db->query("
			UPDATE basket
			SET claimed='1'
			WHERE userID='{$user->userID}'
			AND slot = '$index'
			AND exists_until = '".date("Y-m-d H:00:00", time()+3600)."'
			LIMIT 1");
		header("Location: /view.php?id=$creature_id") and exit;
	}
}


/*$tpl->set(
	array(
		'title'					=> 'Take an egg',
		'egg'					=> $egg,
		'slot_number'			=> $index,
		'can_collect'			=> ($number_of_eggs['number'] <= 5),
		'unhatched'				=> $number_of_eggs['number'],
		'visual_description'	=> esc_html($egg['visual_description']),
		'lifestyle'				=> parse_template($egg, $egg['lifestyle']),
		'is_special'			=> ($egg['speciality'] > 0),
		'image'					=> mkimg($egg),
		'family'				=> $egg['family_name']
	)
);*/

require $template .$pagename;
?>