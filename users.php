<?php
require_once 'init.php';  
$action = $_POST['action'];
switch( $action ){
	case 'edit_user': 
		if ( isset( $_POST['id_user'] ) && is_numeric($_POST['id_user']) && $_POST['id_user'] >= 0 ){
			$id_user = $_POST['id_user'];
			if ( $id_user == 1 ){ //Validate user not System Admin
				header("Location: index.php?command=" . LST_USER . "&err=" . urlencode( "El usuario administrador no puede ser editado." ) );
				die();
			}
			
			require_once DIRECTORY_CLASS . "class.user.php";
			$user = new User( $id_user ); 
			$user->user 		= ( isset($_POST['user']) && $_POST['user'] != '' ) ? $_POST['user'] : "";
			$user->id_profile 	= ( isset($_POST['profile']) && is_numeric($_POST['profile']) ) ? $_POST['profile'] : 0; 
			
			$resp = $user->save();  
			if ( $resp === TRUE ){
				$str_err = "";
				if  ( count( $user->error) > 0 ){
					$str_err = "&err=" . urlencode( $user->get_errors() );
				} 
				header("Location: index.php?command=" 	. LST_USER . "&msg=" . urlencode( "El registro se guardó exitosamente." ) . $str_err );				
			} else { 
				header("Location: index.php?command=" 	. LST_USER . "&err=" . urlencode( $user->get_errors() ) ); 
			} 
		} else{ 
			header("Location: index.php?command=" 		. LST_USER . "&err=" . urlencode( "No se recibieron los datos necesarios." ) );
		} 
		break; 
	default: 
		header("Location: index.php?err=" . urlencode( "Acción inválida." ));
		break;
}
?>