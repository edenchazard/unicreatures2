<?php	require $template .'/header.php'; ?>
				<table class='maxwidth view'>
					<tbody>
						<tr id='food' class='center'>
							<td colspan='2'>
								<?php if(!empty($page->s)) _e($page->s[1]) ?>
<?php	if(!$creature['frozen']){ 
			if($page->s[0] == 3){ ?>
								<form method='POST'>
									<div>
										<input type='hidden' name='care' />
										<input type='image' src='/images/items/food_1.png' />
										<input type='image' src='/images/items/toy_1.png' />
										<input type='image' src='/images/items/care_1.png' />
									</div>
								</form>
<?php		} else if($page->s[0] == 4){ ?>
<?php			foreach($components as $x => $component){ $component = new something('component', $component) ?>
								<div class='view-components'>
									<a href='/view.php?id=<?=$creature['ID'] ?>&feed=<?=$x ?>'><img src='<?=$component->image() ?>' alt='<?=$component->name ?>' /></a>
									<br />
									<?=ucfirst($component->name) ?>
									(<?=number_format($user->inventory($component->name)) ?>)
									<br />
									<a href='/view.php?id=<?=$creature['ID'] ?>&feed=<?=$x ?>&q=10'>10x</a>
									<a href='/view.php?id=<?php _e($creature['ID']) ?>&feed=<?php _e($x) ?>&q=25'>25x</a>
<?php				if($user->inventory($component->name) >= $creature['needs'] && $creature['needs'] > 0){ ?>
									<a href='/view.php?id=<?php _e($creature['ID']) ?>&feed=<?php _e($x) ?>&q=<?php _e($creature['needs']) ?>'><?php _e($creature['needs']) ?>x</a>
<?php				} ?>
								</div>
<?php			}
			}
		}?>
							</td>
						</tr>
						<tr class='center'>
							<td>
								<img src='<?php _e(mkimg($creature)) ?>' alt='<?php _e($creature['family_name'])?>' />
								<br />
<?php 	if($creature['frozen']){ ?>
								<span class='blue'><?php _e($creature['nickname']) ?></span>
<?php 	} else {
								_e($creature['nickname']);
		} ?>
							</td>
							<td>
								<a href='/profile.php?username=<?php _e($creature['owner']) ?>'><img src='/images/items/sign_return.png' /><br />Go to <?php _e($creature['owner']) ?>'s profile</a>
							</td>
						</tr>
						<tr>
							<td>
								Type: <?php _e(ucfirst($creature['type'])) ?><br />
								Family: <?php _e(ucfirst($creature['family_name'])) ?><br />
								Name: <?php _e(ucfirst($creature['creature_name'])) ?>
							</td>
							<td>
								Rarity: <?php _e(ucfirst(determine_rarity($creature['rarity']))) ?>
<?php	if($creature['speciality'] > 0) { ?>
								<span class='b'>(<?php _e(ucfirst(intspeciality2text($creature['speciality']))) ?>)</span>
<?php	} ?>
								<br />Gender: <?php _e(ucfirst($creature['gender'])) ?><br />
<?php	if($creature['frozen']){ ?>
								<span class='blue'><?php _e($creature['nickname']) ?> is frozen solid!</span>
<?php	} else { ?>
								<?php _e($creature['nickname']) ?> has <?php _e($creature['care']) ?> carepoints
<?php	 	if($creature['needs'] > 0) { ?>
									and needs <span class='b'><?php _e($creature['needs']) ?></span> more to evolve.
<?php		}
		} if(  (!$creature['frozen'])
			&& ($user->userID == $creature['userID'])
			&& ($creature['care'] >= $creature['required_clicks'])
			&& ($creature['required_clicks'] != 0)) { ?>
								<br />
								<a href='/evolve.php?id=<?php _e($creature['ID']) ?>'><?php _e($creature['nickname']) ?> can evolve!</a>
<?php	} ?>
							</td>
						</tr>
<?php	if($creature['story']){ ?>
						<tr>
							<td colspan='2'>
								<h2>Story</h2>
								<p class='blue'><?php _e($creature['story']) ?></p>
							</td>
						</tr>
<?php	} ?>
						<tr>
							<td colspan='2'>
								<h2>Visual Description</h2>
								<p><?php _e($creature['visual_description']) ?></p>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<br />
								<h2>Lifestyle</h2>
								<p class='lifestyle'><?php _e($creature['lifestyle']) ?></p>
							</td>
						</tr>
<?php	if($creature['owner'] === $user->username){ ?>
						<tr>
							<td>
								<h2>You can...</h2>
								<ul class='actions'>
									<li><a href='/name.php?id=<?php _e($creature['ID']) ?>'>Change</a> the name of this pet</li>
									<li><a href='/breed.php?id=<?php _e($creature['ID']) ?>'>Breed</a> this pet</li>
<?php		if($user->inventory('cryogenic_freeze_spray')
				&& !$creature['frozen']){ ?>
									<li><a href='/item.php?item=cryogenic_freeze_spray&id=<?php _e($creature['ID']) ?>'>Freeze</a> this pet</li>
<?php		} if($user->inventory('female_gen_x')
				&& $creature['gender'] === 'male'
				&& !$creature['frozen']){ ?>
									<li><a href='/item.php?item=female_gen_x&id=<?php _e($creature['ID']) ?>'>Change</a> this pet into a female</li>
<?php		} if($user->inventory('male_gen_x')
				&& $creature['gender'] === 'female'
				&& !$creature['frozen']){ ?>
									<li><a href='/item.php?item=male_gen_x&id=<?php _e($creature['ID']) ?>'>Change</a> this pet into a male</li>
<?php		} if($user->inventory('defrosting_torch')
				&& $creature['frozen']){ ?>
									<li><a href='/item.php?item=defrosting_torch&id=<?php _e($creature['ID']) ?>'>Defrost</a> this pet</li>
<?php		} if($user->inventory('elixir_of_nobility')
				&& !$creature['deny_ne']
				&& !$creature['frozen']
				&& $creature['care'] >= 500
				&& ($creature['speciality'] == 0
				|| $creature['speciality'] == 2)
				){ ?>
									<li><a href='/item.php?item=elixir_of_nobility&id=<?php _e($creature['ID']) ?>'>Noblize</a> this pet</li>
<?php		} if($user->inventory('elixir_of_exaltation')
				&& !$creature['deny_ne']
				&& !$creature['frozen']
				&& $creature['care'] >= 1000
				&& $creature['speciality'] == 1){ ?>
									<li><a href='/item.php?item=elixir_of_exaltation&id=<?php _e($creature['ID']) ?>'>Exalt</a> this pet</li>
<?php		} if($user->inventory('normalize_potion')
				&& !$creature['frozen']
				&& ($creature['speciality'] == 1
				|| $creature['speciality'] == 2)
				){ ?>
									<li><a href='/item.php?item=normalize_potion&id=<?php _e($creature['ID']) ?>'>Normalize</a> this pet</li>
<?php		} if($user->inventory('time_warp_watch')
				&& $creature['stage'] > 1
				&& !$creature['frozen']){ ?>
									<li><a href='/item.php?item=time_warp_watch&id=<?php _e($creature['ID']) ?>'>Devolve</a> this pet</li>
<?php		} ?>
								<!--//story
								case 5:  $page->html .= "<li><a href='/item/{$item['itemID']}/use/$creature['ID']'>Write</a> a story about this pet</li>"; break;
									//	else $page->html .= "<li><a href='/story/$creature['ID']'>Edit</a> this pet's story</li>"; 
							}-->
								</ul>
							</td>
							<td class='table-bottom'>
								<table class='maxwidth'>
									<tbody>
										<tr class='center'>
											<td><a href='/training.php?id=<?php _e($creature['ID']) ?>'><img src='/images/items/training_manual.png' /></a></td>
											<td><a href='/herd_send.php?id=<?php _e($creature['ID']) ?>'><img src='/images/icons2/herd.png' /></a></td>
											<td><a href='/move.php?id=<?php _e($creature['ID']) ?>'><img src='/images/icons2/backpack.png' /></a></td>
											<td><a href='/abandon.php?id=<?php _e($creature['ID']) ?>'><img src='/images/abandoned.png' /></a></td>
											<td><a href='/interact.php?id=<?php _e($creature['ID']) ?>'><img src='/images/icons2/bluefur.png' /></a></td>
										</tr>
										<tr class='center'>
											<td>Train</td>
											<td>Herd</td>
											<td>Move</td>
											<td>Abandon</td>
											<td>Interact</td>
										</tr>
										<tr class='center'>
											<td colspan='5'>
												<span class='italic'>Post this creature on a forum or your website!</span>
											</td>
										<tr>
											<td><strong>BB Code:</strong></td>
											<td colspan='4'><input type='text' class='maxwidth' value='[url=http://unicreatures.com/view/<?php _e($creature['ID']) ?>][img]http://unicreatures.com/image/<?php _e($creature['ID']) ?>.png[/img][/url]' /></td>
										</tr>
										<tr>
											<td><strong>HTML Code:</strong></td>
											<td colspan='4'><input type='text' class='maxwidth'  value='<a href="http://unicreatures.com/view.php?id=<?php _e($creature['ID']) ?>"><img src="http://unicreatures.com/image/<?php _e($creature['ID']) ?>.png" alt="Unicreatures #<?php _e($creature['ID']) ?>" /></a>' />
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
<?php	}
		if($creature['stage'] > 1 && isset($skills)){ ?>
						<tr>
							<td colspan='2'>
								<br />
								<u>Arena Rating</u> (Rating of <?php _e($skills['total']) ?>)
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
<?php		if($skills['powers'] > 0){ ?>
										<tr>
											<td colspan='6'>
												<span class='b'>Powers (<?php _e("{$skills['powers']}") ?>/272):</span>
<?php			while ($power = $powers->fetch_assoc()):
												_e($power['skill']) ?> <span class='b'>(<?php _e($power['level']) ?>)</span>
<?php			endwhile;
			}
		} ?>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<div  style='margin-top:30px; text-align:right'>Art by <?php _e($creature['artist']) ?></div>
<?php require $template .'/footer.php'; ?>