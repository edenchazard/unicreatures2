<?php
$fancy = function($herd){
	return 	"<a href='/herd.php?id={$herd['herdID']}'><img src='".mkimg($herd)."' /></a>
			 <br />
			 {$herd['herd_name']}";
};
?>




<? require $template .'/header.php'; ?>
<? get_profile_navigation() ?>
					<div class='center'>
						<table class='table-creatures'>
							<tbody>
<?=draw_fancy_table_rows($herds, 4, $fancy) ?>
							</tbody>
						</table>
					</div>
<? require $template .'/footer.php'; ?>