<?php	require $template .'/header.php'; ?>

						<div>
						<?php if(!empty($page->s)) _e($page->s[1]); ?>
<?php	if($page->s[0] == 0){ ?>
							<p>Click a creature to breed with.</p>
							<table class='center m-auto'>
								<tbody>
<?php		$i = 0;
			while($breed_to = $can_breed_to->fetch_assoc()): ?>
<?php			if($i === 0){ ?>
									<tr>
	<?php 		} ?>
										<td>
											<a href='/breed.php?id=<?php _e($creature['ID']) ?>&to=<?php _e($breed_to['ID']) ?>'><img src='<?php _e(mkimg($breed_to)) ?>' /></a>
											<br />
											<?php _e($breed_to['nickname']) ?>
										</td>
	<?php		if($i < 8){
					++$i;
				} if($i === 8){
					$i = 0; ?>
									</tr>
	<?php		}
			endwhile ?>
								</tbody>
							</table>
<?php	} //if($page->s[0] == 1) ?>
						</div>
<?php	require $template .'/footer.php'; ?>