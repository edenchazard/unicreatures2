<?php
//cleanup routine
require('/inc/head.php');

if(is_logged_in()){
	if($user->has_perm('CLEANUP')){
		$page->html .= "It is recommended to run this script at least once every day as it will eliminate unneeded records from the database and improve performance.";
		$page->html .= "<h1>Current time is:</h1>".date($env->date_format).'<hr /><h1>Actions done:</h1><hr />';

		$cur = time();

		$x = date($env->date_format, $cur-(16*60*60));

		//delete all click registers past 16 hours ago
		if($link->query("
			DELETE
			FROM `clicks`
			WHERE `clicked_at` < '$x'
		")){
			$page->html .= "Click registers: Deleted ".$link->affected_rows." records below $x <hr />";
			if($link->query("OPTIMIZE TABLE `clicks`")){
				$page->html .= "Click registers: Optimized table.<hr />";
			}
		}

		//delete all baskets we don't need
		//range of date
		$x = date($env->date_format);

		if($link->query("
			DELETE
			FROM `basket`
			WHERE `exists_until` < '$x'
		")){
			$page->html .= "Basket: Deleted ".$link->affected_rows." records below $x<hr />";

			//optimize table, lots of data was deleted
			if($link->query("OPTIMIZE TABLE `basket`")){
				$page->html .= "Basket: Optimized table.<hr />";
			}
		}
	}
	//not authorized to execute cleanup util
	else{
		redirect_to_index();
	}
}
//not logged in
else{
	redirect_to_index();
}
require('/inc/foot.php');
?>