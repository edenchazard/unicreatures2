<?php
$link = new mysqli('localhost', 'user', 'Kangaro0Penguins', 'unicreatures');

$dir = 'C:\wamp\www\images\creatures\Zahrah';

/*if($files = scandir($dir)){
	//get family name
	
	$slash = strrpos($dir, "\\");
	$family_name = substr($dir, $slash+1, strlen($dir));
	
	//fetch creatures in family
	$creatures_in_family = array();
	$result = $link->query("SELECT creatureID, creature_name FROM creatures_db WHERE family_name='$family_name' limit 5");
	while ($row = $result->fetch_assoc()){
		$creatures_in_family[strtolower($row['creature_name'])] = $row['creatureID'];
	}
	$donef = false;
	foreach($files as $filename){
		if($filename === '.')
			continue;
		if($filename === '..')
			continue;

		$newfilename = '';
		$creatureid = '';
		$speciality = '';
		$variety = '';

		$parts = explode('_', $filename);
		
		foreach($parts as $part){
			if(isset($creatures_in_family[$part])){
				if($family_name 
				$creatureid .= $creatures_in_family[$part];
			}
			if($part === 'noble')
				$speciality .= '_noble';
			if($part === 'exalted')
				$speciality .= '_exalted';
		}
		print_r($parts);
		$newfilename = $creatureid . $variety . $speciality;

		echo $newfilename . '<br />';
	}
}*/

$creatures_db = $link->query("SELECT family_name, creature_name, creatureID FROM creatures_db");
echo '<table><tbody>';
while($crtrs = $creatures_db->fetch_assoc()){
	$f = $crtrs['family_name'];
	$c = $crtrs['creature_name'];
	$cID = $crtrs['creatureID'];
	echo	"<tr>
				<td>rename('C:\wamp\www\images\c4\\$f\\exalted"."_$c.png'</td>
				<td>, </td>
				<td>'C:\wamp\www\images\creatures\\$cID"."_exalted.png');</td>
			</tr>";
}
echo '</tbody></table>';


?>