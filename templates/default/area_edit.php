<?php	require $template .'/header.php'; ?>
					<div>
						<?php if(!empty($page->s)) _e($page->s[1]) ?>
						<form method='POST' action='id=<?php _e($_GET['id']) ?>&action=rename'>
							<div>
								<input type='text' name='newname' value='<?php _e($plot['name']) ?>' />
								<input type='submit' value='Change name' />
							</div>
						</form>
<?php	if($next_stage) { ?>
						<table class='center'>
							<tbody>
								<tr>
									<td>
										<img src='/images/world/<?php _e(concise($plot['plot_name'])) ?>_<?php _e($next_stage['stage']) ?>.png' />
										<br />
										Level <?php _e($next_stage['stage']) ?> <?php _e($plot['name']) ?> Plot
									</td>
									<td>
<?php		foreach($requirements as $require): ?>
										<img src='<?php _e($require['obj']->image()) ?>' /> <?php _e($require['obj']->name . " ({$require['amount']})") ?>
<?php		endforeach ?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<a href='/area_edit.php?id=<?php _e($_GET['id']) ?>&action=upgrade'>Upgrade land plot!</a>
									</td>
								</tr>
							</tbody>
						</table>
<?php	} else { ?>
						<div>
							This plot has been fully developed.
						</div>
<?php	} ?>
					</div>
<?php	require $template .'/footer.php'; ?>