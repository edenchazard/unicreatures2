<?php
define('MYSQL_E_DUPE_KEY', 1062);
define('MYSQL_E_SYNTAX', 1054);

//ext. for dynamic error logging and stuff
class exMysqli extends mysqli{
	private $dont_error_on = array(),
			$batch_limit = 1000;

	public	$errors = array(),
			$queries = array(),
			$querycount = 0,
			$totalquerytime = 0,
			$general_error_msg = "<p class='center deny'>An error has occurred.</p>",
			$on_error;

	public function eError(){
		echo $this->general_error_msg;
		//exit;
	}

	public function is_error($res, $what){
		return ($res === $what);
	}

	function __construct($server, $username, $password, $db,
						 $dont_error_on = array(),
						 $to_call_on_bad_connect,
						 $to_call_on_error){
		@parent::__construct($server, $username, $password, $db);

		if($this->connect_error){
			call_user_func($to_call_on_bad_connect);
		}

		$this->dont_error_on = $dont_error_on;
		$this->on_error = $to_call_on_error;
	}

    public function query($sql, $mem = true){
		$this->querycount++;

		if($mem)
			$this->queries[] = $sql;

		$stopwatch = microtime(true);
		$result = parent::query($sql);
		$this->totalquerytime += (microtime(true)-$stopwatch);

		//query was successful
		if($result !== false){
			return $result;
		}
		//error
		else{
			//we'll only throw our error function if the error code isn't
			//in the list of errors to look out for
			if(!in_array($this->errno, $this->dont_error_on)){
				//log the error
				$log = fopen('C:/wamp/www/os/mysql.log', 'a');
				fwrite($log, "\r\n".date('l, F j, Y @ H:i:s')." in ".$_SERVER['SCRIPT_FILENAME']." on line ".__LINE__ .": {$this->error}\r\n\tSQL: $sql\r\n");
				fclose($log);

				//do we want to display the error??
				if(DEV_MODE){
					echo "
					<div style='padding:5px; background-color:#fff;'>
						<div style='font-weight:bold; border-bottom:1px solid purple;'>SQL error (Error: #{$this->errno})</div>
						<div>{$this->error}</div>
						<br />
						<div style='font-weight:bold; border-bottom:1px solid purple;'>Query was:</div>
						$sql
						<div>Any transactions have been rolled back.</div>
					</div>";
				}
				
				//rollback any transactions in progress
				$this->rollback();

				call_user_func($this->on_error);
			}
			else{
				//if we got an error, just return the error number
				return $this->errno;
			}
		}
    }

	public function batch_insert($table, $columns, $values){
		if(is_array($values) && is_array($columns)){
			array_walk($columns, 'array_surround_concat', '`');
			$prepend_sql = "INSERT INTO {$table} (".implode(',', $columns).") VALUES ";
			$i = 0;
			$sql = '';
			foreach($values as $insert){
				array_walk($insert, 'array_surround_concat', "'");
				$sql .= '('.implode(',', $insert).'),';
				//if over batch limit, we should probably cut it early
				//and execute these in one batch and so on
				if($i == $this->batch_limit){
					$this->query($prepend_sql.substr($sql, 0, -1));
					$sql = '';
					$i = 0;
				}
				else { ++$i; }
			}
			$this->query($prepend_sql.substr($sql, 0, -1));
		}
		else throw new Exception("Batch insert expects value and columns as an array.");
	}

	/* allows you to create an array of values where you want to update a specific ID
		in one go
		WARNING: DOESN'T ESCAPE YOUR VALUES OR COLUMNS!!!
	*/
	public function update($table, $columns, $values, $id_column, $where_id){
		$y = count($columns);

		if($y > 0){
			$id_column = escape($id_column);
			$where_id = escape($where_id);
			$sql = "UPDATE $table SET";
	
			for($i = 0; $i < $y; ++$i){
				$column = $columns[$i];
				$value = $values[$i];
				$sql .= "`$column` = $value, ";
			}
			
			//remove the last ','
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE `$id_column` = '$where_id' LIMIT 1";
			$this->query($sql);
		}
	}
}
?>