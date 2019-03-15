<?php require $template .'/header.php'; ?>
				<div id='usercp'>
<?php	foreach($links as $category => $options): ?>
					<div class='usercp-category'>
						<div class='usercp-category-title'><?php _e($category) ?></div>
<?php		foreach($options as $data): ?>
						<div class='usercp-category-action'>
							<img src='<?php _e($data[2]) ?>' /><br />
							<a href='<?php _e($data[1]) ?>'><?php _e($data[0]) ?></a>
						</div>
<?php		endforeach ?>
					</div>
<?php	endforeach ?>
				</div>
<?php require $template .'/footer.php'; ?>