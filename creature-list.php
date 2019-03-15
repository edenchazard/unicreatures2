<?php
require './inc/head.php';

$list = cfg::$db->query("SELECT family_name, creature_name, creatureID FROM creatures_db ORDER BY family_name, stage");

require $template .$pagename;
?>