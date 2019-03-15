<?php	require $template .'/header.php'; ?>
					<div class='center'>
						<?php if(!empty($page->s)) _e($page->s[1]) ?>
						<p class='warning'>You are currently renaming <?php _e($creature['nickname']) ?></p>
						<a href='/view.php?id=<?php _e($creature['ID']) ?>'><img src='<?php _e(mkimg($creature)) ?>' /></a>
						<form method='POST'>
							New name: <input type='text' name='name' size='30' /> (maximum of 30 characters)
							<br />
							<input type='submit' value='Change this name!' />
						</form>
					</div>
<?php	require $template .'/footer.php'; ?>