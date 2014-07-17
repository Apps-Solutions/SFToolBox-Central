<?php
require 'init.php';

$error = false;

if(empty($CONTEXT['user'])){
    $http_vars["MsgErr"] .=  "Favor de llenar el campo Usuario\n";
    $error = true;
}
else{
    $user = $CONTEXT['user'];
}

if(empty($CONTEXT['password'])){
    $http_vars["MsgErr"] .=  "Favor de llenar el campo Contraseña\n";
    $error = true;
}
else{
    $pass = $CONTEXT['password'];
}

if($error == false){
    $login = new Login();
    if($login->log_in($user, $pass) == LOGIN_SUCCESS) {
        $Session->set_from_login($login);
        if( isset( $CONTEXT['hold'] ) ){
            $login->save_cookie();
        }
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