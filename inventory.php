<?php
require './inc/head.php';

$profile_user = make_user_from_query_string();

//invalid
if(!$profile_user)
	redirect_to_index();

env::set('profile_user_obj', $profile_user);

$rare_components = get_sys_components('Rare Component');
$std_components = get_sys_components('Standard');
$tree_components = get_sys_components('Tree');
$orb_components = get_sys_components('Orb');
$shard_components = get_sys_components('Shard');
$building_materials = get_sys_components('Building Material');
$equipment = get_sys_items();

require $template .$pagename;
?>