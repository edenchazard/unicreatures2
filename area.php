<?php
require './inc/head.php';

$profile_user = make_user_from_query_string();

//invalid
if(!$profile_user)
	redirect_to_index();

env::set('profile_user_obj', $profile_user);

prepend_filter('page_title', function($title){ return "Viewing area"; });

$_GET = escape($_GET);

if(isset($_GET['area'])){
	if(!safe_digit($_GET['area'])){
		redirect_to_index();
	}

	//select from areas
	$area = cfg::$db->query("
		SELECT	`userID`,
				`name`,
				`holding`,
				description,
				sort_method
		FROM `user_owned_plots`
		WHERE `areaID` = '{$_GET['area']}'
		LIMIT 1")->fetch_assoc();

	//area doesn't exist
	if(!$area)
		redirect_to_index();

	$area['area'] = $_GET['area'];

	$where = " WHERE ow.`areaID` = '".$area['area']."'";
}
else if(isset($_GET['wild'])){
	$where = " WHERE ow.userID = '{$profile_user->userID}' AND ow.`areaID` = 0";
	$area = array(	'userID' 		=> $profile_user->userID,
					'name'			=> 'Wild',
					'holding'		=> '100',
					'description' 	=> '',
					'area'			=> 0,
					'sort_method'	=> $profile_user->wild_sort
				);
}
else{
	redirect_to_index();
}

if($area['sort_method'] == 1){
	$sql = "SELECT ow.`ID`,  ow.`creatureID`, ow.`speciality`, ow.`variety`, ow.`nickname`, ow.`frozen`
	FROM `creatures_owned` AS ow
	LEFT JOIN creatures_db AS db ON ow.creatureID = db.creatureID
	$where
	ORDER BY db.familyID, db.stage, ow.speciality";
}
elseif($area['sort_method'] == 2){
	$sql = "SELECT ow.`ID`,  ow.`creatureID`, ow.`speciality`, ow.`variety`, ow.`nickname`, ow.`frozen`
	FROM `creatures_owned` AS ow
	LEFT JOIN creatures_db AS db ON ow.creatureID = db.creatureID
	LEFT JOIN creatures_families AS fa ON db.familyID = fa.familyID
	$where
	ORDER BY fa.release_day";
}
elseif($area['sort_method'] == 3){
	$sql = "SELECT `ID`,  `creatureID`, `speciality`, `variety`, `nickname`, `frozen`
	FROM `creatures_owned` AS ow
	$where
	ORDER BY ow.`custom_sort`, ow.`ID` DESC";
}
//default
else{
	$sql = "SELECT `ID`,  `creatureID`, `speciality`, `variety`, `nickname`, `frozen`
	FROM `creatures_owned` AS ow
	$where
	ORDER BY ow.`ID` DESC";
}
$creatures = cfg::$db->query($sql);

$area['description'] = esc_html($area['description']);

require $template .$pagename;
?>