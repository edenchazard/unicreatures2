<?php	require $template .'/header.php'; ?>
						<script type='text/javascript'>
$(document).ready(function(){
	uc_library.drag_drop_move();
});
						</script>
						<div class='left'>
							<p class='purple'>Using a sort method other than custom will sort your creatures automatically.</p>
							<form method='post'>
								<div>
									Sort by: <input type='radio' name='sorting' value='0'<?php _e($area['sort_method'] == 0 ? "checked='checked'" : '') ?> /> Default
									<input type='radio' name='sorting' value='1'<?php _e($area['sort_method'] == 1 ? "checked='checked'" : '') ?> /> Family > stage > speciality
									<input type='radio' name='sorting' value='2'<?php _e($area['sort_method'] == 2 ? "checked='checked'" : '') ?> /> Release date
									<input type='radio' name='sorting' value='3'<?php _e($area['sort_method'] == 3 ? "checked='checked'" : '') ?> /> Custom
									<input type='submit' value='Set sorting method' />
								</div>
							</form>
						</div>
						<div class='center'>
<?php	if($area['sort_method'] == 3){ ?>
							<form method='post'>
								<div>
									<input type='submit' id='save_a' name='save_active' value='Save sorting' />
								</div>
							</form>
							<p>Drag and drop your creatures next or before each other, and then press save to sort your creatures!</p>
<?php	} ?>
							<p class='purple'>You can press the <span class='b'>t</span> key to scroll to the top of the page, the <span class='b'>m</span> key to scroll to the middle of the page, and the <span class='b'>b</span> key to scroll to the bottom of the page!</p>
						</div>
						<div id='tabs'>
<?php	while($area = $areas->fetch_assoc()): ?>
<?php	endwhile; ?>
						</div>
						<div id='items'>
<?php	while($creature = $creatures->fetch_assoc()): ?>
							<div class='sortable' id='item-<?php _e($creature['ID']) ?>' name='creature=<?php _e($creature['creatureID'])?>'>
								<?php _e(draw_creature_tile($creature)) ?>
							</div>
<?php	endwhile ?>
						</div>
						<div id='log'></div>
					</div>
<?php	require $template .'/footer.php'; ?>