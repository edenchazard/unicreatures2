<?php
require './inc/head.php';

if(!is_logged_in()) redirect_to_index();

$messages = array();
$gather_array = array();

//fetch a specific area
if(isset($_GET['area'])){
	if(safe_digit($_GET['area'])){
	
		$page->s = array(0, "");

		//check area exists
		if(!$area = cfg::$db->query("
			SELECT accomplishments, tree_components, material_components
			FROM explore_areas WHERE exploreID = {$_GET['area']}
			LIMIT 1")->fetch_assoc()){
			header("Location: /explore.php") and exit;
		}

		//check they have enough accomplishments
		//if($user->accomplishments >= $area['accomplishments']){
			$_SESSION["explore_enc"][] = $new_key = substr(md5(mt_rand(1,10000)), 1, 4);

			//keep logs for last 4 pages
			if(isset($_SESSION["explore_enc"][5]))
				array_shift($_SESSION['explore_enc']);

			if(isset($_GET["key"])) {
				if(in_array($_GET['key'], $_SESSION['explore_enc'])){
					header("Cache-Control: store, cache, must-revalidate, post-check=60, pre-check=60");
					header('Expires: ' . gmdate('D, d M Y H:i:s', time()+60) . ' GMT');
					header('Pragma: cache');
					header("Last-Modified: " . gmdate("D, j M Y H:i:s", time()-3600) . " GMT");
					header("Etag: ".substr(md5($_GET["area"]."cheese".$_GET["key"]), 0, 10));
					
					//we'll amalgamate the components given into one update
					$gather_columns = array();
					$gather_values = array();

					//gather - components
					if(isset($_GET['creature'])){
						if(safe_digit($_GET['creature'])){
							//fetch creature name
							$cared_for = cfg::$db->query("SELECT creature_name, component FROM creatures_db
														WHERE creatureID = {$_GET['creature']}
														LIMIT 1")
												->fetch_assoc();
							$messages[] = "<p class='center purple'>{$cared_for['creature_name']} is happy for your care!</p>";

							if(mt_rand(0, 2) == 1){
								//give component
								$component = new something('component', $cared_for['component']);
								$c_name = concise($cared_for['component']);
								$gather_columns[]	= $c_name;
								$gather_values[]	= "`$c_name` + 1";
								$gather_array[] = "<p class='center reddishpurple'><img src='".$component->image()."' /><br />You have gained +1 {$component->name}!</p>";
							}
						}
					}

					if(isset($_SESSION['found_egg']) && isset($_GET['found'])){
						$data = $_SESSION['found_egg'];
						//give the creature
						give_creature($user->userID, $data['creatureID'], $data);
						$messages[] = "<p class='center allow'>You have taken the {$data['family_name']} egg!</p>";
						unset($_SESSION['found_egg']);
					}

					//gather - coins
					if(mt_rand(1, 3) == 2){
						$coins = new something('coins', 0);
						$coins->give($coin_num = mt_rand(1, 3), $user->userID);
						$gather_array[] = "<p class='center reddishpurple'><img src='".$coins->image()."' /><br />You have gained +$coin_num {$coins->name}!</p>";
					}
					
					//gather - mystery box
					if(mt_rand(1, 220) == 42){
						$mb = new something('item', 'Mystery Box');
						$mb->give(1, $user->userID);
						$gather_array[] = "<p class='center reddishpurple'><img src='".$mb->image()."' /><br />You have gained +1 {$mb->name}!</p>";
					}

					//gather - rare component
					if(($chance = env::get('rare_component_chance')) > 0){
						if(mt_rand(0, $chance) == 1){
							$rares = get_sys_components('Rare Component');
							$rc = $rares[array_rand($rares)];
							$rc_obj = new something('component', $rc['name']);
							$c_name = concise($rc['name']);
							$gather_columns[]	= $c_name;
							$gather_values[]	= "`$c_name` + 1";
							$gather_array[] = "<p class='center reddishpurple'><img src='".$rc_obj->image()."' /><br />You have gained +1 {$rc_obj->name}!</p>";
						}
					}

					//gather - tree components
					//if($user->accomplishments >= 400){
						#make sure there IS tree components for this area
						if(!empty($area['tree_components'])){
							if(mt_rand(0, 42) == 1){
								$trees = explode('; ', $area['tree_components']);
								$tc = $trees[array_rand($trees)];
								$rc_obj = new something('component', $tc);
								$c_name = concise($tc);
								$gather_columns[]	= $c_name;
								$gather_values[]	= "`$c_name` + 1";
								$gather_array[] = "<p class='center reddishpurple'><img src='".$rc_obj->image()."' /><br />You have gained +1 {$rc_obj->name}!</p>";
							}
						}
					//}

					//gather - building materials
					#make sure there IS building components for this area
					if(!empty($area['material_components'])){
						if(mt_rand(0, env::get('gather_chance')) == 1){
							$builds = explode('; ', $area['material_components']);
							$bc = $builds[array_rand($builds)];
							$rc_obj = new something('component', $bc);
							$c_name = concise($bc);
							$gather_columns[]	= $c_name;
							$gather_values[]	= "`$c_name` + 1";
							$gather_array[] = "<p class='center reddishpurple'><img src='".$rc_obj->image()."' /><br />You have gained +1 {$rc_obj->name}!</p>";
						}
					}

					//found a creature
					if(mt_rand(0, 20) == 5){
						//I know, all this is somewhat dirty
						$gen = $user->generate_random_family_collection(1, $_GET['area']);
						$find = $gen[0];

						//find the creature's ID
						$fetch_id = cfg::$db->query("SELECT creatureID FROM creatures_db
													WHERE familyID = '{$find['familyID']}'
													AND stage = 1 LIMIT 1")
										->fetch_assoc();

						$find['creatureID'] = $fetch_id['creatureID'];
						$_SESSION['found_egg'] = $find;
						
						$msg_string = "<a href='/explore.php?area={$_GET['area']}&found=1&key=$new_key'>";
						$msg_string .= "<img src='".mkimg($find)."' />";
						$msg_string .= "<br />You've found a ".intspeciality2text($find['speciality'])." {$find['family_name']} egg nearby! Take it?</a>";
						$messages[] = "<p class='center allow'>$msg_string</p>";
					}
					//add all components
					cfg::$db->update('user_owned_components', $gather_columns,
										$gather_values, 'userID', $user->userID
									);
				}
				if(!empty($messages)){
					$page->s = array(1, implode('', $messages));
				}
			}
			//fetch a random story from area
			if(($story_IDs = apc_fetch('storyIDs-'.$_GET['area'])) === false){
				//not in cache
				$story_IDs = array();
				$stories_in_area = cfg::$db->query("SELECT ID FROM explore_stories
												WHERE exploreID = {$_GET['area']}");

				while($ID = $stories_in_area->fetch_assoc()){
					$story_IDs[] = $ID['ID'];
				}
				
				//1 hour cache
				apc_store('storyIDs-'.$_GET['area'], $story_IDs, 3600);
			}

			$story_id_to_fetch = $story_IDs[array_rand($story_IDs)];

			$story = cfg::$db->query("SELECT title, story, history, creature_1_ID,
									creature_1_option, creature_2_ID, creature_2_option,
									creature_3_ID, creature_3_option
									FROM explore_stories WHERE ID = '$story_id_to_fetch'
									LIMIT 1")
							->fetch_assoc();

			$story['story'] = nl2br(esc_html($story['story']));
			$story['history'] = nl2br(esc_html($story['history']));
			
			//fetch creature names for options
			$names = cfg::$db->query("	SELECT creatureID, creature_name FROM creatures_db
										WHERE creatureID IN(
											{$story['creature_1_ID']},
											{$story['creature_2_ID']},
											{$story['creature_3_ID']})
										LIMIT 3
									");
									
			$derp = array(	'creature_1_ID' => $story['creature_1_ID'],
							'creature_2_ID' => $story['creature_2_ID'],
							'creature_3_ID' => $story['creature_3_ID'],
			);
			while($name = $names->fetch_assoc()){
				$key = array_search($name['creatureID'], $derp);
				$derp[$key] = array($derp[$key], $name['creature_name']);
				//$derp['creature
			}
			$story = array_merge($story, $derp);
			for($i = 1; $i < 4; ++$i){
				$col = 'creature_'.$i.'_option';
				$story[$col] = str_replace('*', $story['creature_'.$i.'_ID'][1], $story[$col]);
			}
		//}
	/* 	else{
			$page->s = array(3, "<p class='center deny'>You do not have enough accomplishments to access this area!</p>");
		} */
	}
}

//fetch all areas
else{
	$areas = cfg::$db->query("SELECT exploreID, name, accomplishments, description
							FROM explore_areas");
	$page->s = array(2, "");
}

require $template .$pagename;
?>