<?php	require $template .'/header.php'; ?>
<?php	add_filter('profile_title', function($v){ return "<h1>".get_profile_username()."'s Accomplishments</h1>"; }) ?>
<?php	get_profile_navigation() ?>
<script>
$(document).ready(function(){
	uc_library.accomps();
});
</script>
					<div class='center'>
						<p class='purple'>You currently have <?php _e($profile_user->accomplishments) ?> accomplishments.</p>
						<p><a id='hide' href='#hide'>Hide/unhide families with entire set of accomplishments.</a></p>
						<table class='maxwidth'>
							<tbody>
								<tr>
									<th>&nbsp;</th>
									<th>Fully Evolved</th>
									<th>Entire Family</th>
									<th>Fully Trained</th>
									<th>Both Genders</th>
									<th>Have Noble</th>
									<th>Have Exalted</th>
									<th>Full Herd</th>
								</tr>
<?php	while ($acc = $accomplishments->fetch_assoc()) : ?>
								<tr class='accomplishment'>
									<td><?php _e($acc['family_name']) ?></td>
									<?php draw_accomplishment_row($acc) ?>
								</tr>
<?php	endwhile ?>
							</tbody>
						</table>
					</div>
<?php	require $template .'/footer.php'; ?>