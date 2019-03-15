<?php
require('/inc/head.php');

if(is_logged_in()){
	$user->logout();
	$page->title = "Logout";
	$page->html .= "Thanks, you've successfully logged out.";
}
else{
	redirect_to_index();
}

require('/inc/foot.php');
?>