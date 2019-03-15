<?php //ob_start("ob_gzhandler") ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO10646">
		<link href='/templates/default/css/css.css' rel='stylesheet' />
		<link href='http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' rel="stylesheet" type="text/css" />
		<title><?=$page->title ?></title>
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js'></script>
		<script type='text/javascript'>
			//jQ didn't load, possibly down? load a local copy
			if(!jQuery){
				var j = document.createElement('script');
				j.src = "/js/jquery-1-10-2.min.js";
				document.head.appendChild(j);
			}
		</script>
		<script type='text/javascript' src='http://code.jquery.com/ui/1.10.3/jquery-ui.js'></script>
		<script type='text/javascript' src='/js/lib.js'></script>
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
				<? if(is_logged_in()) : ?>
				<div id='welcome'>
					<div id='welcome-welcome'>Welcome,</div>
					<div>
						<a href='/profile.php' class='user-<?=$user->user_title ?>'><?=$user->username ?></a>
					</div>
					<div title='notifications' id='welcome-alerts'><?=$user->notifications ?></div>
					<div title='missions' id='welcome-missions'>5</div>
					<div title='campfire' id='welcome-campfire'>0</div>
				</div>
				<? endif ?>
			</div>
			<div id='border'>
				<div id='menu'>
					<ul>
						<? if(is_logged_in()) : ?>
						<li><a href='/harvest.php'>Harvest</a></li>
						<li><a href='/profile.php'>My Profile</a></li>
						<li><a href='/trainer.php'>Caretaker Hut</a></li>
						<li><a href='/explore.php'>Explore</a></li>
						<li><a href='/transmute.php'>New Atlantis Plaza</a></li>
						<li><a href='/training.php'>Train</a></li>
						<li><a href='/arena.php'>Arena</a></li>
						<li><a href='/donate.php'>Donate</a></li>
						<li><a href='/logout.php'>Logout</a></li>
						<li><a href='/usercp.php'>Control Panel</a></li>
						<? else : ?>
						<li><a href='/login.php'>Login</a></li>
						<li><a href='/register.php'>Register</a></li>
						<? endif ?>
					</ul>
				</div>
				<div id='content'>