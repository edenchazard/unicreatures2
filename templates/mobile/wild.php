<?php
get_header() ?>
<?php	while ($creature = $creatures->fetch_assoc()) : ?>

						<a href='/view.php?id=<?php _e($creature['ID']) ?>'><img src='<?php _e(mkimg($creature)) ?>' alt='<?php _e($creature['ID']) ?>' /></a>
	<?php				_e($creature['nickname']);
			
		endwhile;

get_footer() ?>