					<div id='footer'>
<?php	if(is_logged_in()){
			if($user->has_perm('DEBUG')){ ?>
						Done in <?php _e(microtime(true)-$stopwatch) ?> seconds |
						<span class='b'>Queries made:</span> <?php _e(cfg::$db->querycount) ?> (<?php _e(cfg::$db->totalquerytime) ?> seconds)

						<div id='db-statistics'>
							<a class='extra' href='#' id='extra'>Details</a>
							<div id='db-statistics-extra' class='hidden'>
<?php			foreach(cfg::$db->queries as $q){ ?>
								<div class="sql-box" class='sql'><?php _e($q) ?></div>
<?php			}
				foreach(cfg::$db->errors as $error){
								_e($error->text);
				}
				?>
							</div>
						</div>
<?php		}
		} ?>
					</div>
				</div>
				<div id="footerend"></div>
			</div>
		</div>
	</body>
</html>
<?php ob_end_flush() ?>