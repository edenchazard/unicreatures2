<?php 	require $template .'/header.php'; ?>
				<div id='usercp'>
					<h1>Adding a new component</h1>
					<?php if(!empty($page->s)) _e($page->s[1]) ?>
					<form method='POST'>
						<div>
							Name <input type='text' name='family_name' value='<?php _e($editing_family['family_name']) ?>' /><br />
							Type: <input type='text' name='type' value='<?php _e($editing_family['type']) ?>' /><br />
							Rarity: <input type='text' name='rarity' value='<?php _e($editing_family['rarity']) ?>' /><br />

							<input type='checkbox' name='in_basket' value='true' <?php _e($editing_family['in_basket'] ? 'checked="yes"' : '') ?> /> Show in basket<br />
							<input type='checkbox' name='deny_ne' value='true' <?php _e($editing_family['deny_ne'] ? 'checked="yes"' : '') ?> /> Deny Noble/Exalted <br />
							<input type='submit' value='Modify' />
						</div>
					</form>
				</div>
<?php	require $template .'/footer.php'; ?>
