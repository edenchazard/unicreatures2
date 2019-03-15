<?php
require './inc/head.php';

if(!isset($_GET['id'])) redirect_to_index();
if(!safe_digit($_GET['id'])) redirect_to_index();

//fetch the herd
$herd = cfg::$db->query("SELECT herd_name, show_names FROM user_herds
					WHERE herdID = {$_GET['id']}
					LIMIT 1")
			->fetch_assoc();
//found herd?
if(!$herd) redirect_to_index();

$herd['ID'] = $_GET['id'];

//fetch all the creatures inside it
$creatures = cfg::$db->query("SELECT h.creatureID, h.number, h.variety,
							db.creature_name, db.stage
						FROM user_herds_data AS h
						LEFT JOIN creatures_db AS db ON h.creatureID = db.creatureID
						WHERE h.herdID = {$herd['ID']}");

//it's easier and probably quicker just to let mysql do this
$herd_stats = cfg::$db->query("SELECT h.creatureID, h.number,
									SUM(number) AS total,
									db.creature_name, db.stage 
								FROM user_herds_data AS h
								LEFT JOIN creatures_db AS db USING(creatureID)
								WHERE h.herdID = {$herd['ID']}
								GROUP BY h.creatureID
								ORDER BY NULL");
require $template .$pagename;
?>