<?php	require $template .'/header.php'; ?>
					<div class='center warning'>
						<a href='/view.php?id=<?php _e($creature['ID']) ?>'><img src='<?php _e(mkimg($creature)) ?>' /></a>
						<p>
							Are you sure you would you like to free <?php _e($creature['nickname']) ?> completely, sending it off into the world?
						</p>
						<p>
							<a href='abandon.php?id=<?php _e($creature['ID']) ?>&confirm=lol'>Yes. Farewell, beloved <?php  _e($creature['nickname']) ?>.</a>
						</p>
						<p class='deny b'>
							(Once you confirm, there is NO going back!)
						</p>
					</div>
<?php	require $template .'/footer.php'; ?>