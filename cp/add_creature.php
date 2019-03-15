<?php
require ROOT.'/inc/head.php';

//family listing
$families_list = cfg::$db->query("SELECT family_name FROM creatures_families");

//holy shit loads of checks
if(isset(	$_POST['name'], $_POST['stage'], $_POST['family'],
			$_POST['care'], $_POST['component'], $_POST['visual_description'],
			$_POST['lifestyle'], $_POST['strength'], $_POST['intelligence'],
			$_POST['charisma'], $_POST['agility'], $_POST['wisdom'],
			$_POST['willpower'], $_POST['speed'], $_POST['creativity'],
			$_POST['focus'], $_POST['training_action'], $_POST['training_cost'],
			$_POST['training_reward'], $_FILES['upload'])){

	//validation
	$errors = array();

#todo: rarity

	//check things
	if(!$f = cfg::$db->query("SELECT 1 FROM creatures_families WHERE family_name='{$_POST['family']}' LIMIT 1"))
		$errors[] = "That family does not exist.";

	if(!safe_digit($_POST['care']))
		$errors[] = "Creature care needs to be a positive integer.";

	if(!safe_digit($_POST['stage']))
		$errors[] = "Creature stage needs to be a positive integer.";

	if(!safe_digit($_POST['strength'], $_POST['intelligence'],
					$_POST['charisma'], $_POST['agility'], $_POST['wisdom'],
					$_POST['willpower'], $_POST['speed'], $_POST['creativity'],
					$_POST['focus']))
		$errors[] = "All creature stats need to be a positive integer.";

	if(strlen($_POST['visual_description']) < 10)
		$errors[] = "Visual description must have ten or more characters.";
	

	$comps = get_sys_components();
	if($_POST['component'] === 'random'){
		//pick a random component
		$_POST['component'] = $comps[array_rand($comps, 1)];
	}
	else{
		if(!in_array($_POST['component'], $comps)){
			$errors[] = "Invalid component chosen. If you have just added this component,
						you should flush the APC cache.";
		}
	}

	//check file
	$temp = explode(".", $_FILES['upload']['name']);
	$extension = end($temp);

	if(!in_array($_FILES['upload']['type'],
				array(	'image/jpeg', 'image/png', 'image/gif' )
				)
		|| $_FILES['upload']['size'] >= 122880
		|| $extension !== 'png'
		)
		$errors[] = "Files must be gif/png/jpg media type, 120kB or less and end with .png";

	if(empty($errors)){
		try{
			//escape $_POST
			$escaped = escape($_POST);

			cfg::$db->autocommit(false);
			//insert datas
			cfg::$db->query("
			INSERT INTO creatures_db	(family_name, creature_name, stage, visual_description, lifestyle, required_clicks, component)
			VALUES				('{$escaped['family']}', '{$escaped['name']}', {$escaped['stage']},
								'{$escaped['visual_description']}', '{$escaped['lifestyle']}',
								{$escaped['care']}, '{$escaped['component']}'
								)");

			$creatureID = cfg::$db->insert_id;

			//we only want to insert max skills if this isn't an egg
			if($escaped['stage'] > 1){
				cfg::$db->query("
				INSERT INTO training_max_skills	(creatureID, strength, agility, speed, intelligence, wisdom, charisma, creativity, willpower, focus)
				VALUES					($creatureID, {$escaped['strength']}, {$escaped['agility']},
										{$escaped['speed']}, {$escaped['intelligence']},
										{$escaped['wisdom']}, {$escaped['charisma']},
										{$escaped['creativity']}, {$escaped['willpower']},
										{$escaped['focus']}
										)
				");
			}
	
			//insert the training options
			$data = array();
			foreach($escaped['training_action'] as $key => $value){
				if(!empty($escaped['training_action'][$key]) && !empty($escaped['training_cost'][$key])
					&& !empty($escaped['training_reward'][$key])){
					
					$stuff[] = array(		$creatureID, $escaped['training_action'][$key],
										'',	$escaped['training_cost'][$key],
										$escaped['training_reward'][$key]
									);
				}
			}

			batch_insert(	'training_options',
							array(	'creatureID', 'title', 'text',
									'energy', 'reward'
								),
							$data
						);

			//move upload appropriately	
			$location = $_FILES['upload']['tmp_name'];
			if(!move_uploaded_file($location, "C:/wamp/www/images/creatures/$creatureID.png"))
				$errors[] = "Problem moving file.";
		}
		catch(sql_error $e){
			$errors[]= "Sorry, a database error has occured. Please check you have entered
					    all information correctly. The creature was not entered.";
		}
	}

	//creature adding was 100% successful
	if(empty($errors)){
		//so commit inserts
		cfg::$db->commit();
		$page->s = array(1, "<p class='center allow'>The ceature has been added to the system.</p>");
	}
	else{
		cfg::$db->rollback();
		$errors[] = "The creature has not be entered into the system.";
		$page->s = array(0, "<p class='center deny'>".implode('<br />', $errors).'</p>');
	}

	cfg::$db->autocommit(true);
}

require $template .$pagename;
?>