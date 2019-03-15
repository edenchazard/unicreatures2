<?php	require $template .'/header.php'; ?>
					<div class='center'>
						<?php if(!empty($page->s)) _e($page->s[1]) ?>
						<a href='/view.php?id=<?php _e($creature['ID']) ?>'><img src='<?php _e(mkimg($creature)) ?>' /></a>
						<br />
						<span class='b'><?php _e($creature['nickname']) ?></span>
			<?php	if($page->s[0] == 0){ ?>
						<p class='purple'>
							Are you sure you would you like to send <?php _e($creature['nickname']) ?> to the <?php _e($creature['family_name']) ?> herd?
						</p>
						<p>
							This will increase the size of your herd, but you will not be able to interact with the creature any longer - it will no longer participate in the arena, adventures, training, or be able to evolve.
						</p>
						<p>
							Noble, exalted and exotic creatures, as well as eggs, cannot enter the herd. 
						</p>
						<p>
							You must enter your password to herd the creature.
						</p>
						<p>
							<form method='post'>
								<div>
									Password: <input type='password' name='password' />
									<input type='submit' value='Send <?php  _e($creature['nickname']) ?> to the <?php _e($creature['family_name']) ?> herd.' />
								</div>
							</form>
						</p>
						<p class='deny b'>
							(WARNING: There is NO WAY to undo this process.)
						</p>
			<?php 	} ?>
					</div>
<?php	require $template .'/footer.php'; ?>