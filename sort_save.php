<?php
//file that saves positions of an array of items
//will be called by ajax

require '/inc/head.php';

//make sure appropriate variables are set
if(!isset($_POST['item'])){
	exit();
}
$_POST = escape($_POST);

$i = 0;
//we need to do an update query for each. :c
//$_POST['item'] = // array_reverse($_POST['item'], true);
foreach($_POST['item'] as $item){
	++$i;
	cfg::$db->query("UPDATE creatures_owned SET custom_sort  = '{$i}' WHERE ID = '{$item}' AND userID = {$user->userID} LIMIT 1");
}

// if(!mysqli_error($link)){
	// echo "Saved sorting for active page.";
// }
?>