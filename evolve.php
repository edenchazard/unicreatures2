<?php
require './inc/head.php';

if(!isset($_GET['id']) || !is_logged_in())
	redirect_to_index();

if(!safe_digit($_GET['id']))
	redirect_to_index();

$creature = cfg::$db->query("
	SELECT	`creatureID`,
			`userID`,
			`care`,
			`speciality`,
			`nickname`,
			`gender`,
			`variety`,
			`frozen`
	FROM `creatures_owned`
	WHERE `ID` = '{$_GET['id']}'
	LIMIT 1
")->fetch_assoc();

//didn't find creature
if(!$creature)
	redirect_to_index();

$creature['ID'] = $_GET['id'];

//get creature specific stuff
$creature += cfg::$db->query("
	SELECT	db.familyID,
			fa.`family_name`,
			db.`creature_name`,
			db.`stage`,
			db.required_clicks,
			fa.rarity,
			fa.type
	FROM `creatures_db` AS db
	LEFT JOIN `creatures_families` AS fa ON db.familyID = fa.familyID
	WHERE db.`creatureID` = '{$creature['creatureID']}'
	LIMIT 1")->fetch_assoc();

//do other security checks + check if it can really evolve
if($creature['frozen']
	|| $user->userID != $creature['userID']
	|| $creature['care'] == 0
	|| $creature['care'] < $creature['required_clicks']
	){
	header("Location: /view.php?id={$creature['ID']}");
	exit;
}

$prev_creature_name = $creature['creature_name'];
//ok, we can evolve

//first, check if there's a next stage, if so, fetch it.

$new_stage = cfg::$db->query("
	SELECT	creatureID,
			creature_name,
			lifestyle
	FROM creatures_db
	WHERE familyID = '{$creature['familyID']}'
	AND stage = ".($creature['stage']+1)."
	" . //AND branchID=$branch
	"LIMIT 1")->fetch_assoc();

//if no next stage, then this is fully evolved.
if(!$new_stage)
	header("Location: /view.php?id={$creature['ID']}") and exit;

try{
	cfg::$db->autocommit(false);
	cfg::$db->query("
		UPDATE creatures_owned
		SET creatureID = {$new_stage['creatureID']}"
		.($creature['nickname'] == $creature['creature_name'] ?
		", nickname = '{$new_stage['creature_name']}' " : '') ."
		WHERE ID = {$creature['ID']}
		LIMIT 1");

		//fetch last stage x of family
		$last_stage_in_family = cfg::$db->query("SELECT MAX(stage) AS final_stage FROM creatures_db
											WHERE familyID = '{$creature['familyID']}'")
									->fetch_assoc();

		if(($creature['stage']+1) == $last_stage_in_family['final_stage']){
			//try to insert a new row, if it fails, it's probably because
			//the user already has fully evolved this family
			try{
				$record = cfg::$db->query("INSERT INTO creatures_owned_complete 
								(userID, familyID) VALUES
								('{$user->userID}', '{$creature['familyID']}')");

				if($record !== MYSQL_E_DUPE_KEY){
					//increase training energy
					cfg::$db->query("UPDATE users SET add_train_energy = add_train_energy + 1
									WHERE userID = {$user->userID} LIMIT 1");

					$page->s = array(1, "
						<p class='center allow'>Congratulations! You've fully
						evolved the {$creature['family_name']} family!
						Your training energy has increased, and you can now collect
						nobles and exalteds of this family!</p>");
				}
			}
			catch(sql_error $e){}
		}
		cfg::$db->commit();
}
catch (sql_error $se){
	cfg::$db->rollback();
	$page->s = array(0, "<p class='center deny'>Sorry, an error has occurred.</p>");
}

cfg::$db->autocommit(true);

$creature = array_merge($creature, $new_stage);

$creature['lifestyle'] = parse_template($creature, $creature['lifestyle']);

require $template .$pagename;
?>