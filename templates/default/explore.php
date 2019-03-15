<?php	require $template .'/header.php'; ?>

						<div id='explore'>
						<?php if(!empty($page->s)) _e($page->s[1]); ?>
<?php	if($page->s[0] < 2){ ?>
<?php		if(!empty($gather_array)){ ?>
							<table class='center maxwidth'>
								<tbody>
									<tr>
<?php			foreach($gather_array as $gather): ?>
										<td><?php _e($gather) ?></td>
<?php			endforeach ?>
									</tr>
								</tbody>
							</table>
<?php		} ?>

							<div class='b center'><?php _e($story['title']) ?></div>
							<p class='center'><?php _e($story['story']) ?></p>
							<table class='center maxwidth' id='explore-options'>
								<tbody>
									<tr>
<?php		for($i = 1; $i < 4; ++$i): ?>
										<td id='explore-option-<?php _e($i) ?>'>
											<a href='/explore.php?area=<?php _e($_GET['area']) ?>&creature=<?php _e($story["creature_$i"."_ID"][0]) ?>&key=<?php _e($new_key) ?>'><img src='<?php _e(mkimg(array('creatureID'=>$story["creature_$i"."_ID"][0]))) ?>' /></a>
											<p><?php _e($story["creature_$i"."_option"]) ?></p>
										</td>
<?php		endfor; ?>
									</tr>
								</tbody>
							</table>
<?php	} elseif($page->s[0] == 2){ ?>
							<p>Where would you like to go?</p>
							<table class='center m-auto'>
								<tbody>
<?php		$i = 0;
			while($area = $areas->fetch_assoc()): ?>
<?php			if($i === 0){ ?>
									<tr>
	<?php 		} ?>
										<td>
											<a href='/explore.php?area=<?php _e($area['exploreID']) ?>'><img src='/images/world/explore_<?php _e(concise($area['name'])) ?>.png' /></a>
											<br />
											<span class='b'>(<?php _e($area['accomplishments']) ?> Accomplishments)</span>
											<br />
											<?php _e($area['name']) ?>
											<p><?php _e(esc_html($area['description'])) ?></p>
										</td>
	<?php		if($i < 3){
					++$i;
				} if($i === 3){
					$i = 0; ?>
									</tr>
	<?php		}
			endwhile ?>
								</tbody>
							</table>
<?php	} ?>
						</div>
<?php	require $template .'/footer.php'; ?>