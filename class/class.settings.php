<?php
/**
 * Class Settings
 * 
 */

class Settings{
 	
	public $error = array();
	
	function __construct(){
		
	}
	
	/**
	* get_settings_option()
	* returns the value of the $option from the DB
	* 
	* @param         $option String option key
	* @param 		 $instance Integer (Default 0)  
	* @param 		 $user 		Integer (Default 0) If > 0 sets user filter for the option 
	* 
	* @return 		String value of the option from the DB. NULL on error.
	*/  
	public function get_settings_option( $option, $user = FALSE , $timestamp = FALSE){
		if ( $option != '' ){
			global $obj_bd; 
			global $Session;
			$query = " SELECT so_value, so_timestamp  FROM " . PFX_MAIN_DB . "settings_option "
					. " WHERE so_option = '" . mysql_real_escape_string( $option ) . "' " 
						. (( $user ) ? " AND so_us_id_user = " . $Session->get_id() . " " : "")
						. " AND so_status = 1 "; 
			$record	= $obj_bd->query( $query );
			if ( $record !== FALSE ){
				if (count($record) > 0 ){
					if ( $timestamp ) {
						$resp = new stdClass;
						$resp->value 	= $record[0]['so_value'];
						$resp->timestamp= $record[0]['so_timestamp'];
						$resp->key		= $option;
						return $resp;
					} else {
						$resp = $record[0]['so_value'];
					}
					return $resp;
				} else {
					$this->set_error( "Option not found (" . $option . ")", ERR_DB_NOT_FOUND, 1);
					return NULL;
				}
			}else {
				$this->set_error( "Could not retrieve option (" . $option . ")", ERR_DB_QRY, 2);
				return NULL;
			}
		} else{
			$this->set_error( "Invalid option parameter. ", ERR_VAL_EMPTY); 
			return NULL;
		}
	}
	
	/**
	* save_settings_option()
	* Saves an the value for an option in the DB.
	* 
	* @param         $option 	String option key
	* @param 		 $value 	String option value 
	* @param 		 $instance 	Integer (Default 0)  
	* @param 		 $user 		Boolean (Default FALSE) If TRUE sets Session user as filter for the option  
	* 
	* @return 		String value of the option from the DB. NULL on error.
	*/  
	public function save_settings_option( $option , $value, $instance = 0, $user = FALSE  ){
		if ( $option != '' && $value != '' ){
			global $obj_bd;
			global $Session;
			$query_ex = "SELECT id_settings_option FROM " . PFX_MAIN_DB . "settings_option "
					. " WHERE so_option = '" . mysql_real_escape_string( $option ) . "' " 
						. (( $user ) ? " AND so_us_id_user = " . $Session->get_id() . " " : "");
			$exists	= $obj_bd->query( $query_ex );
			if ( $exists !== FALSE ){ 
				if ( count($exists) > 0 ){
					$id_option = $exists[0]['id_settings_option'];
					$query = "UPDATE  " . PFX_MAIN_DB . "settings_option SET "
								. " so_option 	 = '" . mysql_real_escape_string( $option ) . "', "
								. " so_value  	 = '" . mysql_real_escape_string( $value ) . "', "
								. " so_timestamp = '" . time( ) . "', "
								. " so_us_id_user = '" . $Session->get_id() . "', "
								. " so_status 	 = 1 "
							. " WHERE id_settings_option  = '" . $id_option . "' ";
				}else{ 
					$query = " INSERT INTO " . PFX_MAIN_DB . "settings_option " 
								. "(so_option, so_value, so_timestamp, so_us_id_user, so_status )"
								. "VALUES ("
									. " '" . mysql_real_escape_string( $option ) . "', "
									. " '" . mysql_real_escape_string( $value ) . "', " 
									. " '" . time( ) . "', " 
									. " '" . $Session->get_id() . "', 1 "
								.")";
				}
				$result = $obj_bd->execute( $query );
				if ( $result !== FALSE ){
					$this->set_msg('SAVE', "Option " . $option . " saved. ");
					return TRUE;
				} else { 
					$this->set_error( "An error ocurred while saving the option. ", ERR_DB_EXEC, 3 );
					return FALSE;
				}
			}
			else {
				$this->set_error( "An error ocurred while querying the DB for the option. ", ERR_DB_QRY ); 
				return FALSE;
			}
		} 
		else{
			$this->set_error( "Invalid parameters to save. ", ERR_VAL_EMPTY ); 
			return FALSE;
		} 
	}

	/**
	* set_error()
	* Adds the error to the errors array and writes the error to Log
	* 
	* @param         $err String 
	* @param 		 $type Integer
	* @param 		 $lvl Integer (optional) 
	* 
	*/  
	private function set_error( $err , $type, $lvl = 1 ){
		global $Log;
		$this->error[] = $err;
		$Log->write_log( " ERROR @ Class User Settings: " . $err, $type, $lvl );
	} 
	
	/**
	* get_errors()
	* Adds the error to the errors array and writes the error to Log
	* 
	* @param        $html Boolean
	 * 
	 * @return		String of concatenated errors.  
	* 
	*/  
	public function get_errors( $html = TRUE ){
		$resp = ""; 
		foreach ($this->error as $key => $err) {
			$resp .= $err . ($html) ? "<br/>" : "\n";
		}
		return $resp;
	}
	
	/**
	* set_msg()
	* Writes the message to the Log, optionally adds the message to the global message string.
	* 
	* @param         $action String 
	* @param 		 $msg String
	* @param 		 $echo String (optional) if set adds message to global variable.  
	* 
	*/  
	private function set_msg( $action , $msg , $echo = ''){
		global $Log;
		global $mensaje;
		$Log->write_log( $action . " @ Class Settings: " . $msg );
		if ( $echo != '') $mensaje .= $echo . " <br/> ";
	}
}
?>