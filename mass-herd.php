<?php
require './inc/head.php';

if(!isset($_GET['id']) || !is_logged_in()) redirect_to_index();
if(!safe_digit($_GET['id'])) redirect_to_index();

//check area exists
//remember, 0 = wild
if($_GET['id'] != 0){
	//select from areas
	$area = cfg::$db->query("SELECT `name`, `holding`, description, sort_method
							FROM `user_owned_plots`
							WHERE `areaID` = {$_GET['id']} LIMIT 1")
					->fetch_assoc();

	//didn't find area
	if(!$area) redirect_to_index();

	$area['areaID'] = $_GET['id'];
}
else{
	$area = array(	'userID' 		=> $user->userID,
					'name'			=> 'Wild',
					'holding'		=> '100',
					'description' 	=> '',
					'areaID'		=> 0,
					'sort_method'	=> $user->wild_sort
				);
}

$sql = "SELECT `ID`,  `creatureID`, `speciality`, `variety`, `nickname`, `frozen`
FROM `creatures_owned` AS ow
LEFT JOIN creatures_db as db USING(creatureID)
LEFT JOIN creatures_families as fa ON db.familyID = fa.familyID
WHERE ow.userID = {$user->userID} AND ow.areaID = {$_GET['id']}
AND ow.frozen = 0 AND db.stage > 1 AND ow.speciality = 0
AND fa.rarity < 10";

$creatures = cfg::$db->query($sql);

$areas = cfg::$db->query("
	SELECT `areaID`, `name`, `holding`
	FROM `user_owned_plots` WHERE `userID` = '{$user->userID}'");

//now we can fetch everything in that group
//as well as all of their group titles

//html for groups
//$html_groups = '';
//$result = mysqli_query($link, "SELECT id, items FROM `groups` WHERE session='{$_SESSION['sess_id']}'");

/*while($row = mysqli_fetch_assoc($result)){
	if($row['id'] != $group_id) //isn't the active tab
		$html_groups .= "<div id='group-{$row['id']}' class='group inactive-group'><a href='?group={$row['id']}'>Group {$row['id']}</a> ({$row['items']})</div>";
	else { //active tab
			$html_groups .= "<div id='group-{$row['id']}' class='group active-group'><strong>Group {$row['id']}</strong> ({$row['items']})</div>";
	}
}*/

//html for items
/*$html_items = '';
$result = mysqli_query($link, "SELECT id FROM sorting_items WHERE `group`='{$group_id}' AND session='{$_SESSION['sess_id']}' ORDER BY sort_order ASC LIMIT 15");

while($row = mysqli_fetch_assoc($result)){
	$html_items .= "<div class='sortable' id='item-{$row['id']}'>item {$row['id']}</div>";
}*/
require $template .$pagename;
?>