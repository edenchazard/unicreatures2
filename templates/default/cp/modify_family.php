<?php 	require $template .'/header.php'; ?>
				<div id='usercp'>
					<h1>Modifying a family</h1>
					<?php if(!empty($page->s)) _e($page->s[1]) ?>
<?php	if(empty($editing_family)){ ?>
					<form method='GET'>
						<div>
							Select family:
							<select name='family'>
<?php		while($family = $families_list->fetch_assoc()): ?>
<?php		/*if(!empty($editing_family) &&
				(concise($family['family_name']) == concise($editing_family['family_name'])))*/
				?>
									<option value='<?php _e($family['family_name']) ?>'><?php _e($family['family_name']) ?></option>
<?php		endwhile ?>
							</select>
							<input type='submit' value='Select' />
						</div>
					</form>
<?php	} else { ?>
					<form method='POST'>
						<div>
							Family name: <input type='text' name='family_name' value='<?php _e($editing_family['family_name']) ?>' /><br />
							Type: <input type='text' name='type' value='<?php _e($editing_family['type']) ?>' /><br />
							Rarity: <input type='text' name='rarity' value='<?php _e($editing_family['rarity']) ?>' /><br />

							<input type='checkbox' name='in_basket' value='true' <?php _e($editing_family['in_basket'] ? 'checked="yes"' : '') ?> /> Show in basket<br />
							<input type='checkbox' name='deny_ne' value='true' <?php _e($editing_family['deny_ne'] ? 'checked="yes"' : '') ?> /> Deny Noble/Exalted <br />
							<input type='submit' value='Modify' />
<?php	}?>
						</div>
					</form>
				</div>
<?php	require $template .'/footer.php'; ?>
