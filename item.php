<?php
require '/inc/head.php';

//checks
if(!isset($_GET['item'], $_GET['id']))
	redirect_to_index();

if(!is_logged_in())
	redirect_to_index();

if(!safe_digit($_GET['id']))
	redirect_to_index();

$_GET['item'] = escape($_GET['item']);
$msgs = array();

//check they have the item in inventory
if(!$user->inventory($_GET['item']))
	redirect_to_index();

//try to find creature
$creature = cfg::$db->query("
	SELECT	ow.creatureID, ow.userID, ow.speciality, ow.variety, ow.nickname,
			ow.gender, ow.frozen, fa.family_name, db.creature_name, db.`stage`,
			ow.care, fa.gender_only, fa.deny_ne, db.familyID
	FROM `creatures_owned` AS ow
	LEFT JOIN `creatures_db` AS db ON ow.creatureID = db.creatureID
	LEFT JOIN creatures_families AS fa ON db.familyID = fa.familyID
	WHERE	ow.ID = '{$_GET['id']}'
	LIMIT 1")->fetch_assoc();

if(!$creature || ($creature['userID'] != $user->userID))
	redirect_to_index();

$creature['ID'] = $_GET['id'];

$item_obj = new item($_GET['item']);

$page->s = array(1, '');
cfg::$db->autocommit(false);

//do what item does
switch($item_obj->concise){
	case 'cryogenic_freeze_spray':
		if(!$creature['frozen']){
			if(isset($_GET['confirm'])){
				cfg::$db->query("
				UPDATE creatures_owned SET frozen = '1'
				WHERE ID = '{$creature['ID']}' LIMIT 1");
				$item_obj->take(1, $user->userID);
				$page->s = array(3, "<p class='allow'>You have succesfully frozen <span class='b'>{$creature['nickname']}</span> using the {$item_obj->name}!</p>");
			}
		}
		else $page->s = array(0, "<p class='deny'><span class='b'>{$creature['nickname']}</span> is already frozen!</p>");
		break;

	case 'female_gen_x':
		if(gender($creature['gender']) == 1)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is already female!</p>";
		
		if($creature['gender_only'] == 0)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is only allowed to be male.</p>";

		if(isset($_GET['confirm']) && empty($msgs)){
			cfg::$db->query("UPDATE creatures_owned SET gender = '1'
							WHERE ID = '{$creature['ID']}' LIMIT 1");
			$item_obj->take(1, $user->userID);
			$page->s = array(3, "<p class='allow'>You have succesfully changed <span class='b'>{$creature['nickname']}</span>'s gender to female using the {$item_obj->name}!</p>");
		}
		break;

	case 'male_gen_x':
		if(gender($creature['gender']) == 0)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is already male!</p>";
			
		if($creature['gender_only'] == 1)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is only allowed to be female.</p>";

		if(isset($_GET['confirm']) && empty($msgs)){
			cfg::$db->query("UPDATE creatures_owned SET gender = '0'
							WHERE ID = '{$creature['ID']}' LIMIT 1");
			$item_obj->take(1, $user->userID);
			$page->s = array(3, "<p class='allow'>You have succesfully changed <span class='b'>{$creature['nickname']}</span>'s gender to male using the {$item_obj->name}!</p>");
		}
		break;

	case 'defrosting_torch':
		if($creature['frozen']){
			if(isset($_GET['confirm'])){
				cfg::$db->query("
				UPDATE creatures_owned SET frozen = '0'
				WHERE ID = '{$creature['ID']}'
				LIMIT 1");
				$item_obj->take(1, $user->userID);
				$page->s = array(3, "<p class='allow'>You have succesfully unfrozen  <span class='b'>{$creature['nickname']}</span> with the {$item_obj->name}!</p>");
			}
		}
		else $page->s = array(0, "<p class='deny'><span class='b'>{$creature['nickname']}</span> is already unfrozen!</p>");
		break;

	case 'elixir_of_nobility':
		if($creature['deny_ne'])
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> isn't allowed to have nobles or exalteds.</p>";

		if($creature['frozen'])
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is frozen.</p>";

		if($creature['care'] < 500)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> doesn't have enough carepoints!</p>";
		
		if($creature['speciality'] != 0
			&& $creature['speciality'] != 2)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> must be an exalted or normal.</p>";

		//fetch last stage x of family
		$last_stage_in_family = cfg::$db->query("
		SELECT MAX(stage) AS final_stage FROM creatures_db
		WHERE familyID = '{$creature['familyID']}'")
									->fetch_assoc();

		if($creature['stage'] != $last_stage_in_family['final_stage']){
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> must be at the final stage in the family to be nobilized.</p>";
		}

		if(isset($_GET['confirm']) && empty($msgs)){
			//find the first stage ID
			$egg_id = cfg::$db->query("
			SELECT creatureID FROM creatures_db
			WHERE familyID = '{$creature['familyID']}'
			AND stage = '1' LIMIT 1")->fetch_assoc();

			cfg::$db->query("
			UPDATE creatures_owned
			SET speciality = '1', creatureID = '{$egg_id['creatureID']}',
				care = '0' ".($creature['nickname'] === $creature['creature_name'] ? ", nickname = 'Egg'" : "")."
			WHERE ID = '{$creature['ID']}'
			LIMIT 1");

			$item_obj->take(1, $user->userID);
			$page->s = array(3, "<p class='allow'><span class='b'>{$creature['nickname']}</span> drinks the {$item_obj->name} and is reborn as a noble!</p>");
		}
		break;

	case 'normalize_potion':
		if($creature['frozen'])
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is frozen.</p>";
		
		if($creature['speciality'] != 1
			&& $creature['speciality'] != 2)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> must be a noble or exalted.</p>";

		if(isset($_GET['confirm']) && empty($msgs)){
			//find the first stage ID
			$egg_id = cfg::$db->query("
			SELECT creatureID FROM creatures_db
			WHERE familyID = '{$creature['familyID']}'
			AND stage = '1' LIMIT 1")->fetch_assoc();

			cfg::$db->query("
			UPDATE creatures_owned
			SET speciality = '0', creatureID = '{$egg_id['creatureID']}'"
			.($creature['nickname'] === $creature['creature_name'] ? ", nickname = 'Egg'" : "")."
			WHERE ID = '{$creature['ID']}'
			LIMIT 1");
			$item_obj->take(1, $user->userID);
			$page->s = array(3, "<p class='allow'><span class='b'>{$creature['nickname']}</span> drinks the {$item_obj->name} and is reborn as a normal!</p>");
		}
		break;

	case 'elixir_of_exaltation':
		if($creature['deny_ne'])
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> isn't allowed to have nobles or exalteds.</p>";

		if($creature['frozen'])
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is frozen.</p>";

		if($creature['care'] < 1000)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> doesn't have enough carepoints!</p>";
		
		if($creature['speciality'] != 1)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> must be a noble.</p>";

		//fetch last stage x of family
		$last_stage_in_family = cfg::$db->query("
		SELECT MAX(stage) AS final_stage FROM creatures_db
		WHERE familyID = '{$creature['familyID']}'")->fetch_assoc();

		if($creature['stage'] != $last_stage_in_family['final_stage']){
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> must be at the final stage in the family to be exalted.</p>";
		}

		if(isset($_GET['confirm']) && empty($msgs)){
			//find the first stage ID
			$egg_id = cfg::$db->query("
			SELECT creatureID FROM creatures_db
			WHERE familyID = '{$creature['familyID']}'
			AND stage = '1' LIMIT 1")->fetch_assoc();

			cfg::$db->query("
			UPDATE creatures_owned
			SET speciality = '2', creatureID = '{$egg_id['creatureID']}',
				care = '0' ".($creature['nickname'] === $creature['creature_name'] ? ", nickname = 'Egg'" : "")."
			WHERE ID = '{$creature['ID']}'
			LIMIT 1");
			$item_obj->take(1, $user->userID);
			$page->s = array(3, "<p class='allow'><span class='b'>{$creature['nickname']}</span> drinks the {$item_obj->name} and is reborn as an exalted!</p>");
		}
		break;

	case 'time_warp_watch':
		if(!$creature['frozen'] && $creature['stage'] > 1){
			if(isset($_GET['confirm'])){
				//find the creatureID before it
				$prev_id = cfg::$db->query("SELECT creatureID FROM creatures_db
											WHERE familyID = '{$creature['familyID']}'
											AND stage = '".($creature['stage'] -1)."'
											LIMIT 1")->fetch_assoc();

				cfg::$db->query("UPDATE creatures_owned SET creatureID = '{$prev_id['creatureID']}'
								WHERE ID = '{$creature['ID']}'
								LIMIT 1");
				$item_obj->take(1, $user->userID);
				$page->s = array(3, "<p class='allow'>You have succesfully unfrozen  <span class='b'>{$creature['nickname']}</span> with the {$item_obj->name}!</p>");
			}
		}
		else $page->s = array(0, "<p class='deny'><span class='b'>{$creature['nickname']}</span> does not meet the requirements for time warp watch use.</p>");
		break;
}

cfg::$db->autocommit(true);

if(!empty($msgs))
	$page->s = array(0, implode('<br />', $msgs));


require $template .$pagename;
?>