<?php

class Session {
	var $name;
	var $hash;
	var $level;
	var $user;
	var $id; 
	var $settings;

	function Session(){
		$this->init();
		
		$this->set_settings();
	}

	private function init() {
		global $Debug;
		global $facebook;
		$this->user = "";
		$this->name = "";
		$this->level = 0;
		$this->id = "";

		if ( $this->set_from_session() ) {
            return TRUE;
		} else if ( $this->set_from_cookie() ){
			return TRUE;
		} else{
			$this->end_session();
			return FALSE;
		}
	}
	
	private function set_settings(){
		global $Settings;
		$this->settings = new stdClass;
		
		$this->settings->title  = $Settings->get_settings_option('global_sys_title');
		$this->settings->color1 = $Settings->get_settings_option('global_css_color1');
		$this->settings->color2 = $Settings->get_settings_option('global_css_color2');
		$this->settings->color3 = $Settings->get_settings_option('global_css_color3');
		
	}
	
	private function set_from_session(){
		if (
			//isset($_SESSION[PFX_SYS . 'name']) && ($_SESSION[PFX_SYS . 'name'] != "") &&
			isset($_SESSION[ PFX_SYS . 'profile']) && ($_SESSION[ PFX_SYS . 'profile'] != "") &&
			isset($_SESSION[ PFX_SYS . 'user']) && ($_SESSION[ PFX_SYS . 'user'] != "") &&
			isset($_SESSION[ PFX_SYS . 'id']) && ($_SESSION[ PFX_SYS . 'id'] != "")
		) { 
            $this->name 	= $_SESSION[ PFX_SYS . 'name'];
			$this->level 	= $_SESSION[ PFX_SYS . 'profile'];
			$this->user 	= $_SESSION[ PFX_SYS . 'user'];
			$this->id 		= $_SESSION[ PFX_SYS . 'id']; 
			if ( $this->level == 1 ){
				define('IS_ADMIN', TRUE);
			} else {
				define('IS_ADMIN', FALSE);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function set_from_cookie(){
		if (
			isset($_COOKIE[PFX_SYS . 'user'] ) && $_COOKIE[PFX_SYS . 'user']  != '' && 
			isset($_COOKIE[PFX_SYS . 'token']) && $_COOKIE[PFX_SYS . 'token'] != '' 
		) {
			$us_usuario	= $_COOKIE[PFX_SYS . 'usr'];
			$us_password= $_COOKIE[PFX_SYS . 'token'];
			if ($this->create_session($us_usuario , $us_password)){ 
				if (stripos( $_SERVER['SCRIPT_NAME'] , SYS_LOGIN ) > 0)  header('location: index.php');
				//cookie activa y la sesion válida
			}  else header('location: '.SYS_LOGIN); 
		} 
	}

	public function logged_in() {
		return ($this->id != "");
	} 
	
    public function get_name() {
        return $this->name;
    } 
            
	public function get_level() {
		return $this->level;
	}

	public function get_user() {
		return $this->user;
	}

	public function get_email() {
		return $this->user;
	}
	
	public function get_id() {
		return $this->id;
	}

	public function get_var( $varname ) {
		return ( isset($_SESSION[$varname]) ? $_SESSION[$varname] : "" );
	}
	
	public function set_var( $varname, $value ) {
		$_SESSION[$varname] = $value;
	}
 
	public function end_session() {
		$_SESSION[PFX_SYS . 'name'] 		= "";
		$_SESSION[PFX_SYS . 'user'] 		= "";
		$_SESSION[PFX_SYS . 'id'] 		= "";
		$_SESSION[PFX_SYS . 'profile'] 	= 0;
		
		setcookie("meta_tracker_user",	'', time() - 3600 );  
		setcookie("meta_tracker_token",	'', time() - 3600 );
		
		session_destroy();
		session_start();
		
		$this->user = "";
		$this->name = "";
		$this->level = 0;
		$this->id = "";
	}

}
?>