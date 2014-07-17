<?php
include_once("class.template.php");

class Login {
	var $bd;
	var $user;
	var $name; 
	var $profile;
	var $id;
	var $plantilla;
    var $firstLogin;
    var $cookie;
    var $status;

	function Login(){
        global $obj_bd;
		$this->bd = $obj_bd;
		$this->template = new Template;
		$this->firstLogin = NULL;
        $this->cookie = NULL;
        $this->status = NULL;
	}
    
	function get_user(){
		return $this->user;
	}
            
    function get_name(){
        return $this->name;
    }
            
	function get_profile(){
		return $this->profile;
	}

	function get_id() {
		return $this->id;
	}
    function get_status(){
        return $this->status;
    }
    private function get_user_info(){
        global $obj_bd;
        $query =
        "SELECT *, CONCAT(co_lastname, ' ', co_name ) as name, uss_last_login, uss_cookie
		FROM %suser u
        INNER JOIN %sprofile p ON id_profile = us_pf_id_profile
        LEFT JOIN %scontact c ON co_us_id_user = id_user
        LEFT JOIN %suser_session ON uss_us_id_user = id_user
        WHERE id_user = '%s' ";
        $query = sprintf( $query, PFX_MAIN_DB, PFX_MAIN_DB, PFX_MAIN_DB, PFX_MAIN_DB, $this->id );
        $user_info = $obj_bd->query( sprintf( $query, $this->id ) );
        if ( $user_info !== FALSE ){
            $record = $user_info[0];
            $this->name 	= utf8_encode($record['name']);
            $this->user 	= $record['us_user'];
            $this->profile 	= $record['id_profile'];
            $this->id 		= $record['id_user'];
            $this->cookie = $record['uss_cookie'];
            $this->firstLogin = is_null( $record['uss_last_login'] );
        }
        else{
            $this->status = $this->status = LOGIN_DBFAILURE;
        }
    }
    public function save_cookie(){
        setcookie( COOKIE_TOKEN, $this->cookie, COOKIE_EXPIRATION );
    }
	public function log_in() {
        global $obj_bd;
        $logInType = func_num_args();
        $fromCookie = 1;
        $fromUserPassword = 2;
        //sanitizing access data
        foreach(func_get_args() as &$accessData){
            $accesData = sanitize($accessData);
        }
        //building query
        switch($logInType){
            case $fromCookie:
                $token = func_get_arg(0);
                $query = "SELECT uss_us_id_user AS id FROM %suser_session WHERE uss_cookie='%s' ";
                $query = sprintf($query, PFX_MAIN_DB, $token);
                break;
            case $fromUserPassword:
                $user = func_get_arg(0);
                $password = func_get_arg(1);
                $password = md5($password);
                $query = "SELECT id_user AS id FROM %suser WHERE us_user='%s' AND us_password='%s' ";
                $query = sprintf($query, PFX_MAIN_DB, $user, $password);
        }
        //finding errors
		$usr = $obj_bd->query($query);
        $this->status = $usr === FALSE? LOGIN_DBFAILURE: NULL;
        $this->status = is_null($this->status) && count( $usr ) == 0? LOGIN_BADLOGIN: $this->status;
        $this->id = $usr[0]['id'];
        $this->get_user_info();
        if( is_null( $this->status ) ){
            //saving last login info
            $sql = array();
            switch($logInType){
                case $fromCookie:
                    $sql[] = sprintf(
                    "UPDATE %suser_session SET uss_last_login = '%s'
                    WHERE uss_us_id_user = '%s'",
                    PFX_MAIN_DB, time(), $this->id);
                    break;
                case $fromUserPassword:
                    $this->cookie = genereate_password();
                    $sql[] = $this->firstLogin?
                    sprintf(
                    "INSERT INTO %suser_session
                    (uss_us_id_user, uss_last_login, uss_cookie)
                    VALUES (%s, %s,'%s')",
                    PFX_MAIN_DB, $this->id, time(), $this->cookie):
                    sprintf(
                    "UPDATE %suser_session
                    SET uss_last_login = '%s', uss_cookie = '%s'
                    WHERE uss_us_id_user = '%s'",
                    PFX_MAIN_DB, time(), $this->cookie, $this->id);
                    break;
            }
            foreach($sql as $s){
                $resp = $obj_bd->execute( $s );
                $this->status = $this->status == LOGIN_DBFAILURE || $resp === FALSE? LOGIN_DBFAILURE: LOGIN_SUCCESS;
            }
        }
        return $this->status;
	}

	function Forgot($email) {
		$consulta  = "SELECT id_usuario "
					. " FROM " . PFX_MAIN_DB . "usuario WHERE us_usuario='$email' and us_status='1'";

		if (($result = $this->bd->Query("forgot", $consulta))!= IBD_SUCCESS) {
			return $result;
		}

		if (($result = $this->bd->NumeroRegistros("forgot")) < 1 ) {
			$this->bd->Liberar("forgot");
			return LOGIN_BADLOGIN;
		}

		$registro = $this->bd->Fetch("forgot"); 
		if ( !$registro ) {
			$result = LOGIN_DBFAILURE;
		}
		else {
			$password	=	substr(md5(uniqid()),0,10); 
			$sql = "UPDATE " . PFX_MAIN_DB . "usuarios SET us_password='".md5($password)."' WHERE id_usuario='".$registro['id_usuario']."'";
			$this->bd->Query("updatepass", $sql);

			$this->template->set_variables(array("email" => $email, "password" => $password, "nombre_web" => NOMBRE_WEB, "url_web" => URL_WEB));
			$ContenidoString = $this->plantilla->show("../templates/mailolvidopass.tpl");

			$headers = "From: ".NOMBRE_WEB."<".MAIL_WEB.">\r\n";
			$headers .= "Reply-To: ".MAIL_WEB."\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
			Correo::Enviar("Recordatorio de contraseña", $email, $ContenidoString,$headers);

			$result = LOGIN_SUCCESS;
		}

		$this->bd->Liberar("forgot");
		return $result;
	}

}
?>
