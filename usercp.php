<?php
require './inc/head.php';
if(!is_logged_in())
	header('Location: /login.php') and exit;

$links = array(
	'Profile'	=> array(
							array('Change username', '/cp/change_username.php', '/images/components/metal.png'),
							array('Change password', '/cp/change_password.php', '/images/components/astralune.png'),
							array('Change email', '/cp/change_email.php', '')
						),
	'Lorem ipsum' => array(
							array('Change class', '/class.php', '/images/items/profession_scroll.png')
						)
);

if($user->is('admin')){
	$links['Administration'][] = array('Add new family', '/cp/new_family.php', '');
	$links['Administration'][] = array('Add new creature', '/cp/add_creature.php', '/images/icons2/add_creature.png');
	$links['Administration'][] = array('Modify family', '/cp/modify_family.php', '');
	$links['Administration'][] = array('Modify creature', '/cp/modify_creature.php', '');
	$links['Administration'][] = array('Add new component', '/cp/add_component.php', '/images/components/ancientberry.png');
}

if($user->is('admin') || $user->is('writer')){
	$links['Writing'][] =  array('Add story', '/cp/add_explore_story.php', '');
}

require $template .$pagename;
?>