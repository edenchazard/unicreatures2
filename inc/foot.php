<?php
$welcome_html = '';
$extra_menu_html = '';
$perm_html = '';

//extra error handling that still makes the page show up even when there's been
//a db error
if(isset($user))
if(is_logged_in()){
	$extra_menu_html = "<li><a href='/logout'>Logout</a></li>";
	$welcome_html = "
			<div id='welcome'>
					Welcome, <a href='social/{$user->username}' class='user-{$user->userlevel['title']}'>{$user->username}</a>
					<a class='alerts_link' href='/alerts'>{$user->notifications} Alerts</a>
					<a class='missions_link' href='/missions'>5 Missions</a>
			</div>";

	if($user->userlevel['title'] == "Admin"){
		$extra_menu_html .= "<a href='/cp/enable'>Enable Admin Mode</a>";
	}
	
	if($user->has_perm('DEBUG')){
		$perm_html = "
		Done in ".(microtime(true)-$stopwatch)." seconds | <strong>Queries made:</strong> ". cfg::$db->querycount . ' (' . cfg::$db->totalquerytime . " seconds)
		<div id='db-statistics'>
			<a class='extra' href='#'>Details</a>
			<div id='db-statistics-extra' class='hidden'>";
				for($i = 0; $i < cfg::$db->querycount; ++$i){
					$perm_html .= '
					<div class="sql-box">' . nl2br(cfg::$db->queries[$i]) . '</div>';
				}

				foreach(cfg::$db->errors as $k => $v){
					$perm_html .= 
					"<br /><br />$v";
				}
		$perm_html .="
			</div>
		</div>";
	}
}
else{
	$extra_menu_html = "
			<li><a href='/login'>Login</a></li>
			<li><a href='/register'>Register</a></li>";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta charset='ISO10646' />
		<link href='/style/css.css' rel='stylesheet' />
		<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
		<title><?php echo $page->title; ?> - Unicreatures</title>
		<script type='text/javascript'>
			$(document).ready(function(){
				$('.extra').click(function(e){
					e.preventDefault();
					$('.hidden').slideToggle(500);
				});
			});
		</script>
	</head>
	<body>
		<div id='container'>
			<div id='header'>
				<?php echo $welcome_html; ?>
			</div>
				<div id='border'>
					<div id='menu'>
						<ul>
							<li>
								<a href='#' class='hover'>Harvest</a>
								<ul>
									<li><a href='#'>Buildings</a></li>
									<li><a href='#'>Technology</a></li>
									<li><a href='#'>Missions</a></li>
								</ul>
							</li>
							<li><a href='/profile'>My Profile</a></li>
							<li><a href='/trainer'>Caretaker Hut</a></li>
							<li><a href='#'>Explore</a></li>
							<li><a href='#'>New Atlantis Plaza</a></li>
							<li><a href='#'>Train</a></li>
							<li><a href='#'>Arena</a></li>
							<li><a href='#'>Donate</a></li>
							<?php echo $extra_menu_html; ?>
						</ul>
					</div>
					<div id='content'>
						<?php echo $page->html; ?>
					</div>
					<div id='footer'>
						<?php echo $perm_html; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>