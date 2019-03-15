<?php
require './inc/head.php';
$res = cfg::$db->query("SELECT req.amount, req.res_type, comp.name, it.name, db.creature_name AS name
FROM shop_requirements AS req
LEFT JOIN components AS comp ON (req.res_id = comp.componentID AND req.res_type = 'component')
LEFT JOIN items AS it ON (req.res_id = it.itemID AND req.res_type='item')
LEFT JOIN creatures_db AS db ON (req.res_id = db.creatureID AND req.res_type='creature')
WHERE req.transactionID = 51");

while($row = $res->fetch_assoc()){
	print_r($row);
}
?>