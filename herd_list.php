<?php
require './inc/head.php';

add_filter('profile_title', function($v){ return "<h1>".get_profile_username()."'s Herds</h1>"; });

$profile_user = make_user_from_query_string();

//invalid
if(!$profile_user)
	redirect_to_index();

env::set('profile_user_obj', $profile_user);

$herds = cfg::$db->query("SELECT h.`herdID`, h.`herd_name`, MAX(db.creatureID) AS creatureID
							FROM `user_herds` AS h
							LEFT JOIN creatures_db AS db USING(familyID)
							WHERE h.`userID`= {$user->userID}
							GROUP BY h.familyID
							ORDER BY NULL");

require $template .$pagename;
?>