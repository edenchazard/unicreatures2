<?php
require ROOT.'/inc/head.php';

if(!$user->is('admin'))
	redirect_to_index();

$families_list = cfg::$db->query("SELECT family_name FROM creatures_families");

if(isset($_GET['family'])){
	$_GET = escape($_GET);

	//check family exists
	if(!$editing_family = cfg::$db->query(
		"SELECT family_name, rarity, type, in_basket, deny_ne FROM creatures_families
		WHERE family_name = '{$_GET['family']}'
		LIMIT 1")->fetch_assoc())
	{
		$page->s = array(1, "<p class='center deny'>That family does not exist.</p>");
	}
	else{
		$page->s = array(2, "<p class='center warning'>You are currently editing the
						{$editing_family['family_name']} family</p>"
					);
	}
}

if(isset(	$_POST['family_name'], $_POST['type'], $_POST['rarity'],
			$editing_family)){
			
	$_POST = escape($_POST);

	try{
		//$_POST = escape($_POST);
		if(isset($_POST['in_basket'])) 	$_POST['in_basket'] = 1;
		else							$_POST['in_basket'] = 0;
		
		if(isset($_POST['deny_ne'])) 	$_POST['deny_ne'] = 1;
		else							$_POST['deny_ne'] = 0;

		$_POST['type'] = ucfirst(strtolower($_POST['type']));
		$_POST['family_name'] = ucfirst(strtolower($_POST['family_name']));

		cfg::$db->query("
		UPDATE creatures_families
		SET	family_name	 = '{$_POST['family_name']}',
			type			= '{$_POST['type']}',
			rarity			= '{$_POST['rarity']}',
			in_basket		= '{$_POST['in_basket']}',
			deny_ne		= '{$_POST['deny_ne']}'
		WHERE family_name = '{$editing_family['family_name']}'
		LIMIT 1");

		$page->s = array(4, "<p class='center deny'>You have successfully changed
							the data for the{$editing_family['family_name']} family.</p>"
						);
	}
	catch(sql_error $e){
		$page->s = array(3, "<p class='center deny'>Sorry, there has been an SQL error.
							The data was not modified. Please check that the family name
							isn't already in use.</p>"
						);
	}
}
require $template .$pagename;
?>