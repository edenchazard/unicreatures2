<?php
class env{
	private static $vars = array();

	public static function set($key, $value){
		self::$vars[$key] = $value;
	}

	public static function get($key){
		if(isset(self::$vars[$key]))
			return self::$vars[$key];
			
		else return null;
	}

	public static function manipulate($key, $by){
		self::set($key, manipulate(self::get($key), $by));
	}
}
?>