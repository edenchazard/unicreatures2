<?php	require $template .'/header.php'; ?>
					<div class='center'>
					<?php if(!empty($page->s)) _e($page->s[1]) ?>
						<table>
							<tbody>
<?php
		$i = 0;
		while ($plot = $plots->fetch_assoc()) :
			if($i === 0){ ?>
								<tr>
	<?php 	} ?>
									<td>
										<a href='/deeds_purchase.php?id=<?php _e($plot['plotTypeID']) ?>'><img src='/images/world/<?php _e(concise($plot['name'])) ?>_1.png' /></a>
										<br />
										<?php _e($plot['name']) ?> Plot
									</td>
	<?php	if($i < 8){
				++$i;
			} if($i === 8){
				$i = 0; ?>
							</tr>
	<?php	}
			
		endwhile ?>
							</tbody>
						</table>
					</div>
<?php	require $template .'/footer.php'; ?>