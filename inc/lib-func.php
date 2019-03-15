<?php
/*****
	USEFUL FUNCTIONS
	*******/
/* short, helper, concise functions */
function get_profile_username(){ return env::get('profile_user_obj')->username; }
function _e($str){ echo $str; }
function is_logged_in(){ return (isset($_SESSION['userid'])); }
function redirect_to_index(){ header('Location: /'); exit; }
function calc_powers($n){ return min(100, ceil(pow($n, 0.82))); }
function concise($string){ return str_replace(array(' ', '\'', '-'), array('_', '', '_'), strtolower($string)); }
function isbetween($n, $l, $x){ return ($n >= $l && $n <= $x); }
function esc_html($string){ return nl2br(htmlspecialchars($string, ENT_QUOTES)); }
function determine_rarity($int){ return (isset(cfg::$rarities[$int]) ? cfg::$rarities[$int][0] : "Unknown"); }
function acceptable_username($string){ return preg_match("/^[a-z0-9]{4,20}$/i", $string, $m); }
function link_to_area($areaID){return ($areaID == 0 ? "/area.php?wild=" : ""); }
function get_response_msg($what){ return substr($what, strpos($what, ':'), strlen($what)); }
function was_successful($what){ return (substr($what, 0, 4) == 'bad:'); }
function array_surround_concat(&$item, $key, $surround_with){ $item = $surround_with.$item.$surround_with; }

/* filters */
function add_filter($to, $whatdo){ cfg::$filters[$to][] = $whatdo; }
function prepend_filter($to, $whatdo){ array_unshift(cfg::$filters[$to], $whatdo); }
function apply_filters($for, $process_on){
	foreach(cfg::$filters[$for] as $filter){
		$process_on = $filter($process_on);
	}
	return $process_on;
}



//credit to  farooqym at ieee dot org http://php.net/manual/en/function.array-rand.php
function array_rand_weighted($values){
    $r = mt_rand(1, array_sum($values));
    foreach ($values as $item => $weight) {
        if  ($r <= $weight) return $item;
        $r -= $weight;
    }
}

/*
 * return a random family name based on rarity
 * takes into account the levels of rarity
 */
function rand_family($system){
	if(empty($system)) return;

	$base = array();

	foreach(cfg::$rarities as $key => $value){
		if(isset($system[$key])){
			$base[$key] = cfg::$rarities[$key][1];
		}
	}

	$set = $system[array_rand_weighted($base)];

	$max = count($set);

	if($max > 0) $max -= 1;

	return $set[mt_rand(0, $max)];
}

function make_user_from_query_string(){
	$profile_user = new user();
	/*$profile_user->bind_data('#1');
	return $profile_user;
	
	
	//temp ^^*/
	if(isset($_GET['id'])){
		if(safe_digit($_GET['id'])){
			if(is_logged_in()){
				if($_GET['id'] == env::get('current_user')->userID){
					return env::get('current_user');
				}
			}
			$valid = $profile_user->bind_data('#' . $_GET['id']);
			return (was_successful($valid) ? $profile_user : null);
		}
	}
	else if(isset($_GET['username'])){
		if(is_logged_in()){
			if($_GET['username'] == env::get('current_user')->username){
				return env::get('current_user');
			}
		}
		$valid = $profile_user->bind_data($_GET['username']);
		return (was_successful($valid) ? $profile_user : null);
	}
	else if(isset($_GET['wild'])){
		if(is_logged_in()){
			if($_GET['wild'] == env::get('current_user')->username){
				return env::get('current_user');
			}
		}
		$valid = $profile_user->bind_data($_GET['wild']);
		return (was_successful($valid) ? $profile_user : null);
	}
	else if(is_logged_in()){
		return env::get('current_user');
	}
	
	return null;
}

function get_max_skills($creatureID, $speciality){
	$skill_list = implode(', ', cfg::$general_skills);

	//max skills
	$max_skills = cfg::$db->query("
		SELECT $skill_list FROM training_max_skills
		WHERE creatureID = '{$creatureID}' LIMIT 1")
						->fetch_assoc();

	//take into account speciality
	//print_r($max_skills);
	if($speciality == 1){
		foreach($max_skills as $key => $value){
			$max_skills[$key] = manipulate($value, '+12%');
		}
	}
	elseif($speciality == 2){
		foreach($max_skills as $key => $value){
			$max_skills[$key] = manipulate($value, '+18%');
		}
	}

	return $max_skills;
}

function manipulate($orig_value, $by){
	$by = trim($by);
	$orig_value = intval($orig_value);
	$ispercent = false;
	
	//methods can be: +, -, =
	$method = substr($by, 0, 1);

	//check if percentage
	if(substr($by, -1, 1) === '%')
		$ispercent = true;

	//get number on its own
	$by = intval($by);

	switch($method){
		case '-':
			if($ispercent)
				return ($orig_value - floor(($orig_value / 100) * $by));
			else
				return $orig_value -= $by;
		case '+':
			if($ispercent)
				return ($orig_value + floor(($orig_value / 100) * $by));
			else
				return ($orig_value + $by);
	}
	return $orig_value;
}


/* cached for 1 hour
 *
 */
function get_sys_components($group = null){
	if(($standard = apc_fetch('components')) === false){
		$result = cfg::$db->query("SELECT name, `type` FROM components");
		$standard = array();
		while($component = $result->fetch_assoc()){
			$standard[concise($component['name'])] = array(
														'name'  => $component['name'],
														'group' => $component['type']
														);
		}
		apc_store("components", $standard, 3600);
	}
	
	//filter by group
	if(is_string($group)){
		$ret = array();
		foreach($standard as $key => $val){
			if($val['group'] === $group)
				$ret[$key] = $val;
		}
		return $ret;
	}
	return $standard;
}

/* cached for 1 hour
 *
 */
function get_sys_items(){
	if(($standard = apc_fetch('items')) === false){
		$result = cfg::$db->query("SELECT name, description FROM items");
		$standard = array();
		while($item = $result->fetch_assoc()){
			$standard[concise($item['name'])] = array(
												'name' => $item['name'],
												'desc' => $item['description']
												);
		}
		apc_store("items", $standard, 3600);
	}

	return $standard;
}


/* recursively sanitizes an array or string, making it safe for
 * DB insertion. It also trims leading and treading whitespace.
 */
function escape($arg){
	if(is_array($arg)){
		foreach($arg as $key => $value){
			$arg[$key] = escape($value);
		}
		return $arg;
	}
	else{
		return trim(cfg::$db->real_escape_string($arg));
	}
}


/*
 * support for multiple argument tests, like isset(xxx, xxx, etc)
 */
function safe_digit(){
	$argcount = func_num_args();
	for($i = 0; $i < $argcount; ++$i){
		$arg = func_get_arg($i);

		if(!ctype_digit((string)$arg)){
			return false;
		}
	}
	return true;
}

//crude function that parses strings with template tags
function parse_template($res, $what){
	$tags = array();

	if(isset($res['creature_name']))
		$tags['{{C:NAME}}'] = $res['creature_name'];
	if(isset($res['family_name']))
		$tags['{{C:FAMILY}}'] = $res['family_name'];
	if(isset($res['nickname']) || isset($res['creature_name']))
		$tags['{{C:NICKNAME}}'] = (isset($res['nickname']) ? $res['nickname'] : $res['creature_name']);
	if(isset($res['gender'])){
		//if($$res['gender'] == 0){
			$tags['#His'] = 'His';
			$tags['#Hers'] = 'Hers';
			$tags['#his'] = 'his';
			$tags['#hers'] = 'hers';
			$tags['#Him'] = 'Him';
			$tags['#He'] = 'He';
			$tags['#Her'] = 'Her';
			$tags['#he'] = 'he';
			$tags['#her'] = 'her';
			$tags['#him'] = 'him';
		//}
	}

	if(!empty($tags)){
		foreach($tags as $tag => $with){
			$what = str_replace($tag, $with, $what);
		}
	}

	return esc_html($what);
}

function intspeciality2text($speciality){
	if(!isset(cfg::$specialities[$speciality]))
		throw new Exception("Invalid speciality: {$speciality}");
	
	return cfg::$specialities[$speciality];
}

/*
 * Translates any string or int into the respective gender,
 * returns the gender in a text format (i.e male, female)
 *
 * @param mixed $gender The gender to translate
 * @return strng
 *
 */
function gender2text($gender){
	if(safe_digit($gender)){
		if(isset(cfg::$genders[$gender]))
			return cfg::$genders[$gender];
	}
	elseif(is_string($gender)){
		$l = strtolower($gender);

		if(in_array($l, cfg::$genders)){
			return $l;
		}
	}

	throw new Exception('Gender not recognized: '.$gender);
}


/*
 * Translates any string or int into the respective gender
 *
 * @param mixed $gender The gender to translate
 * @return int
 *
 */
function gender($gender){
	if(safe_digit($gender)){
		if(isset(cfg::$genders[$gender]))
			return $gender;
	}
	elseif(is_string($gender)){
		$l = strtolower($gender);
		$g = array_search($gender, cfg::$genders[$gender]);
		if($g !== false){
			return $g;
		}
	}

	throw new Exception('Gender not recognized: '.$gender);
}


/*
 * Makes an image string for a creature.
 *
 * @param array $creature The creature to base return on
 * @return string
 *
 */
function mkimg($creature){
	if(isset($creature['creatureID'])){
		$extra = '';
		
		if(!empty($creature['variety']))
			$extra = ('_'. $creature['variety']);

		elseif(!empty($creature['speciality']))
			switch($creature['speciality']){
				case 1: $extra = '_noble'; break;
				case 2: $extra = '_exalted'; break;
		}
		return "/images/creatures/".$creature['creatureID'] . $extra . '.png';
	}
}

function rewardsstring2array($string){
	$matches = array();

	$ret = array(
		'formatted'		=>	'',
		'rewards'		=>	array()
	);

	$count = preg_match_all("#\+([0-9]+) ([a-zA-Z]+)#i", $string, $matches);

	for($i = 0; $i < $count; ++$i){
		$data = array(
			'attribute'	=>	strtolower($matches[2][$i]),
			'amount'	=>	$matches[1][$i]
		);

		if(in_array($data['attribute'], cfg::$general_skills)){
			$data['type'] = 'skill';
			$ret['formatted'] .= "+{$data['amount']} {$data['attribute']} ";
		}

		else{
			$ret['formatted'] .= "<span class='b'>+{$data['amount']} {$data['attribute']}</span> ";
			if ($data['attribute'] === 'Coins'){
				$data['type'] = 'coins';
				$data['name'] = 'Coins';
			}
			else
				$data['type'] = 'power';
		}

		$ret['rewards'][] = $data;
	}
	return $ret;
}
/*
 * returns 4 random seeded components for a creature ID
 *
 * @param int $id The creature ID to randomize
 * @return array
 *
 */
function seed_components($id){
	# Prepare a Seed based on Creature Type, so that components are consistent
	# each time you look at that creature type.
	srand($id);
	$chunks = array_chunk(array_keys(get_sys_components('Standard')), 4);
	return	array(
				$chunks[0][array_rand($chunks[0])],
				$chunks[1][array_rand($chunks[1])],
				$chunks[2][array_rand($chunks[2])],
				$chunks[3][array_rand($chunks[3])]
			);
}

function calculate_leaving($remaining, $years_until){
	#tags
	$day_tag = $prefix_tag = '';


	#y diff
	$year_diff = ($years_until - date('Y'));

	//if leaving date is this year, it's probably retiring
	if($year_diff <= 0) $prefix_tag = 'Retires ';
	//otherwise, it's returning next year
	else				$prefix_tag = 'Leaves ';

	if($remaining > 0) 		$day_tag = 'in '.$remaining.' days';
	elseif($remaining == 0) $day_tag = 'in '.date('G \h\o\u\r\s, i \m\i\n\u\t\e\s', (mktime(0, 0, 0, 0, date('j')) - time()));

	if($day_tag && $prefix_tag) return "{$prefix_tag} {$day_tag}!";
	
	//default to blank
	return '';
}

/*
* return value: the insert id of the last creature given in function
* $override is for overriding parameters
*/
function give_creature($userID, $creatureID, $override = array(), $amount = 1){
	if($amount > 1000)
		throw new Exception("give_creature() can only give a maximum amount of 1000 creatures. Asked for: {$amount}");

	if(!$creature_db = cfg::$db->query("
		SELECT creature_name, gender_only FROM creatures_db
		LEFT JOIN creatures_families USING(familyID)
		WHERE creatureID = '{$creatureID}'  LIMIT 1")->fetch_assoc())
			throw new Exception("Creature not found: {$creatureID}");

	//default values are set, and will be changed to
	//accommodate overrides
	//mysql will handle uninputted defaults, too
	//only accept these overrides, any overrides that
	//don't fit this list will just be skipped
	//also merge what we're going to insert
	$to_insert = array_merge(
							array(
								"creatureID"	=>	$creatureID,
								"userID"		=>	$userID,
								"collected_at"	=>	date("Y-m-d H:i:s"),
								"nickname"		=>	$creature_db['creature_name']
							),
							array_filter($override,
										function($v){
											return (in_array($v, array('speciality', 'variety', 'nickname',
																		'gender', 'frozen')));
										}
							)
	);

	if(!isset($to_insert['gender'])){
		$gender = gender($creature_db['gender_only']);
		$to_insert['gender'] = ($gender == 'decide' ? null : $gender);
	}

	//amount
	for($i = 0; $i < $amount; ++$i){
		$data[$i] = $to_insert;

		//gender for each one
		if($to_insert['gender'] == null){
			$data[$i]['gender'] = mt_rand(0, 1);
		}
	}

	cfg::$db->autocommit(false);
	cfg::$db->batch_insert('creatures_owned', array_keys($to_insert), $data);

	//adoptable ID
	$id = cfg::$db->insert_id;

	//if this was a noble or exalted, also update user row with new info
	if(isset($to_insert['speciality'])){
		if(isbetween($to_insert['speciality'], 1, 2)){
			$field = 'last_'.intspeciality2text($to_insert['speciality']);
			cfg::$db->query("UPDATE `users`
							SET ".$field." = '".date("Y-m-d H:i:s")."'
							WHERE `userID` = {$userID}
							LIMIT 1
			");
		}
	}

	cfg::$db->autocommit(true);
	return $id;
}

function draw_fancy_table_rows($array, $new_row_every, $callback){
	$i = 0;
	echo '<tr>';
	//code to handle sql result objects
	if(is_object($array)){
		//echo 'ha';
		while($value = $array->fetch_assoc()){
			echo '<td>'.$callback($value).'</td>';

			++$i;
			if($i % $new_row_every == 0){
				echo "</tr>";
				$i = 0;
			}
		}
	}
	else{
		foreach($array as &$value){
			echo '<td>'.$callback($value).'</td>';
			++$i;
			if($i % $new_row_every == 0){
				echo "</tr>";
				$i = 0;
			}
		}
	}
}

function z_date($format){
	if(strtolower($format) === 'mysql'){
		$format = 'Y-m-d H:i:s';
	}

	if(func_get_arg(2)){
		if(strtolower(func_get_arg(2)) === 'now'){
			$time = time();
		}
	}
	else{
		$time = time();
	}

	return date($format, $time);
}


function make_link($for){
	$for = strtolower(func_get_arg(0));
	$arg_count = func_num_args()-1;
	switch($for){
		case 'basket':
			$str = "/basket.php?";
	}
	
	for($i = 1; $i < $arg_count; $i+=2){
		$str .= '&'.func_get_arg($i) . '=' . func_get_arg($i+1);
		if($i >= 10){ break; }
	}

	return $str;
}
?>