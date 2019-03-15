<?php
require './inc/head.php';
cfg::$db->query("INSERT INTO exotic_credits (passphrase, bought_by_userID, sent_to_userID)
				VALUES ('".hash('sha256', mt_rand(0, 100000).$user->email)."', 1, {$user->userID})");
if(is_logged_in()){
	$credits = cfg::$db->query("SELECT COUNT(*) AS num FROM exotic_credits
								WHERE sent_to_userID = {$user->userID} AND spent_id = 0")
						->fetch_assoc();
}

$curdate = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j'), 1970));

$user->exotic_credits = $credits['num'];

$available_creatures = cfg::$db->query("
SELECT fa.`familyID`, fa.`family_name`, db.creatureID, DATEDIFF(fa.dend, '{$curdate}') AS rem_days,
fa.every_year_until
FROM creatures_families AS fa
LEFT JOIN creatures_db AS db USING(familyID)
WHERE ".date('Y')." <= fa.every_year_until
AND (('{$curdate}' BETWEEN fa.dbegin AND fa.dend) OR fa.dbegin = fa.dend) 
AND unique_rating > 0 AND db.stage = 1");

require $template .$pagename;
?>