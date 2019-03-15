<?php
		$cur_rand = (10);
		srand($cur_rand);
		
		$type[1] = array('auraglass', 'heartwater', 'lifepowder', 'timeshard');
		$type[2] = array('treescent', 'skypollen', 'watervine', 'whiteroot');
		$type[3] = array('meadowgem', 'moonruby', 'riverstone');
		$type[4] = array('bluemaple', 'echoberry', 'seamelon', 'sunnyseed');
		
		$type[1] = $type[1][array_rand($type[1])];
		$type[2] = $type[2][array_rand($type[2])];
		$type[3] = $type[3][array_rand($type[3])];
		$type[4] = $type[4][array_rand($type[4])];

		print_r($type);
?>