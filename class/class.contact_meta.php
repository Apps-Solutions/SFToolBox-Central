<?php

/**
 * class ContactMeta
 */
class ContactMeta {
	
	public $options = array();
	public $error 	= array();
	
	function __construct( $id_contact = 0 ) {
		global $obj_bd;
		$query = "SELECT "
					. " id_contact_option, id_data_type, dt_data_type, cop_label, cop_options, cop_timestamp, "
					. " IFNULL(cm_co_id_contact,0) as id_contact, cm_value "
				. " FROM " . PFX_MAIN_DB . "contact_option  "
					. " INNER JOIN " . PFX_MAIN_DB . "data_type 	ON cop_dt_id_data_type = id_data_type "
					. "  LEFT JOIN " . PFX_MAIN_DB . "contact_meta 	ON cm_cop_id_contact_option = id_contact_option " 
										. ( $id_contact > 0  ? " AND cm_co_id_contact = " . $id_contact . " " : " AND cm_co_id_contact = 0 " )
				. " WHERE cop_status = 1 ORDER BY id_contact_option ASC;";
		$opts = $obj_bd->query( $query ); 
		if ( $opts !== FALSE ){
			if (count($opts) > 0 ){
				foreach ($opts as $k => $opt) {
					$this->options[] = $this->format_option( $opt );
				}
			}
		}else {
			$this->set_error( "Could not retrieve options ", ERR_DB_QRY, 2);
			return NULL;
		}
	}
	
	private function format_option( $option ){
		$resp = new stdClass;
		$resp->id_option 	= $option['id_contact_option'];
		$resp->id_data_type	= $option['id_data_type'];
		$resp->data_type 	= $option['dt_data_type'];
		$resp->label	 	= stripslashes($option['cop_label']);
		$resp->options	 	= stripslashes($option['cop_options']); 
		$resp->timestamp 	= $option['cop_timestamp'];
		$resp->id_contact 	= $option['id_contact']; 
		$resp->value	 	= stripslashes($option['cm_value']);
		return $resp;
	}
	
	public function get_option( $id ){
		foreach ($this->options as $k => $option) {
			if ( $option->id_option == $id )
				return $option;
		}
		return FALSE;
	}
	
	public function delete_option( $id_option = 0){
		if ( $id_option > 0 && IS_ADMIN ){
			global $obj_bd;
			$query = "UPDATE " . PFX_MAIN_DB . "contact_option SET " 
						. " cop_status 		= 0, "
						. " cop_timestamp 	= " . time() . " "
					. " WHERE id_contact_option = " . $id_option . " ";
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){ 
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record. ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		}
	}
	
	public function save_option( $option ){
		if ( IS_ADMIN ){
			global $Validate;
			if ( !( $Validate->is_number( $option->id_option ) && $option->id_option >= 0 ) ){
				$this->set_error('Invalid option ID.', ERR_VAL_NOT_INT );
				return FALSE;
			}
			if ( !( $Validate->is_number( $option->id_data_type ) && $option->id_data_type > 0 ) ){
				$this->set_error('Invalid data type ID.', ERR_VAL_NOT_INT );
				return FALSE;
			}
			if ( !( $option->label != '' ) ){
				$this->set_error('Invalid Label.', ERR_VAL_EMPTY );
				return FALSE;
			}
			
			global $obj_bd;
			if ( $option->id_option > 0 ){
				$query = "UPDATE " . PFX_MAIN_DB . "contact_option SET "
							. " cop_dt_id_data_type = " . $option->id_data_type . ", "
							. " cop_label 		= '" 	. mysql_real_escape_string($option->label) . "', "
							. " cop_options 	= '" 	. mysql_real_escape_string($option->options) . "', "
							. " cop_status 		= 1, "
							. " cop_timestamp 	= " . time() . " "
						. " WHERE id_contact_option = " . $option->id_option . " "; 
			} else {
				$query = "INSERT INTO " . PFX_MAIN_DB . "contact_option "
							. " (cop_dt_id_data_type, cop_label, cop_options, cop_status, cop_timestamp) "
							. " VALUES ("
								. " " . $option->id_data_type . ", "
								. " '" . mysql_real_escape_string($option->label) . "', "
								. " '" . mysql_real_escape_string($option->options) . "', "
								. "1," . time() . " "
							. ")";
			} 
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){ 
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record. ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		}
	}

	public function save_values( $id_contact ){
		if ( $id_contact > 0 ) {
			global $obj_bd;
			$resp = TRUE;
			foreach ($this->options as $k => $option) {
				$query = "SELECT id_contact_meta, cm_value FROM " . PFX_MAIN_DB . "contact_meta "
							. " WHERE cm_cop_id_contact_option = " . $option->id_option . " AND cm_co_id_contact = " . $id_contact . " "; 
				$exist = $obj_bd->query( $query );
				if ( $exist !== FALSE ){
					$skip = FALSE;
					if ( is_array($option->value) ){
						$value = "";
						foreach ($option->value as $k => $val) {
							$value  .= ($k>0 ? ";" : "") . $val;
						}
					} else {
						$value = $option->value;
					}
					if ( count( $exist ) > 0 ){ 
						if ( $option->value != $exist[0]['cm_value'] ){
							$query = " UPDATE " . PFX_MAIN_DB . "contact_meta SET "
										. " cm_value = '" . mysql_real_escape_string( $value ) . "' "
									. " WHERE cm_cop_id_contact_option = " . $option->id_option 
										. " AND cm_co_id_contact = " . $id_contact . " ";
						} else {
							$skip = TRUE;
						}
					} else {
						$query = " INSERT INTO " . PFX_MAIN_DB . "contact_meta (cm_cop_id_contact_option, cm_co_id_contact, cm_value ) "
								. " VALUES ( " . $option->id_option . ", " . $id_contact . ", '" . mysql_real_escape_string( $value ) . "' ) "; 
					}
					if ( !$skip ){
						$result = $obj_bd->execute( $query );
						$resp 	= ($resp && $result);
					}
				}
			} 
			return $resp;
		}
	}
	
	public function get_frm_control($option,  $pfx = "inp_", $css = ""){
		$resp = "";
		switch ($option->id_data_type) {
			case 1: //Binary 
				$resp .= "<div class='row'><div class='col-xs-12'><div class='input-group col-xs-5 pull-left'><span class='input-group-addon'> "
								. " <input type='radio' name='contact_option_" . $option->id_option . "' id='" . $pfx . "contact_option_" . $option->id_option . "_t' "
								. " value='true' " . ( $option->value ? "checked='checked'" : "" ) . " />" 
							. " </span><label class='form-control'> Verdadero </label></div>" 
						  	. "<div class='input-group col-xs-5 pull-right'><span class='input-group-addon'> "
								. " <input type='radio' name='contact_option_" . $option->id_option . "' id='" . $pfx . "contact_option_" . $option->id_option . "_f' "
								. " value='false' " . ( $option->value === false ? "checked='checked'" : "" ) . " />" 
							. " </span><label class='form-control'> Falso </label></div></div></div>";
				break;
			case 2: //Text
			case 3: //Number
			case 4: //Date
			case 5: //Email
			case 6: //Float
				$resp = "<input type='" . strtolower( $option->data_type ) . "' id='" . $pfx . "contact_option_" . $option->id_option . "' name='contact_option_" . $option->id_option . "' "
								. " class='form-control " . $css . "' value='" . $option->value . "' />";
				break;
			case 7: //RadioOption
				$opts = explode(';', $option->options );
				foreach ($opts as $k => $op) {
					$resp .= "<div class='input-group'><span class='input-group-addon'> "
								. " <input type='radio' name='contact_option_" . $option->id_option . "' id='" . $pfx . "contact_option_" . $option->id_option . "_" . $k . "' "
								. " value='" . $op . "' " . ( $option->value ? "checked='checked'" : "" ) . " />" 
							. " </span><label class='form-control'>" . $op . "</label></div>"; 
				}  
				break;
			case 8: //CheckOption
				$opts = explode(';', $option->options );
				$vals = explode(';', $option->value );
				foreach ($opts as $k => $op) {
					$resp .= "<div class='input-group'><span class='input-group-addon'> "
								. " <input type='checkbox' name='contact_option_" . $option->id_option . "[]' id='" . $pfx . "contact_option_" . $option->id_option . "_" . $k . "' "
								. " value='" . $op . "' " . ( in_array($option->value, $opts) ? "checked='checked'" : "" ) . " />"  
							. " </span><label class='form-control'>" . $op . "</label></div>"; 
				}   
				break;
			case 9:
				$opts = explode(';', $option->options );
				$resp = "<select id='" . $pfx . "contact_option_" . $option->id_option . "' name='contact_option_" . $option->id_option . "'"
						. " class='form-control " . $css . "' >";
				$resp .= "<option value='' " . ( $option->value == '' ? "selected='selected' " : "" ) . "> --- </option>";
				foreach ($opts as $k => $op) {
					$resp .= "<option value='" . $op . "' " . ( $option->value == $op ? "selected='selected' " : " " ) . ">" . $op . "</option>";
				}
				$resp .= "</select>";
				break;
			default:
				$resp = "";
				break;
		}
			
		return $resp;
	}
	
	public function get_frm_html( $pfx = "inp_", $div_css = "row", $inp_css = "" ){
		$resp = "";
		$last = count( $this->options ) - 1;
		foreach ($this->options as $key => $option) {
			$resp .= (($key%2 == 0) ? "<div class='row'>" : "") 
					. "<div class='" . $div_css . "' >"
					. "<label class='control-label'>" . $option->label .  "</label>"
					. $this->get_frm_control( $option, $pfx, $inp_css ) 
					. "</div>"
					. (( $key%2>0 || $key == $last ) ? "</div>" : "") ;
		}
		return $resp;
	}
	
	public function get_list_html( $edit = FALSE ){
		$resp = "";
		$li_css = "row";
		foreach ($this->options as $k => $option) {
			require DIRECTORY_VIEWS . "/lists/lst.contact_option.php"; 
		} 
		return $resp;
	}

	public function get_contact_list( $div_css = 'col-xs-12 col-sm-6', $lbl_css = 'col-xs-4', $val_css = ''  ){
		$resp = "";
		$li_css = "row";
		foreach ($this->options as $k => $option) {
			$resp .= " <div  class='" . $div_css . "'><p>"
					. "<label class='" . $lbl_css . "'>" . $option->label .  ":</label>"
					. "<span class='" . $val_css . "'>" . $option->value .  "</span>"
					. "</p></div>"; 
		} 
		return $resp;
	}
	
	/**
	* set_error()
	* Adds the error to the errors array and writes the error to Log
	* 
	* @param         $err String 
	* @param 		 $type Integer
	* @param 		 $lvl Integer (optional) 
	* 
	*/  
	private function set_error( $err , $type, $lvl = 1 ){
		global $Log;
		$this->error[] = $err;
		$Log->write_log( " ERROR @ Class User (" . $this->id_user . "): " . $err, $type, $lvl );
	} 
	
	/**
	* get_errors()
	* Adds the error to the errors array and writes the error to Log
	* 
	* @param        $html Boolean
	 * 
	 * @return		String of concatenated errors.  
	* 
	*/  
	public function get_errors( $html = TRUE ){
		$resp = ""; 
		foreach ($this->error as $key => $err) {
			$resp .= $err . ($html) ? "<br/>" : "\n";
		}
		return $resp;
	}
	
	/**
	* set_msg()
	* Writes the message to the Log, optionally adds the message to the global message string.
	* 
	* @param         $action String 
	* @param 		 $msg String
	* @param 		 $echo String (optional) if set adds message to global variable.  
	* 
	*/  
	private function set_msg( $action , $msg , $echo = ''){
		global $Log;
		global $mensaje;
		$Log->write_log( $action . " @ Class ContactMeta: " . $msg );
		if ( $echo != '') $mensaje .= $echo . " <br/> ";
	}
}
?>