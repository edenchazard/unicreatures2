<?php	require $template .'/header.php'; ?>
				<div id='usercp'>
					<h1>Adding a new creature</h1>
					<?php if(!empty($page->s)) _e($page->s[1]) ?>
					<form method='POST' enctype="multipart/form-data">
						<div>
							<h2>General</h2>
							Family:
							<select name='family'>
<?php	while($family = $families_list->fetch_assoc()): ?>
								<option value='<?php _e($family['family_name']) ?>'
								<?php if(isset($_POST['family'])){
										_e($_POST['family'] === $family['family_name']? "SELECTED" : '');
									} ?>><?php _e($family['family_name']) ?>
								</option>
<?php	endwhile ?>
							</select>
							Creature name: <input type='text' name='name' value='<?php _e(isset($_POST['name']) ? $_POST['name'] : '') ?>' />
							Stage: <input type='text' name='stage' size='2' value='<?php _e(isset($_POST['stage']) ? $_POST['stage'] : '') ?>' />
							Care: <input type='text' name='care' size='3' value='<?php _e(isset($_POST['care']) ? $_POST['care'] : '') ?>' />
							Component:
							<select name='component'>
								<option value='random'>&lt;random&gt;</option>
<?php	$components = get_sys_components();
		foreach($components as $component){ ?>
								<option value='<?php _e($component) ?>' <?php if(isset($_POST['component'])){ _e($_POST['component'] === $component ? "SELECTED" : ''); } ?>><?php _e($component) ?></option>
<?php	} ?>
							</select>
							Image: <input type="file" name="upload" />
						</div>
						<table>
							<tbody>
								<tr>
									<td>
										<div>
											<h2>Texts</h2>
										</div>
										<div>
											Visual description:
											<br />
											<textarea name='visual_description' cols='80' rows='2'><?php _e(isset($_POST['visual_description']) ? $_POST['visual_description'] : '') ?></textarea>
										</div>
										<div>
											Lifestyle:
											<br />
											<textarea name='lifestyle' cols='80' rows='11'><?php _e(isset($_POST['lifestyle']) ? $_POST['lifestyle'] : '') ?></textarea>
										</div>
									</td>
									<td>
										<div>
											<h2>Training</h2>
											<table>
												<tbody>
													<tr>
														<td>Strength:</td>
														<td><input type='text' name='strength' size='3' value='0' /></td>
														<td>Intelligence:</td>
														<td><input type='text' name='intelligence' size='3' value='0' /></td>
														<td>Charisma:</td>
														<td><input type='text' name='charisma' size='3' value='0' /></td>
													</tr>
													<tr>
														<td>Agility:</td>
														<td><input type='text' name='agility' size='3' value='0' /></td>
														<td>Wisdom:</td>
														<td><input type='text' name='wisdom' size='3' value='0' /></td>
														<td>Willpower:</td>
														<td><input type='text' name='willpower' size='3' value='0' /></td>
													</tr>
													<tr>
														<td>Speed:</td>
														<td><input type='text' name='speed' size='3' value='0' /></td>
														<td>Creativity:</td>
														<td><input type='text' name='creativity' size='3' value='0' /></td>
														<td>Focus:</td>
														<td><input type='text' name='focus' size='3' value='0' /></td>
													</tr>
												</tbody>
											</table>
											<table>
												<tbody>
													<tr>
														<th>Action</th><th>Cost</th><th>Reward</th>
													</tr>
<?php	for($i = 0; $i < 10; ++$i): ?>
													<tr>
														<td><input type='text' name='training_action[<?php _e($i) ?>]' /></td>
														<td><input type='text' name='training_cost[<?php _e($i) ?>]' size='2' /></td>
														<td><input type='text' name='training_reward[<?php _e($i) ?>]' /></td>
													</tr>
<?php	endfor; ?>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<input type='submit' value='Add creature' />
					</form>
				</div>
<?php	require $template .'/footer.php'; ?>