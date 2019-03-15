<?php
require './inc/head.php';

if(!is_logged_in())
	redirect_to_index();

$alerts = cfg::$db->query("
	SELECT	message,
			title,
			colour,
			`when`
	FROM `notifications`
	LEFT JOIN notifications_types
	ON notifications.typeID = notifications_types.typeID
	WHERE notifications.userID ='{$user->userID}'
	ORDER BY `noteID` DESC
	LIMIT 20");

require $template .$pagename;
?>