<?php 	require $template .'/header.php'; ?>
						<table class='training'>
							<tbody>
								<tr>
									<td class='creature'>
										<a href='/view.php?id=<?php _e($_GET['id']) ?>'><img src='<?php _e(mkimg($creature)) ?>' alt='creature' /></a>
									</td>
									<td colspan='3'><?php _e("Energy: {$energy['current']}/{$energy['max']} (More in 0:04)") ?>
										<br />
										<span class='allow'>Clicking on <?php _e($creature['nickname']) ?> will take you to its profile.</span>
									</td>
								</tr>
								<tr>
								<td colspan='4'>
									<table class='maxwidth'>
										<tbody>
											<tr>
												<td>Strength:</td>
												<td><?php _e("{$skills['strength']}/{$skill_limits['strength']}") ?></td>
												<td>Intelligence:</td>
												<td><?php _e("{$skills['intelligence']}/{$skill_limits['intelligence']}") ?></td>
												<td>Charisma:</td>
												<td><?php _e("{$skills['charisma']}/{$skill_limits['charisma']}") ?></td>
											</tr>
											<tr>
												<td>Agility:</td>
												<td><?php _e("{$skills['agility']}/{$skill_limits['agility']}") ?></td>
												<td>Wisdom:</td>
												<td><?php _e("{$skills['wisdom']}/{$skill_limits['wisdom']}") ?></td>
												<td>Willpower:</td>
												<td><?php _e("{$skills['willpower']}/{$skill_limits['willpower']}") ?></td>
											</tr>
											<tr>
												<td>Speed:</td>
												<td><?php _e("{$skills['speed']}/{$skill_limits['speed']}") ?></td>
												<td>Creativity:</td>
												<td><?php _e("{$skills['creativity']}/{$skill_limits['creativity']}") ?></td>
												<td>Focus:</td>
												<td><?php _e("{$skills['focus']}/{$skill_limits['focus']}") ?></td>
											</tr>
<?php 		if(!empty($page->s)){ ?>
											<tr>
												<td colspan='6'><?php _e($page->s[1]) ?></td>
											</tr>
<?php		} ?>
<?php		if($skills['powers'] > 0){ ?>
											<tr>
												<td colspan='6'>
													<span class='b'>Powers (<?php _e("{$skills['powers']}") ?>/272), (+<?php _e(calc_powers($skills['powers'])) ?> bonus):</span>
		<?php	while ($power = $powers->fetch_assoc()):
													_e($power['skill']) ?> <span class='b'>(<?php _e($power['level']) ?>)</span>
		<?php	endwhile;
			} ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<th>Action</th>
								<th>Cost</th>
								<th>Reward</th>
								<th>&nbsp;</th>
							</tr>

<?php 	while($option = $options->fetch_assoc()): $rewards = rewardsstring2array($option['reward']) ?>

							<tr>
								<td><a href='/training.php?id=<?php _e("{$_GET['id']}&option={$option['optionID']}") ?>'><?php _e($option['title']) ?></a></td>
								<td><?php _e($option['energy']) ?> Energy</td>
								<td><?php _e($rewards['formatted']) ?></td>
								<td>
									<a href='/training.php?id=<?php _e("{$_GET['id']}&option={$option['optionID']}") ?>&y=2'>2x</a>,
									<a href='/training.php?id=<?php _e("{$_GET['id']}&option={$option['optionID']}") ?>&y=3'>3x</a>,
									<a href='/training.php?id=<?php _e("{$_GET['id']}&option={$option['optionID']}") ?>&y=4'>4x</a>
								</td>
							</tr>
<?php	endwhile;													 ?>
						</tbody>
					</table>
<?php	require $template .'/footer.php'; ?>