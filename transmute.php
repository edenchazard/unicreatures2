<?php
require './inc/head.php';

prepend_filter('page_title', function($title){ return "New Atlantis Plaza"; });

if(!is_logged_in()) { header('Location: /login.php'); exit; }

if(!isset($_GET['cat']))
	$_GET['cat'] = 1;

if(!safe_digit($_GET['cat'])) redirect_to_index();

$category = $_GET['cat'];

$curdate = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j'), 1970));

//note: the queries here make use of mysql's IF and coalesce functions,
//to retrieve the data in a more efficient way.

//what kind of exchange is this
$exchange = cfg::$db->query("SELECT child_of, dialogue FROM shop_categories
							WHERE categoryID = {$category} LIMIT 1")
					->fetch_assoc();

//fetch spells
$spells = cfg::$db->query("SELECT tran.amount, tran.type, tran.transactionID,
								tran.title, tran.short_description, tran.`type`,
								tran.res_id, tran.description,
								COALESCE(comp.name, item.name, db.creature_name,
									IF(tran.type = 'Coins', 'Coins', '')
								) AS produces,
								DATEDIFF(tran.dend, '{$curdate}') AS remaining,
								tran.every_year_until AS every_year_until
							FROM shop_transactions AS tran
							LEFT JOIN components AS comp ON (tran.res_id = comp.componentID AND tran.type = 'component')
							LEFT JOIN items AS item ON (tran.res_id = item.itemID AND tran.type = 'item')
							LEFT JOIN creatures_db AS db ON (tran.res_id = db.creatureID AND tran.type = 'creature')
							LEFT JOIN creatures_families AS fa ON(db.familyID = fa.familyID)
							WHERE tran.category = {$category} AND ".date('Y')." <= tran.every_year_until
							AND (('{$curdate}' BETWEEN tran.dbegin AND tran.dend) OR tran.dbegin = tran.dend)");

$store_data = array();

while($spell = $spells->fetch_assoc()){
	$type = concise($spell['type']);
	$spell_data = array(
		'ID'				=> $spell['transactionID'],
		'obj'				=> new something($type, ($type == 'creature' ? $spell['res_id'] : $spell['produces'])),
		'spell_name'		=> $spell['title'],
		'amount'			=> $spell['amount'],
		'short_description'	=> $spell['short_description'],
		'description'  		=> $spell['description'],
		'remaining'			=> $spell['remaining'],
		'every_year_until'	=> $spell['every_year_until'],
		'ingredients' 		=> array()
	);
	
	//fetch ingredients
	$res = cfg::$db->query("SELECT req.amount, req.res_id, req.res_type,
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
		$spell_data['ingredients'][] = array(
										'obj' => new something($type, ($type == 'creature' ? $ingredient['res_id'] : $ingredient['produces'])),
										'amount' => $ingredient['amount']
										);
	}
	$store_data[$spell['transactionID']] = $spell_data;
}

//transmuting
if(isset($_POST['spell'], $_POST['amount'])){

	if(safe_digit($_POST['spell'])){

		$amount = (safe_digit($_POST['amount']) ? $_POST['amount'] : 1);

		$spellID = $_POST['spell'];

		//get data from storage
		if(isset($store_data[$spellID])){

			$transaction = $store_data[$spellID];

			//check has enough ingredients for transaction
			$ok = array_filter($transaction['ingredients'],
				function($v) use($user, $amount){
					return ($user->inventory($v['obj']) < ($amount * $v['amount']));
				}
			);

			if(empty($ok)){
				//process transaction
				cfg::$db->autocommit(false);
				$give = ($amount * $transaction['amount']);

				foreach($transaction['ingredients'] as $ingredient){
					$ingredient['obj']->take($amount * $ingredient['amount'], $user->userID);
				}

				$transaction['obj']->give($give, $user->userID);

				$page->s = array(1, "<div class='center'><img src='".$transaction['obj']->image()."' /></div>
									<p class='center'>{$store_data[$spellID]['description']}</p>
									<p class='center allow'>You have successfully
									transmuted {$give}x
									{$transaction['obj']->name}</p>"
								);

				cfg::$db->autocommit(true);

				//update local variable
				$user->inventory();
			}
			else{
				$page->s = array(3, "<p class='center deny'>You don't have enough
									ingredients to produce {$amount}x
									{$transaction['obj']->name}</p>"
								);
			}
		}
	}
}

require $template .$pagename;
?>