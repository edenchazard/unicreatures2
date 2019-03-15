<?php
require('./templates/mobile/header.php') ?>
					<p>"Welcome back!" says a beautiful woman as you enter.</p>
					<p>"Come in! Here! I've got some new pets for you, as long as you don't have too many already."</p>
					<p>She holds out a basket of eggs. As you reach over to take one, you see there are different types. (These change every hour, so make sure to take what you want!)</p>
<?php
	 while ($egg = $basket_eggs->fetch_assoc()) : 
		if(!$egg['claimed']){ ?>
					<div class='center'>
						<a href='/trainer/<?php _e($egg['slot']) ?>'><img src='<?php _e(mkimg($egg)) ?>' alt='<?php _e($egg['creatureID']) ?>' /></a>
						<br />
	<?php	if($egg['speciality'] > 0){ ?>
						<span class='b'><?php _e($egg['visual_description']) ?></span>
	<?php	} else
						_e($egg['visual_description']);
		} ?>
			
<?php endwhile ?>
						</tbody>
					</table>
<?php	if($found_eggs && $claimed_all){ ?>
					<span class='deny'>Sorry, you've already collected enough eggs this hour!</span>
<?php 	}
require('./templates/mobile/footer.php') ?>