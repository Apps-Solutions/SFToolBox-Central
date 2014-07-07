<?php
require_once 'init.php';
$action = $_POST['action'];

if (IS_ADMIN){
	switch( $action ){
		case 'edit_appearance':
			require_once DIRECTORY_CLASS . "class.settings.php";
			$settings = new Settings( ); 
			global $Validate;
			if ( isset( $_POST['title'] ) && $_POST['title'] != '' ){
				$title = $_POST['title'];
			} else {
				$title = SYS_TITLE;
			}
			if ( isset( $_POST['color1'] ) && $_POST['color1'] != '' && $Validate->is_color( $_POST['color1']) ){
				$color_principal = $_POST['color1'];
			} else {
				$color_principal = COLOR1_DEFAULT;
			}
			if ( isset( $_POST['color2'] ) && $_POST['color2'] != '' && $Validate->is_color( $_POST['color1']) ){
				$color_secundario = $_POST['color2'];
			} else {
				$color_secundario = COLOR2_DEFAULT;
			}
			if ( isset( $_POST['color3'] ) && $_POST['color3'] != '' && $Validate->is_color( $_POST['color1']) ){
				$color_auxiliar = $_POST['color3'];
			} else {
				$color_auxiliar = COLOR3_DEFAULT;
			}
			$resp_0 = $settings->save_settings_option("global_sys_title",  $title );
			$resp_1 = $settings->save_settings_option("global_css_color1", $color_principal	); 
			$resp_2 = $settings->save_settings_option("global_css_color2", $color_secundario); 
			$resp_3 = $settings->save_settings_option("global_css_color3", $color_auxiliar	);
			
			if ( !$resp_0 || !$resp_1 || !$resp_2 || !$resp_3 ){
				$err = $settings->get_errors();
			} else {
				$msg = "Appearance settings saved correctly. ";
			}
			
			if (isset( $_FILES['logo']) && $_FILES['logo']['name'] != '' && $_FILES['logo']['tmp_name'] != '' ){
				
				/* TODO: Catch & Validate image */ 
				$valid = $Validate->uploaded_is_image( $_FILES['logo'] );
				if ( $valid === TRUE ){
					
				} else {
					$err .= $valid;
				} 
				
			} 
			header("Location: index.php?command=" . FRM_APPEARANCE . ( $err != '' ? "&err=" . urlencode( $err ) : "" ). ( $err != '' ? "msg=" . urlencode( $msg ) : "" )); 
			break;
		case 'edit_backend_version':
			if ( IS_ADMIN ){
				require_once DIRECTORY_CLASS . "class.settings.php";
				$settings = new Settings( ); 
				global $Validate;
				if ( isset( $_POST['global_backend_version'] ) && $_POST['global_backend_version'] != '' ){
					$version = $_POST['global_backend_version'];
					$resp = $settings->save_settings_option("global_backend_version",  $version );
					if ( $resp ){
						$msg = "Backend Version updated.";
					} else {
						$err = $settings->get_errors();
					}
					header("Location: index.php?command=" . FRM_VERSION_CTRL . ( $err != '' ? "&err=" . urlencode( $err ) : "" ). ( $err != '' ? "msg=" . urlencode( $msg ) : "" ));
				} else {
					header("Location: index.php?command=" . FRM_VERSION_CTRL . "&err=" . urlencode( "Invalid Version value." )); 
				}
			}
			break; 
		default: 
			header("Location: index.php?err=" . urlencode( "Invalid action." ));
			break;
	}
} else {
	header("Location: index.php?err=" . urlencode( "Access denied." ));
	die();
}

?>