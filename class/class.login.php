<?php
include_once("class.template.php");

class Login {
	var $bd;
	var $user;
	var $nombre; 
	var $nivel;
	var $email;
	var $id;
	var $plantilla;

	function Login(){
		$this->init();
	}

	function init(){
		global $obj_bd;
		$this->bd = $obj_bd;
		$this->template = new Template;
	}
 
	function get_user(){
		return $this->user;
	}
            
    function get_name(){
        return $this->nombre;
    }
            
	function get_level(){
		return $this->nivel;
	}

	function get_email(){
		return $this->email;
	}

	function get_id() {
		return $this->id;
	}

	function log_in($usuario, $password) {
			
		global $obj_bd;
		$query 	= "SELECT id_user AS id FROM " . PFX_MAIN_DB . "user WHERE us_user='%s' AND us_password='%s' ";
		$usr	= $obj_bd->query(sprintf($query,$usuario,$password));   
		if ( $usr !== FALSE ){
			if ( count($usr) > 0 ){
				
				
				$query = "SELECT *, CONCAT(co_lastname, ' ', co_name ) as name " 
						. " FROM " . PFX_MAIN_DB . "user u "
	                    	. " INNER JOIN " . PFX_MAIN_DB . "profile p ON id_profile = us_pf_id_profile "
	                    	. " LEFT JOIN " . PFX_MAIN_DB . "contact c ON co_us_id_user = id_user "
						. " WHERE id_user = '%s' ";
				
				$user_info = $obj_bd->query(sprintf($query, $usr[0]['id']));
				if ( $user_info !== FALSE ){
					$record = $user_info[0];
					$this->name 	= utf8_encode($record['name']);
					$this->user 	= $record['us_user'];
					$this->email 	= $record['us_user'];
					$this->level 	= $record['id_profile'];
					$this->id 		= $record['id_user'];
					
					$_SESSION[PFX_SYS . 'name']		= $this->name;
					$_SESSION[PFX_SYS . 'profile']	= $this->level;
					$_SESSION[PFX_SYS . 'user']		= $this->user;
					$_SESSION[PFX_SYS . 'id']		= $this->id; 
					
					if ( $this->level == 1 ){
						define('ES_ADMIN', true);
					} else {
						define('ES_ADMIN', false);
					}
					session_write_close();
					
					$sql = "UPDATE " . PFX_MAIN_DB . "user SET us_lastlogin='".time()."' WHERE id_user='".$this->id."'";
					
					$resp = $obj_bd->execute( $sql );
					
					return LOGIN_SUCCESS; 
					
				}  else {
					return LOGIN_DBFAILURE;
				} 
			} else {
				return LOGIN_BADLOGIN;
			}
		} else {
			return LOGIN_DBFAILURE;
		} 
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
