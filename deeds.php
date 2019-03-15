<?php
require './inc/head.php';

$plots = cfg::$db->query("SELECT plotTypeID, name FROM plots");

require $template .$pagename;
?>