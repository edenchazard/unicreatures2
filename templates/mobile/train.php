<?php	require('./templates/mobile/header.php'); ?>
		Cost / Action / Reward
<?php	while($option = $options->fetch_assoc()): $rewards = rewardsstring2array($option['reward']); ?>
						<div class='t-left'>
							<i><?php _e($option['energy']); ?> Energy</i>
							<a href='/train/<?php _e("{$_GET['id']}/{$option['optionID']}"); ?>'><?php _e($option['title']); ?></a> [
							<a href='/train/<?php _e("{$_GET['id']}/{$option['optionID']}/2"); ?>'>2x</a>,
							<a href='/train/<?php _e("{$_GET['id']}/{$option['optionID']}/3"); ?>'>3x</a>,
							<a href='/train/<?php _e("{$_GET['id']}/{$option['optionID']}/4"); ?>'>4x</a> ]
							<br />
<?php 						_e($rewards['formatted']); 						?>
						</div>

<?php	endwhile;													 ?>

						<div>
							<a href='/view/<?php _e($_GET['id']); ?>'><img src='<?php _e(mkimg($creature)); ?>' alt='creature' /></a>
						</div>
						<div>
<?php 						_e("Energy: {$energy['current']}/{$energy['max']} (More in 0:04)"); ?>
						</div>
						<div>
							<span class='allow'>Clicking on <?php _e($creature['nickname']); ?> will take you to its profile.</span>
						</div>
						<div class='t-left'>
							<span class='b'>Strength:</span> <?php _e("{$skills['strength']}/{$skill_limits['strength']}"); ?>, 
							<span class='b'>Intelligence:</span> <?php _e("{$skills['intelligence']}/{$skill_limits['intelligence']}"); ?>,
							<span class='b'>Charisma:</span> <?php _e("{$skills['charisma']}/{$skill_limits['charisma']}"); ?>,
							<span class='b'>Agility:</span> <?php _e("{$skills['agility']}/{$skill_limits['agility']}"); ?>,
							<span class='b'>Wisdom:</span> <?php _e("{$skills['wisdom']}/{$skill_limits['wisdom']}"); ?>,
							<span class='b'>Willpower:</span> <?php _e("{$skills['willpower']}/{$skill_limits['willpower']}"); ?>,
							<span class='b'>Speed:</span> <?php _e("{$skills['speed']}/{$skill_limits['speed']}"); ?>,
							<span class='b'>Creativity:</span> <?php _e("{$skills['creativity']}/{$skill_limits['creativity']}"); ?>,
							<span class='b'>Focus:</span> <?php _e("{$skills['focus']}/{$skill_limits['focus']}"); ?>
						</div>
<?php 	if($skills['powers'] > 0): ?>
						<br />
						<div class='t-left'>
							<span class='b'>Powers (<?php _e("{$skills['powers']}"); ?>/272): </span>
<?php 		while ($power = $powers->fetch_assoc()):
				_e("{$power['skill']} ({$power['level']}) ");
			endwhile;
		endif; ?>
						</div>
<?php	require('./templates/mobile/footer.php'); 				?>