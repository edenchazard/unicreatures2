<?php
$stopwatch = microtime(true);


/* app constants */
//turn dev mode on or off
define('DEV_MODE', 1);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('FRM_MAIN', true);


date_default_timezone_set('UTC');

session_start();


require ROOT.'/inc/db.php';
require ROOT.'/inc/environment.class.php';

/* configuration */
class cfg {
	public static $filters = array();
	public static $db;

	public static $genders = array(
		0 => 'male',
		1 => 'female',
		2 => 'dual',	//(literal dual gender)
		3 => 'decide'	//use this if you want to let the system decide
						//between male or female
	);

	public static $rarities = array(
		13 =>  array('WTFBBQ!?', 0),
		12 =>  array('WTF?', 0),
		11 => array('seriously?', 0),
		10 => array('exotic', 1),
		9 => array('epic race', 0),
		8 => array('exclusive', 5),
		7 => array('rare', 2),
		6 => array('limited', 4),
		5 => array('scarce', 6),
		4 => array('uncommon', 10),
		3 => array('common', 20),
		2 => array('plentiful', 26),
		1 => array('abundant', 32)
	);

	public static $general_skills = array(
		'strength', 'agility', 'speed', 'intelligence', 'wisdom', 'charisma',
		'creativity', 'willpower', 'focus'
	);

	public static $specialities = array(
		0 => '', //regular
		1 => 'noble',
		2 => 'exalted',
		3 => 'exotic',
		4 => 'legendary'
	);
}

cfg::$db = new exMysqli(
		'localhost', 'user', 'Kangaro0Penguins', 'unicreatures',
		array(MYSQL_E_DUPE_KEY),
		function(){
			echo "
			<div style='color:#000; margin: auto auto; background-color:#d1edff; width:200px; padding:5px; height:200px; vertical-align:middle; font:arial; font-size:12px; text-align:center'>
			Sorry, but there has been an error connecting to the database.
			</div>";
			exit;
		},
		function(){
			echo "<div style='margin: 0 auto; width:200px'><h1>Oops!</h1>Sorry, SQL error. <br /><br />Technical details have been logged.";
			exit;
		}
);


require ROOT.'/inc/lib-func.php';
require ROOT.'/inc/matters.class.php';
require ROOT.'/inc/api.php';
require ROOT.'/inc/user.class.php';

//new user obj for active user (or, if not logged in, guest)
$user = new user();

$template = 'default';

if(is_logged_in()){
	$user->bind_data('#'.$_SESSION['userid']);
	env::set('current_user', $user);
	if($user->template == 1){
		$template = 'mobile';
	}
}

require ROOT.'/inc/vars.php';

$pagename = substr($_SERVER['SCRIPT_NAME'], 1, strlen($_SERVER['SCRIPT_NAME'])-5).'.php';
$page = new stdClass;
$page->s = array();
$template = './templates/default/';