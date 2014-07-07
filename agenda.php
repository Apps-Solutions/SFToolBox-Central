<?php
require_once 'init.php';  
$action = $_POST['action'];
$cb	 	= $_POST['cb'];
ini_set('display_errors', TRUE);
echo "<pre>" . print_r( $_POST ) . "</pre>";

switch( $action ){
	case 'edit_contact':
		$command = ( $cb != '' ) ? $cb : LST_CONTACTS ;
		if ( ! class_exists( 'Contact') )
				require_once DIRECTORY_CLASS . "class.contact.php";
		
		if ( isset( $_POST['id_contact'] ) && is_numeric($_POST['id_contact']) && $_POST['id_contact'] >= 0 ){
			$id_contact = $_POST['id_contact']; 
			$contact = new Contact( $id_contact ); 
		} else if ( IS_ADMIN && isset( $_POST['id_user'] ) && is_numeric($_POST['id_user']) && $_POST['id_user'] >= 0 ){
			
			$id_user = $_POST['id_user'];
			$contact = new Contact();
			$contact->set_from_user( $id_user  ); 
			
		} else if ( IS_ADMIN && isset( $_POST['co_us_id_user'] ) && is_numeric($_POST['co_us_id_user']) && $_POST['co_us_id_user'] >= 0 ){
			
			$id_user = $_POST['co_us_id_user'];
			$contact = new Contact();
			$contact->set_from_user( $id_user  ); 
			
		} else{
			
			header("Location: index.php?command=" . $command . "&err=" . urlencode( "No se recibieron los datos necesarios." ) );
		} 
		
		$contact->id_user		= ( isset($_POST['co_us_id_user']) && $_POST['co_us_id_user'] > 0 ) ? $_POST['co_us_id_user'] : 0;
		$contact->id_type		= ( isset($_POST['id_type'])  && $_POST['id_type'] > 0 ) 	? $_POST['id_type']  : 0;
		
		if ( $contact->id_user > 0 ) 
			$contact->id_type = 1; //persona
		
		$contact->name		 	= ( isset($_POST['name']) 	  && $_POST['name'] != '' ) 	? strip_tags($_POST['name']) 		: '';
		$contact->lastname	 	= ( isset($_POST['lastname']) && $_POST['lastname'] != '' ) ? strip_tags($_POST['lastname']) 	: '';
		$contact->sex		 	= ( isset($_POST['sex']) 	  && $_POST['sex'] != '' ) 		? strip_tags($_POST['sex']) 		: '';
		$contact->email		 	= ( isset($_POST['email']) 	  && $_POST['email'] != '' ) 	? strip_tags($_POST['email']) 		: '';
		$contact->telephone 	= ( isset($_POST['telephone'])&& $_POST['telephone'] != '' )? strip_tags($_POST['telephone']) 	: '';
		$contact->cellphone 	= ( isset($_POST['cellphone'])&& $_POST['cellphone'] != '' )? strip_tags($_POST['cellphone']) 	: '';
		
		foreach ($contact->meta->options as $k => $option) {
			$id 	= $option->id_option;
			$idx 	= "contact_option_" . $id;
			$contact->meta->options[$k]->value = ( isset($_POST[$idx]) && $_POST[$idx] != '' )? strip_tags($_POST[$idx]) : '';
		}
		
		$resp = $contact->save();
		if ( $resp === TRUE ){ 
			$str_err = "";
			if ( isset( $_FILES['avatar'] ) && $_FILES['avatar']['tmp_name'] != '' && $_FILES['avatar']['name'] != '' ){
				$file = $_FILES['avatar']; 
				$resp_img = $contact->save_avatar( $file ); 
			} 
			if  ( count( $contact->error) > 0 ){
				$str_err = "&err=" . urlencode( $contact->get_errors() );
			} 
			header("Location: index.php?command=" . $command . "&msg=" . urlencode( "El registro se guardó exitosamente." ) . $str_err );				
		} else { 
			header("Location: index.php?command=" . $command . "&err=" . urlencode( $contact->get_errors() ) ); 
		} 
		
		break; 
	default: 
		$command = ( $cb != '' ) ? $cb : LST_CONTACTS ;
		header("Location: index.php?command=" . $command . "&err=" . urlencode( "Acción inválida." ));
		break;
}

?>