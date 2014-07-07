<?php
require_once "init.php"; 

$response 	= array( 'success' => false );
$resource	= isset($_REQUEST['resource']) ? $_REQUEST['resource'] : '';
$action 	= isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ( $resource ){
	case 'settings':
		require_once DIRECTORY_AJAX . 'ajax.settings.php';
		break;
	case 'contact':
		require_once DIRECTORY_AJAX . 'ajax.contact.php';
		break;
	case 'lists':
		require_once DIRECTORY_AJAX . 'ajax.lists.php';
		break;
	case 'user':
	case 'profile':
		require_once DIRECTORY_AJAX . 'ajax.admin.php';
		break;
	default: 
		$responce['error'] = "Invalid resource";
		break;
	
}

echo json_encode( $response );
?>