<?php
require ROOT.'/inc/head.php';

if(isset($_POST['username'])){
	try{
		$success = $user->change_username($_POST['username']);
		$page->s = array(0, "<p class='center allow'>".$success."</p>");
	}
	catch(sql_error $e){
		$page->s = array(0, cfg::$db->eError());
	}
}

require $template .$pagename;
?>