<?php 	require $template .'/header.php'; ?>
<?php	$prev_date = '';
		while($alert = $alerts->fetch_assoc()):
			if(empty($prev_date)){
				$prev_date = $alert['when']; ?>
					<div class='alerts-breaker'><?php _e(date('l g, F Y', strtotime($alert['when'] . ' UTC'))) ?></div>
<?php		} else if($prev_date !== $alert['when']){
				$prev_date = $alert['when'];
			?>
					<div class='alerts-breaker'><?php _e(date('l g, F Y', strtotime($alert['when'] . ' UTC'))) ?></div>
<?php		}
		?>
					<div>
						<div style='color:#<?php _e($alert['colour']) ?>' class='b'><?php _e($alert['title']) ?></div>
						<p><?php _e($alert['message']) ?></p>
					</div>
<?php	endwhile; ?>
<?php 	require $template .'/footer.php'; ?>