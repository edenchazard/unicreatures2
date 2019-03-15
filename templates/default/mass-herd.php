<?php	require $template .'/header.php'; ?>
						<script type='text/javascript'>
$(document).ready(function(){
	$('#selectable').selectable({filter: 'td'});

	$('#myform').on('submit', function(e){
		e.preventDefault();
		uc_library.mass_herd();
	});
});

						</script>
						<div class='center'>
							<p class='purple'>To mass-herd these creatures, you must enter your password.</p>
							<form method='POST' id='myform'>
								<div>
									<input type='password' name='password' />
									<input id='sub' type='submit' value='Mass herd the selected creatures.' />
								</div>
							</form>
						</div>
						<table id='selectable' class='table-creatures'>
<?php	while($creature = $creatures->fetch_assoc()) :
			_e(draw_fancy_table_rows(8, "
							<div id='item-{$creature['ID']}' name='creature={$creature['creatureID']}'>"
								.draw_creature_tile($creature).
							"</div>"));
		endwhile ?>
						</table>
					</div>
<?php	require $template .'/footer.php'; ?>