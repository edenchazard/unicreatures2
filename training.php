<?php
require './inc/head.php';

if(!isset($_GET['id']) || !is_logged_in()) redirect_to_index();
if(!safe_digit($_GET['id'])) redirect_to_index();

$skill_list = implode(', ', cfg::$general_skills);

$creature = cfg::$db->query("SELECT ow.`creatureID`, ow.`userID`, ow.`care`,ow.`speciality`,
								ow.`nickname`, ow.`gender`,ow.`variety`, ow.`frozen`, db.`stage`
							FROM `creatures_owned` AS ow
							LEFT JOIN creatures_db AS db ON ow.creatureID = db.creatureID
							WHERE ow.`ID` = '{$_GET['id']}'
							LIMIT 1")
					->fetch_assoc();
$creature['ID'] = $_GET['id'];

//check limitations
if(	  !$creature
	|| $creature['frozen']
	|| $creature['stage'] < 2
	|| $creature['userID'] != $user->userID){
	header("Location: /view.php?id={$creature['ID']}");
	exit;
}

$energy = $user->calculate_energy('training');

$skill_limits = get_max_skills($creature['creatureID'], $creature['speciality']);

//current skills
$skills = cfg::$db->query("SELECT $skill_list, powers
						FROM `creatures_owned_training`
						WHERE ID = '{$creature['ID']}' LIMIT 1")
				->fetch_assoc();

//no skills already so insert
if(!$skills){
	cfg::$db->query("
	INSERT INTO creatures_owned_training
	SELECT {$creature['ID']}, $skill_list, 0 FROM training_base_skills
	LEFT JOIN creatures_db ON training_base_skills.familyID = creatures_db.familyID
	WHERE creatures_db.creatureID = {$creature['creatureID']} LIMIT 1");

	$skills = cfg::$db->query("
	SELECT $skill_list, powers FROM `creatures_owned_training`
	WHERE ID = '{$creature['ID']}' LIMIT 1")->fetch_assoc();
}

//is doing an option
if(isset($_GET['option'])){

	if(safe_digit($_GET['option'])){

		//select option
		$option = cfg::$db->query("
		SELECT creatureID, `text`, energy, reward FROM training_options
		WHERE optionID = '{$_GET['option']}' LIMIT 1")
						->fetch_assoc();

		if($option['creatureID'] === $creature['creatureID']){

			if(isset($_GET['y'])){
				if(safe_digit($_GET['y']))
					$multiply_by = $_GET['y'];
			}
			else{
				$multiply_by = 1;
			}
			if(($total_energy_cost = ($option['energy'] * $multiply_by)) <= $energy){
				try{
					$rewards = rewardsstring2array($option['reward']);

					//gonna update at the end with all variables
					$columns = array(); $values = array();

					foreach($rewards['rewards'] as $res){
						if($res['type'] == 'coins'){
							$coins = new coins();
							$coinage = ($multiply_by * $res['amount']);
							$coins->give($coinage, $user->userID);
							$page->s = array(1, "<p class='center allow'>{$option['text']}</p>
												<p class='center allow'>You have gained +$coinage Coins!</p>"
											);
						}
						else if($res['type'] == 'skill'){
							$res['attribute'] = strtolower($res['attribute']);
							$increase = $multiply_by;
							if($skills[$res['attribute']] + $increase > $skill_limits[$res['attribute']]){
								$increase = ($skill_limits[$res['attribute']] - $skills[$res['attribute']]);
							}
							if($increase > 0){
								$skills[$res['attribute']] += $increase;
								$columns[] 	= $res['attribute'];
								$values[]	= $skills[$res['attribute']];
							}
							$page->s = array(1, "<p class='center allow'>{$option['text']}</p>");
						}
						else if($res['type'] == 'power'){
							cfg::$db->query("INSERT INTO creatures_owned_training_powers (`ID`, `skill`, `level`)
											VALUES ({$creature['ID']}, '{$res['attribute']}', $multiply_by)
											ON DUPLICATE KEY UPDATE level = level + $multiply_by");
							//update on screen
							$skills['powers'] += $multiply_by;
							//update db
							$columns[]	= 'powers';
							$values[]	= $skills['powers'];
							$page->s = array(1, "<p class='center allow'>{$option['text']}</p>");
						}
					}
					cfg::$db->update('creatures_owned_training', $columns, $values, 'ID', $creature['ID']);
				}
				catch (sql_error $e){
					cfg::$db->rollback();
					$page->s = array(0, "<p class='center deny'>Sorry, there has been an error.</p>");
				}
			}
			else{
				$page->s = array(0, "<p class='center deny'>You do not have enough energy!</p>");
			}
		}
	}
}

//fetch powers
if($skills['powers'] > 0){
	$powers = cfg::$db->query("
		SELECT skill, level FROM creatures_owned_training_powers
		WHERE ID = '{$creature['ID']}'");
}

$options = cfg::$db->query("
	SELECT optionID, title, energy, reward FROM training_options
	WHERE creatureID = '{$creature['creatureID']}'
	ORDER BY energy
");

require $template .$pagename;
?>