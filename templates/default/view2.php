<?php	if(!defined('FRM_MAIN')) exit; ?>
<?php	$tpl->render('header') ?>
<table class='maxwidth view'>
	<tbody>
		<tr id='food' class='center'>
			<td colspan='2'>
				<? if($tpl->response_msg) : ?>
					<?=$tpl->response_msg ?>
				<? endif ?>
				<? if(!$isfrozen) : ?>
					<? if(!$clicked) : ?>
						<form method='POST'>
							<div>
								<input type='hidden' name='care' />
								<input type='image' src='/images/items/food_1.png' />
								<input type='image' src='/images/items/toy_1.png' />
								<input type='image' src='/images/items/care_1.png' />
							</div>
						</form>
					<? else : ?>
						<? foreach(seed_components($id) as $key => $component) : ?>
								<div class='view-components'>
									<a href='/view.php?id=<?=$id ?>&feed=<?=$component ?>'><img src='/images/components/<?=$component ?>.png' alt='<?=ucfirst($component) ?>' /></a>
									<br />
									<?=ucfirst($component) ?> (<?=number_format($user->inventory($component)) ?>)
									<br />
									<a href='/view.php?id=<?=$id ?>&feed=<?=$key ?>&q=10'>10x</a>
									<a href='/view.php?id=<?=$id ?>&feed=<?=$key ?>&q=25'>25x</a>
									<? if($can_evolve) : ?>
										<? if($user->inventory($component) >= $needs_to_evolve) : ?>
											<a href='/view.php?id=<?=$id ?>&feed=<?=$key ?>&q=<?=$needs_to_evolve ?>'><?=$needs_to_evolve ?>x</a>
										<? endif ?>
									<? endif ?>
								</div>
						<? endforeach ?>
					<? endif ?>
				<? endif ?>
			</td>
		</tr>
		<tr class='center'>
			<td>
				<img src='<?=$image ?>' alt='<?=$family ?>' />
				<br />
				<? if($isfrozen) : ?><span class='blue'><?=$nickname ?></span>
				<? else : ?> <?=$nickname ?>
				<? endif ?>
			</td>
			<td>
				<a href='/profile.php?username=<?=$owner ?>'><img src='/images/items/sign_return.png' /></a>
				<br />
				Go to <?=$owner ?>'s profile
			</td>
		</tr>
		<tr>
			<td>
				Type: <?=$type ?>
				<br />
				Family: <?=$family ?>
				<br />
				Name: <?=$creature_name ?>
			</td>
			<td>
				Rarity: <?=$rarity ?>
				<? if($has_speciality) : ?>
				<span class='b'>(<?=$speciality ?>)</span>
				<? endif ?>
				<br />
				Gender: <?=$gender ?>
				<br />
				<? if($isfrozen): ?>
				<span class='blue'><?=$nickname ?> is frozen solid!</span>
				<? else : ?>
				<?=$nickname ?> has <?=$carepoints ?> carepoints
				<? endif ?>
				<? if(!$isfrozen && $can_evolve) : ?>
					<? if($needs_to_evolve > 0) : ?>
						and needs <span class='b'><?=$needs_to_evolve ?></span> more to evolve.
					<? elseif($user_is_owner) : ?>
						<br />
						<a href='/evolve.php?id=<?=$id ?>'><?=$nickname ?> can evolve!</a>
					<? endif ?>
				<? endif ?>
			</td>
		</tr>
		<? if($has_story) : ?>
		<tr>
			<td colspan='2'>
				<h2>Story</h2>
				<p class='blue'><?=$story ?></p>
			</td>
		</tr>
		<? endif ?>
		<tr>
			<td colspan='2'>
				<h2>Visual Description</h2>
				<p><?=$visual_description ?></p>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<br />
				<h2>Lifestyle</h2>
				<p class='lifestyle'><?=$lifestyle ?></p>
			</td>
		</tr>
		<? if($user_is_owner) : ?>
		<tr>
			<td>
				<h2>You can...</h2>
				<ul class='actions'>
					<li><a href='/name.php?id=<?=$id ?>'>Change</a> the name of this pet</li>
					<li><a href='/breed.php?id=<?=$id ?>'>Breed</a> this pet</li>
					<? if($can_freeze) : ?>
					<li><a href='/item.php?item=cryogenic_freeze_spray&id=<?=$id ?>'>Freeze</a> this pet</li>
					<? elseif($can_defrost) : ?>
					<li><a href='/item.php?item=defrosting_torch&id=<?=$id ?>'>Defrost</a> this pet</li>
					<? endif ?>
					<? if($can_fem_gen_x) : ?>
					<li><a href='/item.php?item=female_gen_x&id=<?=$id ?>'>Change</a> this pet into a female</li>
					<? elseif($can_male_gen_x) : ?>
					<li><a href='/item.php?item=male_gen_x&id=<?=$id ?>'>Change</a> this pet into a male</li>
					<? endif ?>
					<? if($can_noble) : ?>
					<li><a href='/item.php?item=elixir_of_nobility&id=<?=$id ?>'>Noblize</a> this pet</li>
					<? elseif($can_exalt) : ?>
					<li><a href='/item.php?item=elixir_of_exaltation&id=<?=$id ?>'>Exalt</a> this pet</li>
					<? endif ?>
					<? if($can_normalize) : ?>
					<li><a href='/item.php?item=normalize_potion&id=<?=$id ?>'>Normalize</a> this pet</li>
					<? endif ?>
					<? if($can_devolve) : ?>
					<li><a href='/item.php?item=time_warp_watch&id=<?=$id ?>'>Devolve</a> this pet</li>
					<? endif ?>
				</ul>
			</td>
			<td class='table-bottom'>
				<table class='maxwidth'>
					<tbody>
						<tr class='center'>
							<td><a href='/training.php?id=<?=$id ?>'><img src='/images/items/training_manual.png' /></a></td>
							<td><a href='/herd_send.php?id=<?=$id ?>'><img src='/images/icons2/herd.png' /></a></td>
							<td><a href='/move.php?id=<?=$id ?>'><img src='/images/icons2/backpack.png' /></a></td>
							<td><a href='/abandon.php?id=<?=$id ?>'><img src='/images/abandoned.png' /></a></td>
							<td><a href='/interact.php?id=<?=$id ?>'><img src='/images/icons2/bluefur.png' /></a></td>
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
							<td colspan='4'><input type='text' class='maxwidth' value='[url=http://unicreatures.com/view/<?=$id ?>][img]http://unicreatures.com/image/<?=$id ?>.png[/img][/url]' /></td>
						</tr>
						<tr>
							<td><strong>HTML Code:</strong></td>
							<td colspan='4'><input type='text' class='maxwidth'  value='<a href="http://unicreatures.com/view.php?id=<?=$id ?>"><img src="http://unicreatures.com/image/<?=$id ?>.png" alt="Unicreatures <?=$id ?>" /></a>' />
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<? if($has_skills) : ?>
		<tr>
			<td colspan='2'>
				<br />
				<u>Arena Rating</u> (Rating of <?=$total_skill ?>)
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
						<? if($skills['powers']) : ?>
						<tr>
							<td colspan='6'>
								<span class='b'>Powers (<?php _e("{$skills['powers']}") ?>/272):</span>
								<? while ($power = $powers->fetch_assoc() ) : ?>
									<?=$power['skill'] ?> <span class='b'>(<?=$power['level'] ?>)</span>
								<? endwhile ?>
							</td>
						</tr>
						<? endif ?>
					</tbody>
				</table>
			</td>
		</tr>
		<? endif ?>
		<? endif ?>
	</tbody>
</table>
<div  style='margin-top:30px; text-align:right'>Art by <?=$artist_name ?></div>
<?php $tpl->render('footer') ?>