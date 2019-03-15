<?php	require $template .'/header.php' ?>
<?php	add_filter('profile_title', function($v) use ($area){ return "<h1>".get_profile_username()."'s Profile - {$area['name']}</h1>"; }) ?>
<?php	get_profile_navigation() ?>
<?php	$fancy = function($creature){
					return draw_creature_tile($creature, "/view.php?id={$creature['ID']}");
		} ?>
					<p id='area-description'><?php _e($area['description']) ?></p>
					<table class='table-creatures'>
						<tbody>
<?php	_e(draw_fancy_table_rows($creatures, 8, $fancy)) ?>
						</tbody>
					</table>
<?php	if($area['userID'] == $user->userID){ ?>
					<div class='center'>
						<a href='/sort.php?area=<?php _e($area['area']) ?>'>Re-organize this area</a>
					</div>
<?php	} ?>
<?php	require $template .'/footer.php'; ?>