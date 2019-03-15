<?php
class query{
}

class singleCreature{
	public function isFrozen(){
		return ($this->frozen == true);
	}

	public function canNormalize(){
		$msgs = array();
		if($this->isFrozen)
			$msgs[] = "<p class='deny'><span class='b'>{$creature['nickname']}</span> is frozen.</p>";
			
		if($creature['speciality'] != 1
			&& $creature['speciality'] != 2)
			$msgs[] = "<p class='deny'><span class='b'>{$this->nickname}</span> must be a noble or exalted.</p>";
		return $msgs;
	}
}
?>