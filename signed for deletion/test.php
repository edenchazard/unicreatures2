<?php
set_time_limit(50000);
$link = new mysqli('localhost', 'user', 'Kangaro0Penguins', 'unicreatures');
$x = microtime(true);
for($i = 0; $i < 100000; ++$i){
	$link->query("
SELECT `ID` , `creatureID` , `speciality` , `variety` , `nickname`
FROM `creatures_owned`
WHERE `userID` = '1'
AND `areaID` = '0'

ORDER BY `sort_order` DESC, `collected_at` DESC");
}
echo 'A) '.(microtime(true) - $x).'<br />';

/*$x = microtime(true);
for($i = 0; $i < 100000; ++$i){
	$link->query("
SELECT `ID` , `creatureID` , `speciality` , `variety` , `nickname`
FROM `creatures_owned` FORCE INDEX(i_order)
WHERE `userID` = '1'
AND `areaID` = '0'

ORDER BY `sort_order` DESC, `collected_at` DESC");
}
echo 'B) '.(microtime(true) - $x).'<br />';*/

$x = microtime(true);
for($i = 0; $i < 100000; ++$i){
	$link->query("
SELECT `ID` , `creatureID` , `speciality` , `variety` , `nickname`
FROM `creatures_owned` FORCE INDEX(userID, i_order)
WHERE (`userID` = '1'
AND `areaID` = '0')

ORDER BY `sort_order` DESC, `collected_at` DESC");
}
echo 'C) '.(microtime(true) - $x);
?>