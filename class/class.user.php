<?php
/**
* User CLass
* 
* @package		Meta Tracker			
* @since        18/05/2014
* @author		Manuel Fernández
*/ 

class User{
	
	public $id_user;
	public $user;
	
	public $password;
	
	public $id_profile;
	public $profile; 
	public $lastlogin;
	public $permissions;
	
	public $contact;
	public $instance;
	
	public $error = array();
	
	/**
	* User()    
	* Creates a User object from the DB.
	*  
	* @param	$id_user (optional) If set populates values from DB record. 
	* 
	*/  
	function User( $id_user = 0 ){
		global $obj_bd;
		$this->error = array();
		if ( $id_user > 0 ){
			$query = "SELECT id_user, us_user, id_profile, pf_profile, us_lastlogin "
						. " FROM " . PFX_MAIN_DB . "user "
						. " INNER JOIN " . PFX_MAIN_DB . "profile ON id_profile = us_pf_id_profile "
					. " WHERE id_user = " . $id_user;
			$info = $obj_bd->query( $query );
			if ( $info !== FALSE ){
				if ( count($info) > 0 ){
					$usr = $info[0];
					$this->id_user 		= $usr['id_user'];
					$this->user	 		= $usr['us_user']; 
					$this->id_profile 	= $usr['id_profile'];
					 
					$this->lastlogin 	= $usr['us_lastlogin']; 
					
					$this->set_profile();
					$this->set_contact();
					
				} else {
					$this->clean();
					$this->set_error( "User not found (" . $id_user . "). ", ERR_DB_NOT_FOUND, 2 ); 
				}
			} else {
				$this->clean();
				$this->set_error( "An error ocurred while querying the Data Base. ", ERR_DB_QRY, 2 );
			} 
		} else {
			$this->clean();
		} 
	}
	
	
	/**
	* validate()    
	* Validates the values before inputing to Data Base 
	*  
	* @return        Boolean TRUE if valid; FALSE if invalid
	*/ 
	public function validate(){
		
		global $Validate; 
		if ( !$this->user != '' ){
			$this->set_error( 'User value empty. ', ERR_VAL_EMPTY );
			return FALSE;
		}
		if ( !$Validate->is_unique( 'user', 'us_user', $this->user, 'id_user', $this->id_user ) ){
			$this->set_error( 'User not unique. ', ERR_VAL_NOT_UNIQUE );
			return FALSE;
		} 
		if ( !$this->id_profile > 0 || !$Validate->exists( 'profile', 'id_profile', $this->id_profile)){
			$this->set_error( 'Invalid profile. ', ERR_VAL_EMPTY );
			return FALSE;
		}
		
		return TRUE;
		
	}
	
	/**
	* save()    
	* Inserts or Update the record in the DB. 
	* 
	*/  
	public function save(){
		if ( $this->validate() ){
			global $obj_bd;
			if ( $this->id_user > 0 ){
				$query = " UPDATE " . PFX_MAIN_DB . "user SET "
							. " us_user = '" . mysql_real_escape_string( $this->user ) . "', "
							. " us_pf_id_profile = '" . $this->id_profile . "', "
							. " us_timestamp = " . time() . " "
							. " WHERE id_user = " . $this->id_user . " ";
			} else {
				$query = "INSERT INTO " . PFX_MAIN_DB . "user " 
						. "( us_user, us_pf_id_profile, us_password, us_timestamp, us_status) "
						. " VALUES ("
						. " '" . mysql_real_escape_string( $this->user ) . "', "
						. " '" .  $this->id_profile  . "', "
						. " 'XXXXX', " . time() . ", 1 "  
						. ")"; 
			}
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){
				if ( $this->id_user == 0){
					 $this->id_user = $obj_bd->get_last_id();
					 return $this->set_password( TRUE );
				}
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record. ", ERR_DB_EXEC, 3 );
				return FALSE;
			} 
		}
	}

	/**
	* set_password()    
	* IF password is empty, generates a new password.  
	* Either way it sends the password on an e-mail to the user.
	* 
	* @param	$flag TRUE sets new user email template; FALSE sets change password email template. (Default is FALSE)
	* 
	* @return	TRUE if password was saved correctly: FALSE otherwise.
	*/  
	public function set_password( $flag = FALSE ){
		if ( $this->password == ""){  
			$this->password = genereate_password(); 
		}
		
		$hash = md5( $this->password );
		
		global $obj_bd;
		$query = " UPDATE "  . PFX_MAIN_DB . "user SET "
					. " us_password  = '" . $hash . "', "
					. " us_timestamp = '" . time() . "' "
				. " WHERE id_user = " . $this->id_user . " ";
		$result = $obj_bd->execute( $query );
		if ( $result !== FALSE ){ 
			return TRUE;
		} else {
			$this->set_error( "An error ocurred while trying to update the password in the DB. ", ERR_DB_EXEC, 3 );
			return FALSE;
		} 
	}
	
	/**
	* delete()    
	* Changes status from User to 0 in the DB.. 
	* 
	*/  
	public function delete(){
		if ( IS_ADMIN ){
			global $obj_bd;
			$query = " UPDATE "  . PFX_MAIN_DB . "user SET "
						. " us_status = 0 "
					. " WHERE id_user = " . $this->id_user . " ";
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){
				$this->clean();
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to set status to 0. ", ERR_DB_EXEC, 3 );
				return FALSE;
			} 
		}
	}
	
	/**
	* set_contact()    
	* Creates a Contact object related to the User
	* @param         $id_contact ( 0 )
	*  
	*/  
	private function set_contact( $id_contact = 0 ){
		
		if ( $id_contact == 0 ){
			 
			if ( $this->id_user > 0 ){
				/* TODO: Query Data Base for contact, create object if exists  */
				global $obj_bd;
				$query = "SELECT id_contact FROM " . PFX_MAIN_DB . "contact WHERE co_us_id_user = " . $this->id_user ;
				$info = $obj_bd->query( $query );
				if ( $info !== FALSE ) {
					if ( count( $info ) > 0 )
						$id_contact = $info[0]['id_contact'];
				} else{
					$this->set_error( "An error ocurred while trying to query the DB for the contact information. ", ERR_DB_QRY, 2 );
				}
			}  
			
		} 
		if ( !class_exists( 'Contact' ) ) 
	  		require_once 'class.contact.php';
		$this->contact = new Contact( $id_contact );
	}
	
	
	/**
	* set_profile()    
	* Creates a Profile object related to the User
	* @param         $id_contact ( 0 )
	*  
	*/  
	private function set_profile( ){
		if ( $this->id_profile > 0 ){
			 if ( !class_exists('Profile' ) ) 
			 		require_once 'class.profile.php';
			 $this->profile = new Profile( $this->id_profile ); 
		} 
	}
	
	
	/**
	 * get_array()
	 * returns an Array with user information
	 * 
	 * @param 	$full Boolean if TRUE returns Contact and Instance Arrays (default FALSE)
	 * 
	 * @return	$array Array with User information
	 */
	 public function get_array( $full = FALSE ){
	 	$array = array(
	 					'id_user' 		=>	$this->id_user,
	 					'user' 			=>	$this->user,
	 					'id_profile' 	=>	$this->id_profile,
	 					'profile' 		=>	$this->profile->profile
					);
		
		if ($full){
	 		$array['profile']	= $this->profile->get_array();
			$array['contact'] 	= $this->contact->get_array(); 
		} 
		return $array;
	 }
	
	/**
	 * get_user_html()
	 * returns a String of HTML with user information
	 * 
	 * @param 	$full Boolean if TRUE returns Contact and Instance Arrays (default FALSE)
	 * 
	 * @return	$html String html user info template
	 */
	 public function get_user_html( $full = FALSE ){
	 	$html  = "";
		$user = $this;
		ob_start();
		require_once DIRECTORY_VIEWS . "admin/info.user.php"; 
		$html .= ob_get_contents();
		ob_end_clean();
		
		return str_replace(array("\n", "\t"), "", $html);
	 }
	 
	/**
	* clean()    
	* Cleans all parameters and resets all objects
	*  
	*/  
	public function clean(){
		$this->id_user = 0;
		$this->user = "";
		
		$this->id_profile = 0;
		$this->profile = ""; 
		$this->permissions 	= "";
		$this->password 	= "";
		$this->lastlogin 	= 0; 
		
		$this->contact = new stdClass;
		
		$this->error = array();
		
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
		$Log->write_log( " ERROR @ Class User (" . $this->id_user . "): " . $err, $type, $lvl );
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
	public function get_errors( $html = true ){
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
		$Log->write_log( $action . " @ Class User (" . $this->id_user . "): " . $msg );
		if ( $echo != '') $mensaje .= $echo . " <br/> ";
	}
}

?>