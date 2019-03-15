<?php	require $template .'/header.php'; ?>
<?php	add_filter('profile_title', function($v){ return "<h1>".get_profile_username()."'s Profile</h1>"; }) ?>
<?php	get_profile_navigation() ?>
<?php	$fancy = function($area){
			return "
					<a href='/area.php?id={$area['areaID']}'><img src='/images/world/".concise($area['plot_name'])."_{$area['stage']}.png' /></a>
					<br />
					{$area['name']} ({$area['holding']} / {$area['num_of_creatures']})";
		} ?>
					<table class='maxwidth'>
						<tbody>
								<td>
									<a href='/area.php?wild=<?php _e($profile_user->username) ?>'><img src='/images/world/wild.png' /></a>
									<br />
									Wild
								</td>
								<?php draw_fancy_table_rows($areas, 4, $fancy) ?>
						</tbody>
					</table>
<?php	require $template .'/footer.php'; ?>