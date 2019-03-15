<?php
require './inc/head.php';
$profile_user = make_user_from_query_string();

//invalid
if(!$profile_user)
	redirect_to_index();

env::set('profile_user_obj', $profile_user);

function check_accomplishment($familyID, $column, $user){
	$checkoff = cfg::$db->query("INSERT INTO accomplishments (userID, familyID, {$column})
								VALUES ({$user->userID}, {$familyID}, 1)
								ON DUPLICATE KEY UPDATE {$column} = 1");
	if(cfg::$db->affected_rows){
		//update user's accomplishments
		cfg::$db->query("UPDATE users SET accomplishments = accomplishments + 1
						WHERE userID = {$user->userID} LIMIT 1");
		$user->accomplishments += 1;
	}
}

//check for claims
if(isset($_GET['fully_evolved'])){
	if(safe_digit($_GET['fully_evolved'])){
		//check allow
		$check = cfg::$db->query("SELECT can_fully_evolved
									FROM `creatures_families_allowed_accs`
									WHERE familyID = {$_GET['fully_evolved']}
									LIMIT 1")
							->fetch_assoc();
		if($check){
			if($check['can_fully_evolved']){
				//allowable accomp
				//check they actually have it
				$max = cfg::$db->query("SELECT MAX(stage) AS last_stage FROM creatures_db
										WHERE familyID = {$_GET['fully_evolved']}")
								->fetch_assoc();

				$validate = cfg::$db->query("SELECT 1 FROM creatures_owned AS ow
											LEFT JOIN creatures_db AS db USING(creatureID)
											WHERE db.stage = {$max['last_stage']}
											AND familyID = {$_GET['fully_evolved']}
											AND userID = {$user->userID}
											LIMIT 1")
								->fetch_assoc();
				if($validate){
					check_accomplishment($_GET['fully_evolved'], 'fully_evolved', $user);
				}
			}
		}
	}
}
if(isset($_GET['full_family'])){
	if(safe_digit($_GET['full_family'])){
		//check allow
		$check = cfg::$db->query("SELECT can_full_family
									FROM `creatures_families_allowed_accs`
									WHERE familyID = {$_GET['full_family']}
									LIMIT 1")
							->fetch_assoc();
		if($check){
			if($check['can_full_family']){
				//allowable accomp
				//check they actually have it

				$i_have = cfg::$db->query("SELECT COUNT(DISTINCT(db.stage)) AS stages FROM creatures_owned AS ow
											LEFT JOIN creatures_db AS db USING(creatureID)
											WHERE familyID = {$_GET['full_family']}
											AND userID = {$user->userID}
											AND stage != 1")
								->fetch_assoc();
				$required = cfg::$db->query("SELECT COUNT(DISTINCT(stage))-1 AS stages
											FROM creatures_db
											WHERE familyID = {$_GET['full_family']}")
									->fetch_assoc();

				if($i_have['stages'] >= $required['stages']){
					check_accomplishment($_GET['full_family'], 'full_family', $user);
				}
			}
		}
	}
}
/* if(isset($_GET['both_genders'])){
	if(safe_digit($_GET['both_genders'])){
		//check allow
		$check = cfg::$db->query("SELECT can_both_genders
									FROM `creatures_families_allowed_accs`
									WHERE familyID = {$_GET['both_genders']}
									LIMIT 1")
							->fetch_assoc();
		if($check){
			if($check['can_both_genders']){
				//allowable accomp
				//check they actually have it

				$required = cfg::$db->query("SELECT COUNT(DISTINCT(gender))-1 AS gender
											FROM creatures_owned
											WHERE familyID = {$_GET['full_family']}")
									->fetch_assoc();

				if($i_have['stages'] >= $required['stages']){
					$checkoff = cfg::$db->query("INSERT INTO accomplishments (userID, familyID, full_family)
												VALUES ({$user->userID}, {$_GET['full_family']}, 1)
												ON DUPLICATE KEY UPDATE full_family = 1");
					if(cfg::$db->affected_rows){
						check_accomplishment($_GET['full_family'], 'full_family', $user);
					}
				}
			}
		}
	}
} */

if(isset($_GET['fully_trained'])){
	if(safe_digit($_GET['fully_trained'])){
		//check allow
		$check = cfg::$db->query("SELECT can_fully_trained
									FROM `creatures_families_allowed_accs`
									WHERE familyID = {$_GET['fully_trained']}
									LIMIT 1")
							->fetch_assoc();
		if($check){
			if($check['can_fully_trained']){
				//allowable accomp
				//check they actually have it
				$creatureID = cfg::$db->query("SELECT MAX(creatureID) as final_id, MAX(stage) AS final_stage FROM creatures_db
												WHERE familyID = {$_GET['fully_trained']}")
										->fetch_assoc();

				//calc what training must add up to
				$add_up_to = cfg::$db->query("SELECT ".implode('+', cfg::$general_skills)." AS max_number
												FROM training_max_skills
												WHERE creatureID = {$creatureID['final_id']}")
									->fetch_assoc();

				$i_have = cfg::$db->query("SELECT ".implode('+', cfg::$general_skills)." AS max_number
											FROM creatures_owned_training AS train
											INNER JOIN creatures_owned AS ow USING(ID)
											WHERE ow.creatureID = {$creatureID['final_id']}
											AND ow.userID = {$user->userID}
											HAVING max_number >= {$add_up_to['max_number']}")
									->fetch_assoc();

				if($i_have){
					check_accomplishment($_GET['fully_trained'], 'fully_trained', $user);
				}
			}
		}
	}
}

//get
$accomplishments = cfg::$db->query("SELECT ac.*, fa.family_name, accs.fully_evolved, accs.full_family,
										accs.fully_trained, accs.both_genders, accs.have_noble,
										accs.have_exalted, accs.full_herd
										FROM `creatures_families_allowed_accs` AS ac
										LEFT JOIN accomplishments AS accs ON (ac.familyID = accs.familyID AND accs.userID = {$profile_user->userID})
										LEFT JOIN creatures_families AS fa ON (ac.familyID = fa.familyID)
									");
require $template .$pagename;
?>