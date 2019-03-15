<?php
require './inc/head.php';

if(!is_logged_in()
	|| !isset($_GET['id'])) redirect_to_index();
if(!safe_digit($_GET['id'])) redirect_to_index();

//fetch the creature
$creature = cfg::$db->query("
	SELECT ow.`creatureID`, ow.`userID`, ow.`care`, ow.`speciality`, ow.`nickname`, ow.`variety`, ow.`frozen`,
			db.`creature_name`, db.familyID, db.stage, fa.`family_name`
	FROM `creatures_owned` AS ow
	LEFT JOIN `creatures_db` AS db ON ow.creatureID = db.creatureID
	LEFT JOIN `creatures_families` AS fa ON db.familyID = fa.familyID
	WHERE ow.`ID` = '{$_GET['id']}'
	LIMIT 1
")->fetch_assoc();


//found creature?
if(!$creature) redirect_to_index();
if($creature['userID'] != $user->userID) redirect_to_index();

$creature['ID'] = $_GET['id'];

if(!isbetween($creature['speciality'], 1, 4)
	&& $creature['stage'] > 1
	&& !$creature['frozen']){

	$page->s = array(0, '');

	//fetch herd
	$herd = cfg::$db->query("SELECT herdID
							FROM user_herds
							WHERE userID = {$user->userID}
							AND familyID = {$creature['familyID']}
							LIMIT 1")
					->fetch_assoc();

	$must_create_herd = false;
	if(!$herd){
			$herd = array(
							'herd_name'	=> ucfirst($creature['family_name']) . " Herd"
						);
			$must_create_herd = true;
	}
	
	//trying to herd
	if(isset($_POST['password'])){
		//correct password
		if(hash('sha256', $_POST['password']) == $user->password){
			//check if herd row for this creature already exists
			$herd_row = cfg::$db->query("SELECT herd.creatureID, herd.variety
										FROM user_herds_data AS herd
										LEFT JOIN user_herds AS uh USING (herdID)
										WHERE herd.creatureID 
										AND variety='{$creature['variety']}' AND uh.userID = {$user->userID}
										LIMIT 1")
								->fetch_assoc();
			try{
				cfg::$db->autocommit(false); 
				//update/insert
				if($must_create_herd){
					cfg::$db->query("INSERT INTO user_herds (userID, herd_name, familyID)
									VALUES ({$user->userID}, '{$herd['herd_name']}', {$creature['familyID']})");
					$herd['herdID'] = cfg::$db->insert_id;
				}

				cfg::$db->query("INSERT INTO user_herds_data (herdID, creatureID, number, variety)
								VALUES({$herd['herdID']}, {$creature['creatureID']}, 1, '{$creature['variety']}')
								ON DUPLICATE KEY UPDATE number = number + 1");

				//delete the creature
				cfg::$db->query("DELETE FROM creatures_owned WHERE ID = {$creature['ID']} LIMIT 1");
				cfg::$db->commit();

				//redirect to the respective herd
				header("Location: /herd.php?id={$herd['herdID']}") and exit;
			}
			catch(sql_error $e){
				$page->s = array(2, "<p class='center deny'>Sorry, an error has occurred.</p>");
				cfg::$db->rollback();
			}
			cfg::$db->autocommit(true);
		}
		else{
			$page->s = array(0, "<p class='center deny'>Sorry, that is the wrong password.</p>");
		}
	}
}
else{
	$page->s = array(2, "<p class='center deny'>This creature cannot be herded!
						To meet herd criteria, they must not be exalted, noble
						or exotic, must not be frozen and must not be an egg.</p>");
}

require $template .$pagename;
?>