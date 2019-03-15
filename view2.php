<?php
if(!isset($_GET['id'])) redirect_to_index();

require './inc/head.php';

$page->s = array(0, "");

if(!safe_digit($_GET['id'])) redirect_to_index();

//food selection depends upon when last clicked by IP
$ip = escape($_SERVER['REMOTE_ADDR']);

$creature = cfg::$db->query("
	SELECT ow.`creatureID`, ow.`userID`, ow.`care`, ow.`speciality`, ow.`nickname`, ow.`gender`, ow.`variety`, ow.`frozen`,
		db.`visual_description`, db.`lifestyle`, fa.`family_name`, db.`creature_name`, db.`stage`, db.required_clicks,
		fa.rarity, fa.type, fa.deny_ne, users.username AS owner, `text` AS story, cl.clicked_at
	FROM `creatures_owned` AS ow
	LEFT JOIN `creatures_db` AS db ON ow.creatureID = db.creatureID
	LEFT JOIN `creatures_families` AS fa ON db.familyID = fa.familyID
	LEFT JOIN users ON ow.userID = users.userID
	LEFT JOIN creatures_user_stories AS stories ON ow.ID = stories.ID
	LEFT JOIN creatures_owned_clicks as cl ON (ow.ID = cl.ID
								AND cl.`IP` = '{$ip}'
								AND cl.`clicked_at` >= '".date("Y-m-d H:i:s", time()-(60*60*16))."')
	WHERE ow.`ID` = '{$_GET['id']}'
	LIMIT 1
")->fetch_assoc();

//didn't find creature
if(!$creature) redirect_to_index();

$tpl->set(array('has_skills' => false));

$creature['ID'] = $_GET['id'];

//fetch artist contributions
if(!empty($creature['variety'])){
	$art = cfg::$db->query("SELECT artist
								FROM creatures_db_credits
								WHERE creatureID = {$creature['creatureID']}
								AND speciality = ''
								AND variety = '{$creature['variety']}' LIMIT 1")
				->fetch_assoc();
}
else if(isbetween($creature['speciality'], 1, 2)){
	$art = cfg::$db->query("SELECT artist
								FROM creatures_db_credits
								WHERE creatureID = {$creature['creatureID']}
								AND speciality = '{$creature['speciality']}'
								AND variety = '' LIMIT 1")
				->fetch_assoc();
}
else{
	$art = cfg::$db->query("SELECT artist
								FROM creatures_db_credits
								WHERE creatureID = {$creature['creatureID']}
								AND speciality = ''
								AND variety = '' LIMIT 1")
				->fetch_assoc();
}
if(empty($art['artist']))
	$creature['artist'] = 'Unknown';
else
	$creature['artist'] = $art['artist'];

//get queries and computations out of the way
if($creature['stage'] > 1){
	$skills = cfg::$db->query("SELECT ".implode(', ', cfg::$general_skills).", powers
							FROM `creatures_owned_training`
							WHERE ID = '{$creature['ID']}' LIMIT 1")
					->fetch_assoc();

	//only need to fetch these if there's skills
	if($skills && $creature['stage'] > 1){
		$skills['total'] = array_sum($skills);

		//max skills
		$skill_limits = get_max_skills($creature['creatureID'], $creature['speciality']);

		//fetch powers
		if($skills['powers'] > 0){
			$tpl->set(array('powers' => cfg::$db->query("
				SELECT	skill,
						level
				FROM creatures_owned_training_powers
				WHERE ID = '{$creature['ID']}'")
			));
			//add power increase
			$skills['total'] += calc_powers($skills['powers']);
		}
		$tpl->set(array('has_skills' => true, 'skills' => $skills));
	}
}

if(!$creature['frozen']){
	$tpl->set(array('clicked' => false));

	//not yet clicked by ip
	if(!$creature['clicked_at']){

		if(isset($_POST['care'])){
			$points_to_add = 1;
			cfg::$db->autocommit(false);
			//insert record
			$result = cfg::$db->query("INSERT INTO `creatures_owned_clicks`
										(`ID`, `IP`, `clicked_at`)
										VALUES
										('{$creature['ID']}', '$ip', '".date("Y-m-d H:i:s")."')"
			);

			//add carepoint
			cfg::$db->query("UPDATE `creatures_owned` SET `care`=`care`+1
							WHERE `ID` = '{$creature['ID']}' LIMIT 1");

			$creature['care'] += 1;
			$tpl->response_msg = "<p class='allow'>Thank you for caring for
								{$creature['owner']}'s
								{$creature['creature_name']}!</p>";

			//chance of orb/shard
			//don't have any chance clicking your own pets
			if($creature['userID'] != $user->userID){
				if(mt_rand(0, 1) === 1){
					$orbs_and_shards = (get_sys_components('Orb') + get_sys_components('Shard'));
					$forme = $orbs_and_shards[array_rand($orbs_and_shards)];
					$forthem =  $orbs_and_shards[array_rand($orbs_and_shards)];
					$forme_obj =  new something('component', $forme['name']);
					$forme_obj->give(1, $user->userID);

					$forthem_obj = new something('component', $forthem['name']);
					$forthem_obj->give(1, $creature['userID']);
					$tpl->response_msg = "<p class='allow'>Thank you for caring for
									{$creature['owner']}'s
									{$creature['creature_name']}!</p>
									<p class='allow'>You have gained +1 <img src='{$forme_obj->image()}' />
									{$forme_obj->name}!</p>
									<p class='allow'>{$creature['owner']} has gained +1
									<img src='{$forthem_obj->image()}' /> {$forthem_obj->name}!</p>";
				}
			}
			cfg::$db->autocommit(true);
			$tpl->set(array('clicked' => true));
		}
		else{
			$tpl->response_msg = "<p class='warning'>Click an option above to help this creature!</p>";
		}
	}
	else {
		$tpl->set(array('clicked' => true));
	}
}

//components
if(is_logged_in() && ($page->s[0] <= 1)){
	$components = seed_components($creature['creatureID']);
	$page->s[0] = 4;

	//feeding components to a creature
	if(isset($_GET['feed'])){
		$amount = 1;

		if(isset($_GET['q'])){
			//stop people cheating by using negative numbers
			//and validates
			if(safe_digit($_GET['q'])){
				$amount = $_GET['q'];
			}
		}

		//what we're feeding
		if(!isset($components[$_GET['feed']]))
			$tpl->response_msg = "<p class='center deny'>Value can only be 0-3, damn it!</p>";
		else{
			//component we're feeding
			$component = new something('component', $components[$_GET['feed']]);

			//check user has enough to feed x amount
			if($user->inventory($component) >= $amount){
				cfg::$db->autocommit(false);

				//add carepoints
				cfg::$db->query("UPDATE `creatures_owned` SET `care`=`care`+$amount WHERE `ID`='{$creature['ID']}' LIMIT 1");
				$component->take($amount, $user->userID);

				cfg::$db->autocommit(true);
				$user->inventory();
				$creature['care'] += $amount;
			}
		}
	}
}

//nobles are +1 rarity, exalts are +2 rarity
if(isbetween($creature['speciality'], 1, 2)){
	$creature['rarity'] += $creature['speciality'];
}

$creature['story'] = esc_html($creature['story']);
$creature['lifestyle'] = parse_template($creature, $creature['lifestyle']);
$creature['needs'] = ($creature['required_clicks'] - $creature['care']);

$tpl->set(
	array(
		'title'					=> "Viewing {$creature['nickname']}",
		'stopwatch'				=> $stopwatch,
		'can_evolve'			=> (($creature['required_clicks'] - $creature['care']) > 0 ? true : false),
		'user'					=> $user,
		'owner' 				=> $creature['owner'],
		'id'					=> $creature['ID'],
		'isfrozen' 				=> $creature['frozen'],
		'nickname'				=> $creature['nickname'],
		'image'					=> mkimg($creature),
		'type' 					=> ucfirst($creature['type']),
		'creature_name' 		=> ucfirst($creature['creature_name']),
		'family'				=> ucfirst($creature['family_name']),
		'rarity'				=> ucfirst(determine_rarity($creature['rarity'])),
		'has_speciality'		=> ($creature['speciality'] > 0),
		'speciality'			=> $creature['speciality'],
		'gender'				=> ucfirst(gender2text($creature['gender'])),
		'has_story'				=> (!empty($creature['story'])),
		'story'					=> esc_html($creature['story']),
		'needs_to_evolve'		=> ($creature['required_clicks'] - $creature['care']),
		'visual_description'	=> $creature['visual_description'],
		'lifestyle'				=> $creature['lifestyle'],
		'user_is_owner'			=> ($creature['userID'] == $user->userID),
		'can_freeze'			=> ($user->inventory('cryogenic_freeze_spray') && !$creature['frozen']),
		'can_defrost'			=> ($user->inventory('defrosting_torch') && $creature['frozen']),
		'can_fem_gen_x'			=> ($user->inventory('female_gen_x') && $creature['gender'] === 'male' && !$creature['frozen']),
		'can_male_gen_x'		=> ($user->inventory('male_gen_x') && $creature['gender'] === 'female' && !$creature['frozen']),
		'artist_name'			=> $creature['artist'],
		'carepoints'			=> $creature['care'],
		'can_noble'				=> ($user->inventory('elixir_of_nobility') && !$creature['deny_ne'] && !$creature['frozen']
									&& $creature['care'] >= 500 && ($creature['speciality'] == 0 || $creature['speciality'] == 2)),
		'can_exalt'				=> ($user->inventory('elixir_of_exaltation') && !$creature['deny_ne'] && !$creature['frozen']
									&& $creature['care'] >= 1000 && $creature['speciality'] == 1),
		'can_normalize'			=> ($user->inventory('normalize_potion') && !$creature['frozen']
									&& ($creature['speciality'] == 1 || $creature['speciality'] == 2)),
		'can_devolve'			=> ($user->inventory('time_warp_watch') && $creature['stage'] > 1 && !$creature['frozen']),
	)
);

$tpl->render('view2');
?>