<?php

class Session {
	private $profile;
    private $name;
	private $user;
	private $id;
    private $publicVars;
    private $requireVars;
    public $settings;

	function __construct(){
        $this->publicVars = array(
            'user' => '',
            'profile' => 0,
            'name' => '',
            'id' => ''
        ); //default values
        $this->requireVars = array('user', 'profile', 'id');
        session_start();
        $this->set_settings();
        $in = $this->set_from_session() || $this->set_from_cookie();
        if(!$in){
            $this->init();
        }
	}

	private function init() {
        foreach($this->publicVars as $key => $value){
            $this->set_var($key, $value);
        }
	}
	public function set_from_login($login){
        if($login->status != LOGIN_SUCCESS){ return FALSE; }
        foreach($this->publicVars as $var => $value){
            $getter = sprintf('get_%s', $var);
            $this->set_var($var, $login->$getter());
        }
        $this->set_permissions();
        return TRUE;
    }
	private function set_from_session(){
        $valid = TRUE;
        foreach($this->publicVars as $var => $value){
            if(in_array($var, $this->requireVars)){
                $valid = $valid && !empty($_SESSION[PFX_SYS . $var]);
                if(!$valid) break;
            }
            $this->set_var($var, $_SESSION[ PFX_SYS . $var]);
        }
        if($valid){
            $this->set_permissions();
        }
        return $valid;
	}
	private function set_from_cookie(){
        $success = !empty( $_COOKIE[COOKIE_TOKEN] );
        if($success){
            $login = new Login();
            $login->log_in( $_COOKIE[COOKIE_TOKEN] );
            $success = $this->set_from_login( $login );
        }
        return $success;
	}
    private function set_permissions(){
        define('IS_ADMIN', $this->profile == 1);
    }
    private function set_settings(){
        global $Settings;
        $this->settings = new stdClass;
        
        $this->settings->title  = $Settings->get_settings_option('global_sys_title');
        $this->settings->color1 = $Settings->get_settings_option('global_css_color1');
        $this->settings->color2 = $Settings->get_settings_option('global_css_color2');
        $this->settings->color3 = $Settings->get_settings_option('global_css_color3');
		
	}

	public function logged_in() {
		return ($this->id != "");
	} 
	
    public function get_name() {
        return $this->name;
    } 
            
	public function get_profile() {
		return $this->profile;
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
		return ( isset($_SESSION[PFX_SYS . $varname]) ? $_SESSION[$varname] : "" );
	}
	
	public function set_var( $varname, $value ) {
        if(property_exists(get_class($this), $varname)){
            $_SESSION[PFX_SYS . $varname] = $value;
            $this->$varname = $value;
        }
		
	}
 
	public function end_session() {
		session_destroy();
        session_start();
        $this->init();
		
		setcookie(COOKIE_TOKEN, '', time() - 3600 );
	}

}
?>