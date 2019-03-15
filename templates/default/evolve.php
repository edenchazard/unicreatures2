<?php require $template .'/header.php'; ?>
						<div class='center'>
							<?php if(!empty($page->s)) _e($page->s[1]) ?>
							<p>Your <?php _e($prev_creature_name) ?> has evolved into a...</p>
							<p><a href='/view.php?id=<?php _e($creature['ID']) ?>'><img src='<?php _e(mkimg($creature)) ?>' /></a></p>
							<p><?php _e($creature['creature_name']) ?>!</p>
							<p><?php _e($creature['lifestyle']) ?></p>
						</div>
<?php require $template .'/footer.php'; ?>