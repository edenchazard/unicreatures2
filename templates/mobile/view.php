<?php require $header; ?>
					<div class='view'>
						<p class='center'>
	<?php	if(!$clicked_by_ip){
				if(!$just_clicked){ ?>
							<form method='POST'>
								<div>
									<input type='hidden' name='care' />
									<input type='image' src='http://www.unicreatures.com/images/items/food_1.png' />
									<input type='image' src='http://www.unicreatures.com/images/items/toy_1.png' />
									<input type='image' src='http://www.unicreatures.com/images/items/care_1.png' />
								</div>
								<div>
									<strong>Click an option above to help this creature!</strong>
								</div>
							</form>
						</p>
	<?php		} else{ ?>
						<p class='allow center'>Thank you for caring for <?php _e("{$creature['username']}'s {$creature['creature_name']}") ?>!</p>
	<?php		}
			}
	 ?>					<p class='center'><img src='<?php _e(mkimg($creature)) ?>' alt='<?php _e($creature['family_name'])?>' /></p>
						<p>	Type: <?php _e($creature['type']) ?>, 
							Family: <?php _e($creature['family_name']) ?>,
							Name: <?php _e($creature['creature_name']) ?>,
							Rarity: <?php _e($creature['rarity']) ?>
	<?php	if($creature['speciality'] > 0) { ?>
								<span class='b'>(<?php _e(intspeciality2text($creature['speciality'])) ?>)</span>,
							Gender: <?php ($creature['gender'] === 'female' ? '&#9792;' : '&#9794;') ?>,
	<?php	} ?>
							<?php _e($creature['care']) ?> carepoints.
						</p>
	<?php	if($creature['username'] === $user->username){ ?>
						<p>
							<h2>Actions</h2>
							<ul class='actions'>
								<li><a href='/name/<?php _e($id) ?>'>Change</a> the name of this pet</li>
	<?php		while($item = $items->fetch_assoc()):
								//skip if criteria not met
								if(!fits_criteria($creature, $item['itemID'])) continue;

								switch(intval($item['itemID'])){
									//cryo
									case 2: "<li><a href='/item/{$item['itemID']}/use/$id'>Freeze</a> this pet</li>"; break;
									//f gen x
									case 3:  $page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Change</a> this pet into a female</i>"; break;
									//defrost
									case 4:  $page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Defrost</a> this pet</li>"; break;
									//story
									case 5:  $page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Write</a> a story about this pet</li>"; break;
										//	else $page->html .= "<li><a href='/story/$id'>Edit</a> this pet's story</li>"; 
									//TWW
									case 6: 	$page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Devolve</a> this pet</li>"; break;
									//m gen x
									case 7:		$page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Change</a> this pet into a male</i>"; break;
									//normalize
									case 8: 	$page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Normalize</a> this pet</i>"; break;
									//noblize
									case 9: 	$page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Noblize</a> this pet</i>"; break;
									//exalt
									case 10:	$page->html .= "<li><a href='/item/{$item['itemID']}/use/$id'>Exalt</a> this pet</i>"; break;
								}
				endwhile; ?>
							</ul>
						</p>
						<p class='center'>
							<a href='/train.php?id=<?php _e($id) ?>'><img src='http://www.unicreatures.com/images/items/training_manual.png' /></a>
							<a href='/herd.php?id=<?php _e($id) ?>'><img src='http://www.unicreatures.com/images/icons/herd.png' /></a>
							<a href='/move.php?id=<?php _e($id) ?>'><img src='http://www.unicreatures.com/images/icons/backpack.png' /></a>
							<a href='/abandon.php?id=<?php _e($id) ?>'><img src='http://www.unicreatures.com/images/abandoned.png' /></a>
							<a href='/interact.php?id=<?php _e($id) ?>'><img src='http://www.unicreatures.com/images/icons/bluefur.png' /></a>
						</p>
	<?php	if(isset($creature['text'])){ ?>
						<p>
							<h2>Story</h2>
							<p class='blue'><?php _e(nl2br(htmlspecialchars($creature['text']))) ?></p>
						</p>
	<?php	} ?>
						<p>
							<h2>Visual Description</h2>
							<p><?php _e($creature['visual_description']) ?></p>
						</p>
						<p>
							<h2>Lifestyle</h2>
							<p class='lifestyle'><?php _e($creature['lifestyle']) ?></p>
						</p>
						<p class='center'><span class='b'>BB Code:</span></p>
						<p>
							<textarea>[url=http://unicreatures.com/view/<?php _e($id) ?>][img]http://unicreatures.com/image/<?php _e($id) ?>.png[/img][/url]</textarea>
						</p>
						<p class='center'><span class='b'>HTML Code:</span></p>
						<p>
							<textarea><a href="http://unicreatures.com/view/<?php _e($id) ?>"><img src="http://unicreatures.com/image/<?php _e($id) ?>.png" alt="Unicreatures #<?php _e($id) ?>" /></a></textarea>
						</p>
	<?php	} ?>
						<p>
							<h2>Arena Rating (Rating of 739)</h2>
							<span class='b'>Strength:</span> <?php _e("5/5") ?>, 
							<span class='b'>Intelligence:</span> <?php _e("10/20") ?>,
							<span class='b'>Charisma:</span> <?php _e("50/30") ?>,
							<span class='b'>Agility:</span> <?php _e("17/43") ?>,
							<span class='b'>Wisdom:</span> <?php _e("20/20") ?>,
							<span class='b'>Willpower:</span> <?php _e("5/98") ?>,
							<span class='b'>Speed:</span> <?php _e("23/30") ?>,
							<span class='b'>Creativity:</span> <?php _e("1/10") ?>,
							<span class='b'>Focus:</span> <?php _e("42/42") ?>
						</p>
						<p>
							<span class='b'>Powers (308/272):</span> Identify(9), Predict(29), Upgrade(14), Electric Storm(24), Hide(232)
						</p>
						<p class='center'>
							<a href='/profile/<?php _e($creature['username']) ?>'><img src='http://www.unicreatures.com/images/items/sign_return.png' /><br />Go to <?php _e($creature['username']) ?>'s profile</a>
						</p>
					</div>
<?php require $footer ?>