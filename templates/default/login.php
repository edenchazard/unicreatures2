<?php	require $template .'/header.php'; ?>
					<div class='center'>
					<?php if(!empty($page->s)) _e($page->s[1]) ?>
<?php	if($page->s[0] == 2 || $page->s[0] == 4){ ?>
						<h1>Already have an account at UniCreatures?</h1>
						<form method='POST'>
							<div>
								<label for='username'>Username:</label> <input type='text' name='username' />
								<strong>OR</strong>
								<label for='userid'>User #ID:</label> <input type='text' name='userid' /><br />
								<label for='password'>Password</label> <input type='password' name='password' />
								<input type='submit' value='Login!' />
							</div>
						</form>
						<h1>Don't have an account?</h1>
						<p>Registration is free, quick and easy. Just adopt a pet below to get started! You will be able to complete registration afterwards.</p>
<?php		while($adopt = $creatures->fetch_assoc()):?>
						<a href='/login.php?id=<?php _e($adopt['creatureID']) ?>'><img src='<?php _e(mkimg($adopt)) ?>' alt='<?php _e($adopt['family_name']) ?>' /></a>
<?php		endwhile; ?>
<?php	} ?>
					</div>
<?php	require $template .'/footer.php'; ?>