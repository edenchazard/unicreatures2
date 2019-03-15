<?php
/*
 * System for handling a user
 * by 42 v.1
 */

class user_management {
	public function create_tables(){
		cfg::$db->query("
			--
			-- Table structure for table `users`
			--

			CREATE TABLE IF NOT EXISTS `users` (
				`userID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
				`username` varchar(20) NOT NULL,
				`password` char(64) NOT NULL,
				PRIMARY KEY (`username`),
				UNIQUE KEY `userID` (`userID`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
		");
		
		cfg::$db->query("
			--
			-- Table structure for table `users_ranks`
			--

			CREATE TABLE IF NOT EXISTS `users_ranks` (
			`rank` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
			`title` varchar(15) NOT NULL,
			`permissions` varchar(100) NOT NULL,
			PRIMARY KEY (`rank`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
		");
	}

	public function is($title){
		return (strtolower($this->user_title) === strtolower($title));
	}

	public function match($selector){
		$x = substr($selector, 0, 1);
		
		//#<id>
		if($x === '#')
			return 'id search';
		else
			return 'username search';
	}
	/* 
	binds user data to a user object based on a user id ( #id )
	or a username
	*/
	public function bind_data($selector, $extra_columns = null){
		$selector = escape($selector); $field_syntax = '';

		$fields_to_fetch = array('userID', 'username', 'password');
	
		if(is_array($extra_columns))
			$fields_to_fetch = array_merge($fields_to_fetch, $extra_columns);

		foreach($fields_to_fetch as $field){
			$field_syntax .= "`$field`, ";
		}

		if($this->match($selector) === 'id search')
			$where = "WHERE `userID` = '".substr($selector, 1, strlen($selector))."'";
		else
			$where = "WHERE `username` = '$selector'";

		//set user data to use throughout the script
		$result = cfg::$db->query("SELECT ".substr($field_syntax, 0, -2)." FROM `users` $where LIMIT 1");

		if($data = $result->fetch_assoc()){

			foreach($data as $key => $value){
				$this->$key = $value;
			}
			//rank data
			//not in cache
			$rank = apc_fetch("rank_{$this->rank}");
			if($rank === false){
				$result = cfg::$db->query("
					SELECT	`title`,
							`permissions`
					FROM `users_ranks`
					WHERE `rank`='{$this->rank}'
					LIMIT 1");

				if(!$row = $result->fetch_assoc()){
					throw new Exception("Rank level {$this->rank} does not appear to be in the database.");
				}

				$rank = array();

				//has multiple permissions?
				if(strrpos($row['permissions'], '; ') > 0)
					$rank['permissions'] = explode('; ', $row['permissions']);
				else
					$rank['permissions'] = array(0	=>	$row['permissions']);

				$rank['title'] = $row['title'];
				apc_store("rank_{$this->rank}", $rank, 3600);
				
			}
			$this->user_title = $rank['title'];
			$this->permissions = $rank['permissions'];
			return "good:User has been binded.";
		}
		//wasn't found
		else
			return "bad:Couldn't find the user. Search criteria was $selector";
	}

	public function login($selector, $password){
		//check isn't already logged in
		if($this->bind_data($selector)){
		
			//check not banned
			if(strtolower($this->user_title === 'banned'))
				return "bad:This user is banned and hence not allowed to log in.";

			if($this->password === hash('sha256', $password)){
				$_SESSION['userid'] = $this->userID;
				return "good:You have successfully logged in!";
			}
		}
		return "bad:Invalid login information.";
	}

	public function logout(){
		if(is_logged_in()){
			session_destroy();
			return true;
		}
		
		return false;
	}

	public function has_perm($perm){
		if(is_logged_in()){
			$perms = $this->permissions;

			//perm ALL overrides all
			if(in_array('ALL', $perms))
				return true;

			return (in_array($perm, $perms));
		}

		return false;
	}

	public function change_password($new_password){
		
	}

	public function change_username($new_username){
		if($this->username == $new_username)
			return "bad:That already is your username.";

		$new_username = escape($new_username);

		if(acceptable_username($new_username)){
			$res = cfg::$db->query("UPDATE users SET username = '$new_username' WHERE userID = {$this->userID} LIMIT 1");
			if($res === MYSQL_E_DUPE_KEY){
				return "bad:Please check that username isn't already in use.";
			}
			else return "good:You have successfully changed your username.";
		}
		else return "bad:Sorry, that username is unacceptable. Usernames can only consist of
					alphanumeric characters and must be between 4 and 20 characters.";
	}
}










/* 
 * adaptation of user_management class API for UC
 */
class user extends user_management{
	private $inventory_arr = array();

	public function bind_data($find){
		//overload the bind_data with the extra columns we need
		return parent::bind_data($find, 
			array('subclass', 'last_noble', 'last_exalted', 'rank', 'notifications', 'coins',
				  'accomplishments', 'template',
				  'last_train_time', 'last_train_energy', 'add_train_energy',
				  'wild_sort', 'email'
			)
		);
	}

	public function register_guest_account($password){
		$password_hash = hash('sha256', $password);
		$date_time = date("Y-m-d H:i:s");
		$ret = false;

		try{
			cfg::$db->autocommit(false);
			cfg::$db->query("INSERT INTO `users`
							(`password`, `subclass`, `last_noble`, `last_exalted`, `rank`)
							VALUES
							('$password_hash', '', '$date_time', '$date_time', '5')");

			//update username with guest ID
			$user_id = cfg::$db->insert_id;
			$username = "Guest $user_id";
			cfg::$db->query("UPDATE `users` SET `username`='$username'
							WHERE `userID`='$user_id' LIMIT 1");
							
			//insert inventory
			cfg::$db->query("INSERT INTO user_owned_items (userID) VALUES ('$user_id')");
			cfg::$db->query("INSERT INTO user_owned_components (userID) VALUES ('$user_id')");
			cfg::$db->commit();
			$ret = $user_id;
			cfg::$db->autocommit(true);
		}
		catch (sql_error $e){
			cfg::$db->rollback();
			cfg::$db->autocommit(true);
			throw new Exception("There was a problem creating the guest account.");
		}

		return $ret;
	}

	public function create_area($type, $level = 1){
		if(!safe_digit($level))
			throw new Exception("create_area() expects level to be a positive int.");

		$ret = false;

		//check type exists and fetch data
		if(!$area_info = cfg::$db->query("
			SELECT	`cost`,
					`type`
			FROM `areas_db`
			WHERE	`type`	= '$type'
			AND	`level`	= '$level'
			LIMIT 1")->fetch_assoc()){
			throw new Exception("Area doesn't exist: '$type'.");
		}

		//check they have ample coinage
		if($this->coins >= $area_info['cost']){
			try {
				cfg::$db->autocommit(false);
				cfg::$db->query("
					UPDATE `users`
					SET `coins`		 = `coins`-'{$area_info['cost']}'
					WHERE userID	 = '{$this->userID}'
					LIMIT 1");
				cfg::$db->query("
					INSERT INTO `areas_owned`
					(`username`, `name`, `type`)
					VALUES
					('{$this->username}', '{$area_info['type']}', '{$area_info['type']}')");
				cfg::$db->commit();
				$ret = array(2, cfg::$db->insert_id);
			}
			catch (sql_error $e){
				cfg::$db->rollback();
				throw new Exception("Sorry, a database error has occurred.");
			}
		}
		else{
			throw new Exception("Not enough coins.");;
		}

		cfg::$db->autocommit(true);
		return $ret;
	}

	/*
	 * function for transfering an exotic credit to a user
	 */
	public function transfer_credit(){
		
	}

	/*  useful for things like basket or explore. includes nobles/exalteds
	 * & variations. $quantity is number of families to return
	 */
	public function generate_random_family_collection($quantity, $area = 'basket'){
		$to_rand = array();
		$curdate = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j'), 1970));
	
		//select all families in approp. area, and within date requirements
		if($area == 'basket'){
			$result = cfg::$db->query("
				SELECT 	fa.`familyID`, fa.`family_name`, fa.`has_varieties`, fa.`rarity`, fa.`deny_ne`
				FROM creatures_families AS fa
				WHERE fa.in_basket = 1
				AND ".date('Y')." <= fa.every_year_until
				AND (('{$curdate}' BETWEEN fa.dbegin AND fa.dend) OR fa.dbegin = fa.dend)");
		}
		else{
			$result = cfg::$db->query("
				SELECT e.familyID, fa.`family_name`, fa.rarity, fa.`deny_ne`, e.varieties,
					fa.has_varieties
				FROM explore_creatures AS e
				LEFT JOIN creatures_families AS fa ON e.familyID = fa.familyID
				WHERE e.exploreID = {$area}
				AND ".date('Y')." <= fa.every_year_until
				AND (('{$curdate}' BETWEEN fa.dbegin AND fa.dend) OR fa.dbegin = fa.dend)");
		}

		//file family info into appropriate tier
		while($row = $result->fetch_assoc()){
			//what we'll add to our mass array
			$insert = array(	'familyID' => $row['familyID'], 'family_name' => $row['family_name'],
								'deny_ne'	=> $row['deny_ne'], 'has_varieties' => $row['has_varieties']
							);
			
			if(!empty($row['varieties'])){
				if(strrpos($row['varieties'], '; ') > 0)
					$insert['varieties'] = explode('; ', $row['varieties']);
				else
					$insert['varieties'] = array(0	=>	$row['varieties']);
			}
			$to_rand[$row['rarity']][] = $insert;
		}

		$timenow = time(); $collection = array();

		for($i = 0; $i < $quantity; ++$i){

			$family = rand_family($to_rand);

			$creature = array(
				'familyID'		=>	$family['familyID'],
				'family_name'	=>	$family['family_name'],
				'speciality' 	=>	0
			);

			//check if we're enforcing a particular selection of varieties
			
			//in this case, we're not
			if(empty($family['varieties'])){
				if($family['has_varieties']){
					//if over 0, pick a variety, otherwise, use default
					if(mt_rand(0, 1) > 0){
						//count enabled varieties
						$n_varieties = cfg::$db->query("
							SELECT COUNT(*) AS varieties
							FROM creatures_families_varieties
							WHERE familyID = '{$family['familyID']}'
							AND enabled = 1")->fetch_assoc();

						$row = cfg::$db->query("
							SELECT variety FROM creatures_families_varieties
							WHERE familyID = '{$family['familyID']}' AND enabled = 1
							LIMIT ".mt_rand(0, $n_varieties['varieties']).", 1")->fetch_assoc();

						$creature['variety'] = $row['variety'];
					}
				}
			}
			//but here, we are
			else{
				//so randomize what it is
				$creature['variety'] = $family['varieties'][array_rand($family['varieties'])];
			}

			if(!$family['deny_ne']){
				//chance of noble
				//enough time passed
				if(($timenow - strtotime($this->last_noble .' UTC')) >= env::get('noble_min_time_pass')
					&& mt_rand(0, env::get('noble_chance')) == 42){
					//randomizer says yes
					//check if user is allowed to have a noble of this creature (full)
					$check = cfg::$db->query("SELECT 1 FROM `creatures_owned_complete`
											WHERE userID = {$this->userID}
											AND familyID = {$family['familyID']} LIMIT 1")
									->fetch_assoc();
					if($check){
						$creature['speciality'] = 1;
					}
				}
				//chance of exalted
				else if(($timenow - strtotime($this->last_noble .' UTC')) >= env::get('exalted_min_time_pass')
					&& mt_rand(0, env::get('exalted_chance')) == 12){
					//check if user is allowed to have an exalted of this creature (full)
					$check = cfg::$db->query("SELECT 1 FROM `creatures_owned_complete`
											WHERE userID = {$this->userID}
											AND familyID = {$family['familyID']} LIMIT 1")
									->fetch_assoc();
					if($check){
						$creature['speciality'] = 2;
					}
				}
			}

			$collection[] = $creature;
		}
		return $collection;
	}



	public function calculate_energy($which){
		$ret = array();

		if($which === 'training'){
			$total_max = env::get('train_energy')+$this->add_train_energy;
			
			$then = strtotime($this->last_train_time .' UTC');
			$difference = time()-$then;
			//we can calculate the number of regenerations since  our
			//last timestamp (basically, intervals)
			$regens = floor($difference/80)-$this->last_train_energy;
		}
		if($regens > $total_max)
			$ret['current'] = $total_max;
		else
			$ret['current'] = $regens;

		$ret['max'] = $total_max;
			
		return $ret;
	}

	/* if a creature obj type is given, this function will detect that and
	 * instead return the owns_of_creature($id) function value
	 * for it
	 * if a string is given, the function will try to return
	 * it as if it was an item or component
	*/
	public function inventory($arg = null){
		if(empty($this->inventory_arr)
			|| is_null($arg)
			){
			//fetch what user has - we'll get what columns we need from our
			//cached items/components arrays
			$user_inventory = cfg::$db->query("
			SELECT	".implode(', ', array_keys(get_sys_components())).", "
					.implode(', ', array_keys(get_sys_items()))." FROM user_owned_components
			LEFT JOIN user_owned_items ON user_owned_components.userID = user_owned_items.userID
			WHERE user_owned_components.userID = {$this->userID}
			LIMIT 1")->fetch_assoc();

			// (coins)
			$user_inventory['coins'] = $this->coins;

			//get ECs
			$res = cfg::$db->query("SELECT COUNT(*) AS num FROM exotic_credits
									WHERE sent_to_userID = {$this->userID} AND spent_id = 0")
							->fetch_assoc();

			$user_inventory['exotic_credit'] = $res['num'];

			$this->inventory_arr = $user_inventory;
		}

		if(is_object($arg)){
			if($arg->type === 'creature'){
				return $this->owns_of_creature($arg->id);
			}
			else{
				return intval($this->inventory_arr[$arg->concise]);
			}
		}
		elseif(is_string($arg)){
			return $this->inventory_arr[concise($arg)];
		}
	}

	public function owns_of_creature($creatureID){
		if(!safe_digit($creatureID))
			throw new Exception("owns_of_creature() expects parameter 1 to be a safe digit");

		$fetch = cfg::$db->query("SELECT COUNT(*) AS number FROM creatures_owned
								WHERE creatureID = $creatureID
								AND userID = {$this->userID}
								AND frozen = 0")->fetch_assoc();
		return $fetch['number'];
	}
}
?>