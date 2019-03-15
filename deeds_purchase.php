<?php
require './inc/head.php';
if(!is_logged_in() || !isset($_GET['id']))
	redirect_to_index();

if(!safe_digit($_GET['id']))
	redirect_to_index();

//check plot exists
$plot = cfg::$db->query("SELECT name FROM plots WHERE plotTypeID = {$_GET['id']} LIMIT 1")
				->fetch_assoc();
$plot['plotTypeID'] = $_GET['id'];
if(!$plot)
	header("Location: deeds.php") and exit;

//give
try {
	cfg::$db->autocommit(false);

	//select first stage of plot
	$first_stage = cfg::$db->query("SELECT `plotID` FROM `plot_stages`
								WHERE plotTypeID = {$plot['plotTypeID']}
								AND stage = 1 LIMIT 1")
						->fetch_assoc();

	cfg::$db->query("
		INSERT INTO `user_owned_plots`
		(`userID`, `plotID`, `name`, `holding`) VALUES
		('{$user->userID}', '{$first_stage['plotID']}', '{$plot['name']}', 0)");

	cfg::$db->commit();
}
catch (sql_error $e){
	cfg::$db->rollback();
}
cfg::$db->autocommit(true);
require $template .$pagename;
?>