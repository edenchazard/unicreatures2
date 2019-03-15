<?php
//file that saves an item to a group
//will be called by ajax

//make sure appropriate variables are set
if(!isset($_POST['id'], $_POST['group'])){
	//no
	exit;
}

//start session
session_start();

//verification and sanitation
//not numeric
if(!is_numeric($_POST['id']))
	exit;
	
if(!is_numeric($_POST['group']))
	exit;
	
require("db_conn.php");
	
//check group, id belong to session
$_POST['id'] = intval($_POST['id']);
$_POST['group'] = intval($_POST['group']);
$_SESSION['sess_id'] = mysqli_real_escape_string($link, $_SESSION['sess_id']);

/*if(!mysqli_fetch_assoc(mysqli_query($link, "SELECT id FROM `groups` WHERE id='{$_POST['id']}' AND belongs_to='{$_SESSION['session']}' LIMIT 1")))
	exit; //no*/

//do query to save item to group
mysqli_query($link, "UPDATE sorting_items SET `group`='{$_POST['group']}' WHERE id='{$_POST['id']}' AND session='{$_SESSION['sess_id']}' LIMIT 1");
?>