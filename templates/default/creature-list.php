<?php	require $template .'/header.php'; ?>
					<table class='table-creatures' style='padding:0; margin:0; border-collapse:collapse;'>
						<tbody>
							<tr>
<?php	$family_name = '';
		while ($creature = $list->fetch_assoc()) :
			if($family_name !== $creature['family_name']){
				$family_name = $creature['family_name']; ?>
							</tr>
							<tr>
	<?php 	} ?>
								<td style='border:1px solid #000;'>
									<img src='<?php _e(mkimg($creature)) ?>' />
									<br />
									<?php _e($creature['creature_name']) ?>
								</td>
	<?php	if($family_name !== $creature['family_name']) {?>
							</tr>
	<?php		
			}
			
		endwhile ?>
						</tbody>
					</table>
<?php	require $template .'/footer.php' ?>