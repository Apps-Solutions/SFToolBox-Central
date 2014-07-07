<?php

class Log{
	
	private $name = "";
	
	protected $handle;
	
	
	function Log(){
		$this->name = "";
		$this->check_file(); 
	}
	
	private function check_file(){
		try {
			if ( file_exists( LOG_DIR . LOG_FILE ) ){
				if ( filesize( LOG_DIR . LOG_FILE ) >= LOG_MAX_SIZE ){
					$success = rename(LOG_DIR . LOG_FILE, LOG_DIR . LOG_FILE . "_YmdHis"); 
					if ( !$success ){
						throw new Exception("ERROR: No se ha podido respaldar el archivo de Log");
						die("ERROR: No se ha podido respaldar el archivo de Log");
					}
				} 
			} 
			$this->handle = fopen( LOG_DIR . LOG_FILE , 'a'); //implicitly creates file  	
		}
		catch (Exception $e){
			throw new Exception("ERROR: No se ha podido crear el archivo de Log. ". $e );
			die( "ERROR: No se ha podido crear el archivo de Log. ". $e ); 
		}
		
	}
	 
	public function write_log( $string  , $type = 0, $level = 1){
		try {
			$formatted =  sprintf( LOG_TMPLT , date('Y-m-d H:i:s') , $_SESSION[ PFX_SYS . 'user'], $_SERVER['REMOTE_ADDR'] , $string . "\n"); 
			$success = fwrite($this->handle, $formatted );
			
			if ( !$success )
				die("ERROR: No se ha podido escribir en el archivo de Log. ". $success );
			
			if ($type > 0 )
				$this->save_log( $type, $level, $string );
		} 
		catch (Exception $e){
			throw new Exception("ERROR: No se ha podido escribir en el archivo de Log. ". $e );
			die("ERROR: No se ha podido escribir en el archivo de Log. ". $e ); 
		} 
	}
	 
	public function write_api_log( $user, $from, $string , $type = 3, $level = 3 ){
		try {
			$formatted =  sprintf( LOG_TMPLT , date('Y-m-d H:i:s') , $user, $from, $string . "\n"); 
			$success = fwrite($this->handle, $formatted );
			
			if ( !$success )
				die("ERROR: No se ha podido escribir en el archivo de Log. ". $success );
			
			$this->save_log( $type, $level, $string );
		} 
		catch (Exception $e){
			throw new Exception("ERROR: No se ha podido escribir en el archivo de Log. ". $e );
			die("ERROR: No se ha podido escribir en el archivo de Log. ". $e ); 
		} 
	}

	private function save_log( $type, $level, $string ){
		global $obj_bd; 
		$query = " INSERT INTO log ( lg_lt_id_log_type, text, level, alert, timestamp ) "
							. " VALUES ( " 
							. "  " . $type . ", " 
							. " '" . mysql_real_escape_string(trim($string)) . "', "
							. "  " . $level . ", " 
							. "0," . time() . " ); "; 
	
		return $obj_bd->execute( $query );  
	}
	
	private function clean_log(){
		/*TODO: Clean log*/
	}
 
}

?>