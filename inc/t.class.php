<?php
class v{
	private $hooks = array();

	function fetch(){
	}

	function results_to_array($cat){
		//note: the queries here make use of mysql's IF and coalesce functions,
		//to retrieve the data in a more efficient way.
		//fetch spells
		$spells = cfg::$db->query("
		SELECT tran.amount, tran.type, tran.transactionID, tran.title, tran.short_description, tran.`type`, tran.res_id, tran.description,
			COALESCE(comp.name, item.name, db.creature_name,
				IF(tran.type = 'Coins', 'Coins', '')
			) AS produces
		FROM shop_transactions AS tran
		LEFT JOIN components AS comp ON (tran.res_id = comp.componentID AND tran.type = 'component')
		LEFT JOIN items AS item ON (tran.res_id = item.itemID AND tran.type = 'item')
		LEFT JOIN creatures_db AS db ON (tran.res_id = db.creatureID AND tran.type = 'creature')
		WHERE tran.category = {$cat}");

		$data = array();

		while($spell = $spells->fetch_assoc()){
			$type = concise($spell['type']);

			if(!isset($this->hooks[$type]))
				throw new Exception ("Invalid resource type: {$type}.");

			$spell_data = array(
				'ID'				=> $spell['transactionID'],
				'obj'				=> new $type(($type == 'creature' ? $spell['res_id'] : $spell['produces'])),
				'spell_name'		=> $spell['title'],
				'amount'			=> $spell['amount'],
				'short_description'	=> $spell['short_description'],
				'description'  		=> $spell['description'],
				'ingredients' 		=> array()
			);
			
			//fetch ingredients
			$res = cfg::$db->query("
			SELECT req.amount, req.res_id, req.res_type,
				COALESCE(comp.name, item.name, db.creature_name,
					IF(req.res_type = 'Coins', 'Coins', '')
				) AS produces
			FROM shop_requirements AS req
			LEFT JOIN components AS comp ON (req.res_id = comp.componentID AND req.res_type = 'component')
			LEFT JOIN items AS item ON (req.res_id = item.itemID AND req.res_type = 'item')
			LEFT JOIN creatures_db AS db ON (req.res_id = db.creatureID AND req.res_type = 'creature')
			WHERE req.transactionID = {$spell['transactionID']}");

			while($ingredient = $res->fetch_assoc()){
				$type = concise($ingredient['res_type']);

				if(!isset($this->hooks[$type]))
					throw new Exception ("Invalid resource type: {$type}.");

				$spell_data['ingredients'][] = array(
												'obj' => new $type(($type == 'creature' ? $ingredient['res_id'] : $ingredient['produces'])),
												'amount' => $ingredient['amount']
												);
			}
			$data[$spell['transactionID']] = $spell_data;
		}
		return $data;
	}

	
	function add_hooks(){
		$args = func_num_args();

		if(is_array(func_get_arg(0))){
			foreach(func_get_arg(0) as $key => $value){
				$this->add_hooks($key, $value);
			}
		}
		else{
			$this->hooks[func_get_arg(0)] = func_get_arg(1);
		}
	}
}
?>