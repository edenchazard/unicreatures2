<?php
/*
 * Handles items, components, creatures, exotic credits, etc
 */
class something{
	public $type, $name, $concise;

	public function __construct($type, $idorname){
		$this->type = $type;
		if($type == 'component'){
			//get list of components in system
			$sys_components = get_sys_components();

			$this->concise = concise($idorname);

			//check is a valid component
			if(!isset($sys_components[$this->concise])) { }
				//throw new Exception("$name is not a valid item name");

			//okay.
			$this->name = $sys_components[$this->concise]['name'];
		}
		else if($type == 'item'){
			$name = concise($idorname);
			//get list of components in system
			$sys_items = get_sys_items();

			//check is a valid component
			if(!isset($sys_items[$name])) { }
				//throw new Exception("$name is not a valid item name");

			//okay.
			$this->name = $sys_items[$name]['name'];
			$this->description = $sys_items[$name]['desc'];
			$this->concise = concise($name);
		}
		else if($type == 'creature'){
			$fetch = cfg::$db->query("SELECT family_name, creature_name, stage
									FROM creatures_db
									LEFT JOIN creatures_families ON creatures_db.familyID = creatures_families.familyID
									WHERE creatureID = '{$idorname}'
									LIMIT 1")->fetch_assoc();

			if($fetch['stage'] == 1)
				$this->name = $fetch['family_name'].' '.$fetch['creature_name'];
			else
				$this->name = $fetch['creature_name'];
		
			$this->concise = concise($this->name);
			$this->id = $idorname;
		}
		elseif($type == 'exotic'){
			$this->name = 'Exotic Credit';
			$this->concise = 'exotic_credit';
		}
		elseif($type == 'coins'){
			$this->name = 'Coins';
			$this->concise = concise('coins');
		}
		else{
			throw new Exception("Invalid something: $type");
		}
	}
	
	public function give($amount, $userID, $overrides = array()){
		if($this->type == 'component'){
			cfg::$db->query("UPDATE user_owned_components
							SET {$this->concise} = {$this->concise} + {$amount}
							WHERE userID = {$userID} LIMIT 1");
		}
		elseif($this->type == 'item'){
			cfg::$db->query("UPDATE user_owned_items
						SET {$this->concise} = {$this->concise} + {$amount}
						WHERE userID = {$userID} LIMIT 1");
		}
		elseif($this->type == 'creature'){
			give_creature($userID, $this->id, $overrides, $amount);
		}
	}
	public function take($amount, $userID){
		if($this->type == 'component'){
			cfg::$db->query("UPDATE user_owned_components
							SET {$this->concise} = {$this->concise} - $amount 
							WHERE userID=$userID LIMIT 1");
		}
		#TODO: make this safe #tags - specific adopt id
		elseif($this->type == 'creature'){
			cfg::$db->query("DELETE FROM `creatures_owned`
							WHERE creatureID={$this->id}
							AND userID = $userID LIMIT $amount");
		}
		#TODO: make this safe #tags - specific credit id?
		elseif($this->type == 'exotic'){
			cfg::$db->query("UPDATE exotic_credits
							SET spent_id = 1
							WHERE sent_to_userID = {$userID} AND spent_id = 0
							LIMIT {$amount}");
		}
	}

	public function image($add_suffix = ''){
		if($this->type == 'component')
			return "/images/components/{$this->concise}$add_suffix.png";
		if($this->type == 'item')
			return "/images/items/{$this->concise}$add_suffix.png";
		if($this->type == 'creature')
			return "/images/creatures/{$this->id}.png";
		if($this->type == 'exotic')
			return "/images/icons2/exotic_credit.png";
		if($this->type == 'coins')
			return "/images/items/{$this->concise}$add_suffix.png"; 
	}
}

$stuff = new something('component', 'meadowgem');
?>