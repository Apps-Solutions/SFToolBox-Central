<?php
global $response;

switch ( $action ) {
	case 'lst_admin_users':
		if ( !IS_ADMIN )
			$response['error'] = " No permissions for list. ";
		break; 
	default:
		$response['error'] = " Invalid list. ";
		break;
}

if ( $response['error'] == '' ){
	
	if ( isset( $_POST['table_id'] ) && $_POST['table_id'] != '' ){
		$table_id = $_POST['table_id'];
		require_once DIRECTORY_CLASS . "class.datatable.php";
		$list = new DataTable( $action, $table_id );
		if ( isset($_POST['filterIdx']) && $_POST['filterIdx'] != '' && isset($_POST['filterVal']) && $_POST['filterVal'] != '' ){
			$list->set_filter( $_POST['filterIdx'], $_POST['filterVal']);
			$list->fidx = $_POST['filterIdx'];
			$list->fval = $_POST['filterVal'];
		}
		
		$response['html'] = $list->get_list_html( TRUE );
		if ( count( $list->error ) > 0 ){
			$response['error'] .= $list->get_errors( );
			$response['html'] 	= ""; 
		}  else {
			$response['lbl_foot'] = $list->get_foot_records_label();
			$response['tpages'] = $list->total_pages;
			$response['page'] = $list->page;
			$response['trecords'] = $list->total_records;
			$response['rows'] = $list->rows;
			$response['success'] = TRUE;
		}
		
	} else {
		$response['error'] .= " Invalid table id. <br/> "; 
	} 
	 
}

?>