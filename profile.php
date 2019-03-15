<?php
require './inc/head.php';
$profile_user = make_user_from_query_string();


if(!$profile_user)
	redirect_to_index();

env::set('profile_user_obj', $profile_user);

$areas = cfg::$db->query("
	SELECT 	u.`areaID`, u.`name`, u.`holding`, p.name AS plot_name, ps.stage, ps.num_of_creatures
	FROM `user_owned_plots` AS u
	INNER JOIN plot_stages AS ps USING(plotID)
	INNER JOIN plots AS p ON ps.plotTypeID = p.plotTypeID
	WHERE `userID`='{$profile_user->userID}'");

require $template .$pagename;
?>