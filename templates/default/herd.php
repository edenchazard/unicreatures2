<?php	require $template .'/header.php'; ?>
					<h1><?php _e($herd['herd_name']) ?></h1>
					<table class='table-creatures'>
						<tbody>
							<tr>
<?php	$i = 0;
		while ($creature = $creatures->fetch_assoc()) :
			for($j = 0; $j < $creature['number']; ++$j) :
				echo "<td><img src='".mkimg($creature)."' />";
					if($herd['show_names']){
						echo "
							<br />
							{$creature['creature_name']}";
					}
				echo '</td>';
				++$i;
				if($i % 8 == 0){
					echo "</tr>";
					$i = 0;
				}
			endfor;
		endwhile ?>
							<tr>
								<td colspan='4'>
									<h2>In this herd...</h2>
<?php
		$total_points = 0; $total_creatures = 0;
		while($stat = $herd_stats->fetch_assoc()) :
				$total_creatures += $stat['total'];
				$points = ($stat['total'] * ($stat['stage'] - 1));
				$total_points += $points; ?>
										<?php _e($stat['total']) ?> <?php _e(ucfirst($stat['creature_name'])) ?> (<?php _e($points) ?> points)
										<br />
<?php	endwhile; ?>
									Total: <?php _e($total_creatures) ?> creatures (<?php _e($total_points) ?> points)
								</td>
								<td colspan='4'>
									<a href=''></a>
								</td>
							</tr>
						</tbody>
					</table>
<?php	require $template .'/footer.php'; ?>