<?php	require $template .'/header.php'; ?>
					<div class='center'>
						<?php if(!empty($page->s)) _e($page->s[1]) ?>
<?php	if($page->s[0] == 1){ ?>
						<p>Are you sure you want to use this item on <span class='b'><?php _e($creature['nickname']) ?></span>?</p>
						<span class='b'><a href='/item.php?item=<?php _e($item_obj->concise) ?>&id=<?php _e($creature['ID']) ?>&confirm=1'>Yes, I'm sure.</a></span>
<?php	} ?>
						<p>
							<a href='/view.php?id=<?php _e($creature['ID']) ?>'><img src='<?php _e(mkimg($creature)) ?>' /></a>
						</p>
						<div class='b'>
							<?php _e($item_obj->name) ?>
							<span class='purple'>(<?php _e($user->inventory($item_obj->name)) ?>x remaining)</span>
						</div>
						<div>
							<img src='<?php _e($item_obj->image()) ?>' />
						</div>
						<p><?php _e($item_obj->description) ?></p>
					</div>
<?php	require $template .'/footer.php'; ?>