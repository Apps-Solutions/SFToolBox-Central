<?php
require 'init.php';
//ini_set('display_errors', TRUE);
include_once(DIRECTORY_CLASS.'class.login.php');

$login 		= new Login; 
$usuario	= isset($CONTEXT["user"]) 		? sanitize($CONTEXT["user"]) 		: "";
$contrasena	= isset($CONTEXT["password"]) 	? sanitize($CONTEXT["password"]) 	: "";
$error 		= false;

if(empty($usuario)){
    $http_vars["MsgErr"] .=  "Favor de llenar el campo Usuario\n";
    $error = true;
}

if(empty($contrasena)){
    $http_vars["MsgErr"] .=  "Favor de llenar el campo Contraseña\n";
    $error = true;
}

if($error == false){
    if($login->log_in($usuario, md5($contrasena)) == LOGIN_SUCCESS) { 
        $Session->set_var( PFX_SYS . 'name', 	$login->get_name());
        $Session->set_var( PFX_SYS . 'profile',	$login->get_level());
        $Session->set_var( PFX_SYS . 'user',	$login->get_email());
        $Session->set_var( PFX_SYS . 'id', 		$login->get_id()); 
        $location ="index.php";
    }
    else {
        $http_vars["MsgErr"] .= "El Usuario o la Contrase&ntilde;a no son correctos ";
        $location = "index.php?command=" . LOGIN . "&err=" . $http_vars["MsgErr"];
    }
}
else{
    $location = "index.php?command=" . LOGIN;
}

$_SESSION["cookie_http_vars"] = $http_vars;
header("HTTP/1.1 302 Moved Temporarily");
header("Location: $location");
