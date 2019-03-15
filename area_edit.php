<?php
require './inc/head.php';
if(!is_logged_in() || !isset($_GET['id']))
	redirect_to_index();

if(!safe_digit($_GET['id']))
	redirect_to_index();

//select plot
$plot = cfg::$db->query(	"SELECT userID, user_owned_plots.plotID,  user_owned_plots.name, description, stage,
						plot_stages.plotTypeID, plots.name AS plot_name
						FROM user_owned_plots
						LEFT JOIN plot_stages ON user_owned_plots.plotID = plot_stages.plotID
						LEFT JOIN plots ON plot_stages.plotTypeID = plots.plotTypeID
						WHERE areaID = {$_GET['id']} LIMIT 1")
				->fetch_assoc();

if($plot['userID'] != $user->userID)
	redirect_to_index();

$page->s = array(0, "<p class='center purple'>You are currently editing the land plot \"{$plot['name']}\"</p>");

//select next stage for this plot
$next_stage = cfg::$db->query("SELECT plotID, stage FROM plot_stages
							WHERE stage = {$plot['stage']} + 1
							AND plotTypeID = {$plot['plotTypeID']}
							LIMIT 1")
					->fetch_assoc();

//found next stage, so fetch requirements
if($next_stage){
	$requirements = array();

	$res = cfg::$db->query("
	SELECT req.amount, req.res_id, req.res_type,
		COALESCE(comp.name, item.name, db.creature_name,
			IF(req.res_type = 'Coins', 'Coins', '')
		) AS produces
	FROM plot_stages_requirements AS req
	LEFT JOIN components AS comp ON (req.res_id = comp.componentID AND req.res_type = 'component')
	LEFT JOIN items AS item ON (req.res_id = item.itemID AND req.res_type = 'item')
	LEFT JOIN creatures_db AS db ON (req.res_id = db.creatureID AND req.res_type = 'creature')
	WHERE req.plotID = {$next_stage['plotID']}");

	while($ingredient = $res->fetch_assoc()){
		$type = concise($ingredient['res_type']);
		$requirements[] = array(
								'obj' => new $type(($type == 'creature' ? $ingredient['res_id'] : $ingredient['produces'])),
								'amount' => $ingredient['amount']
								);
	}
	
}

if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'upgrade':
			$ok = true;
			foreach($requirements as $req){
				if($user->inventory($req['obj']) < $req['amount']){
					$ok = false;
					break;
				}
			}

			if($ok){
				try{
					cfg::$db->autocommit(false);

					foreach($requirements as $req){
						$req['obj']->take($req['amount'], $user->userID);
					}

					//upgrade plot
					cfg::$db->query("UPDATE user_owned_plots as p SET
									plotID = {$next_stage['plotID']}
									WHERE areaID = {$_GET['id']}
									LIMIT 1");
					cfg::$db->commit();

					//update local variable
					$user->inventory();
				}
				catch(sql_error $e){
					cfg::$db->rollback();
					$page->s = array(2, "<p class='center deny'>Sorry, an error
										has occured. You have not lost your items.</p>"
									);
				}
				cfg::$db->autocommit(true);
			}
			else{
				$page->s = array(3, "<p class='center deny'>You don't have enough
									to upgrade the land plot!</p>"
								);
			}
			break;
		case 'rename':
			
			break;
	}
}

require $template .$pagename;
?>