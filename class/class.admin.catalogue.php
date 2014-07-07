<?php
/**
* Contact CLass
* 
* @package		SF Tracker			
* @since        19/06/2014
* @author		Manuel Fernández
*/ 

class CatalogueAdmin extends Object{
	
	private $table 	= "";
	private $pfx 	= "";
	private $col	= "";
	private $col_parent	= ""; 
	
	public  $id		= 0;
	public  $id_parent = 0;
	public 	$lbl 	= "";
	public 	$lbl_parent = "";
	public  $value 	= ""; 
	
	/**
	* CatalogueAdmin()    
	* Creates a User object from the DB.
	*  
	* @param 	$table 
	* @param	$id (optional) If set populates values from DB record. 
	*/  
	function CatalogueAdmin( $table, $id = 0 ){ 
		$this->class = "CatalogueAdmin";
		if ( !$table || $table == '' ){
			$this->set_error("Wrong Catalogue", ERR_VAL_EMPTY );
			return FALSE;
		} 
		$this->error = array();
		$this->set_table( $table );
		$this->set_value( $id );
	}
	
	/**
	* set_table()    
	* Sets the DB table configuration for the catalogue.
	*  
	* @param 	$table  Catalogue table
	*/  
	private function set_table( $table ){
		switch ( $table ){
			case 'company':
				$this->table 	= "company";
				$this->pfx	 	= "cm_";
				$this->col 	 	= "cm_company";
				$this->lbl		= "Compañía";
				$this->col_parent = "";
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
			case 'country':
				$this->table 	= "country";
				$this->pfx	 	= "cnt_";
				$this->col 	 	= "cnt_country";
				$this->lbl		= "País";
				$this->col_parent = "";
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
			case 'evidence_type':
				$this->table 	= "evidence_type";
				$this->pfx	 	= "et_";
				$this->col 	 	= "et_evidence_type";
				$this->lbl		= "Tipos de Evidencia";
				$this->col_parent = "";
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
			case 'region':
				$this->table 	= "region";
				$this->pfx	 	= "re_";
				$this->col 	 	= "re_region";
				$this->lbl		= "Región";
				$this->col_parent = "";
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
			case 'state':
				$this->table 	= "state";
				$this->pfx	 	= "st_";
				$this->col 	 	= "st_state";
				$this->lbl		= "Estado";
				$this->col_parent = "st_cnt_id_country";
				$this->lbl_parent = "País";
				$this->parent	= "country";
				break;
			case 'task_omition_cause':
				$this->table 	= "task_omition_cause";
				$this->pfx	 	= "toc_";
				$this->col 	 	= "toc_task_omition_cause";
				$this->lbl		= "Motivo de Omisión de Tarea";
				$this->col_parent = "toc_tt_id_task_type";
				$this->lbl_parent = "Tipo de Tarea";
				$this->parent	= "task_type";
				break;
			case 'supplier':
				$this->table 	= "supplier";
				$this->pfx	 	= "su_";
				$this->col 	 	= "su_supplier";
				$this->lbl		= "Mayorista";
				$this->col_parent = "";
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
			case 'visit_reschedule_cause':
				$this->table 	= "visit_reschedule_cause";
				$this->pfx	 	= "vrc_";
				$this->col 	 	= "vrc_visit_reschedule_cause";
				$this->lbl		= "Motivo de Reagendación";
				$this->col_parent = "";
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
			default:
				$this->table 	= "";
				$this->pfx	 	= "";
				$this->col 	 	= "";
				$this->lbl		= "";
				$this->col_parent = ""; 
				$this->lbl_parent = "";
				$this->parent	= FALSE;
				break;
		}
		$this->id 		 = 0;
		$this->id_parent = 0;
		$this->value 	 = "";
	}
	
	public function set_value( $id = 0 ){
		if ( $id > 0 ){
			global $obj_bd;
			$query = "SELECT "
							. " id_" . $this->table . ", " . $this->col . " "
							. ( ( $this->parent ) ? ", " . $this->set_table . " " : " ") 		
						. " FROM " . PFX_MAIN_DB . $this->table . " " 
					. " WHERE id_" . $this->table . " = " . $id ; 
					
			$info = $obj_bd->query( $query );
			if ( $info !== FALSE ){
				if ( count($info) > 0 ){
					$usr = $info[0];
					$this->id 		= $usr['id_' . $this->table ];
					$this->value	= $usr[ $this->col ];
					if ( $this->parent ){
						$this->id_parent	= $usr[ $this->col_parent ];
					} 
				} else { 
					$this->set_error( "Catalogue " . $this->table . " value not found (" . $id_contact . "). ", ERR_DB_NOT_FOUND, 2 ); 
				}
			} else { 
				$this->set_error( "An error ocurred while querying the Data Base. ", ERR_DB_QRY, 2 );
			} 
		} 
	}
 
	/**
	* validate()    
	* Validates the values before saving to Data Base 
	*  
	* @return        Boolean TRUE if valid; FALSE if invalid
	*/ 
	public function validate(){ 
		global $Validate; 
		if ( !$this->table != '' ){
			$this->set_error( 'Invalid table. ', ERR_VAL_INVALID, 2 );
			return FALSE;
		} 
		if ( !$this->value != '' ){
			$this->set_error( 'Name value empty. ', ERR_VAL_EMPTY );
			return FALSE;
		} 
		if ( $this->parent ){
			if ( !($Validate->is_integer( $this->id_parent ) && $this->id_parent > 0) ){
				$this->set_error( 'Invalid parent value. ', ERR_VAL_NOT_INT );
				return FALSE;	
			}
		}
		return TRUE; 
	}
	
	/**
	* save()    
	* Inserts or Update the record in the DB. 
	* 
	*/  
	public function save(){
		if ( $this->validate() ){
			global $obj_bd;
			if ( $this->id > 0 ){
				$query = " UPDATE " . PFX_MAIN_DB . $this->table . " SET " 
							. $this->col . " = '" . mysql_real_escape_string( $this->value ) . "', "
							. ( $this->parent 
									? " " . $this->col_parent . " = " . $this->id_parent . ", " 
									: "" 
								) 
							. " " . $this->pfx . "status	 = 1, "
							. " " . $this->pfx . "timestamp = " . time() . " "
							. " WHERE id_" . $this->table ." = " . $this->id . " ";
			} else {
				$query = "INSERT INTO " . PFX_MAIN_DB . $this->table . " " 
						. "( " 	. $this->col. ", " 
								. ( $this->parent ? " " . $this->col_parent . ", " : " " ) 
								. $this->pfx . "status, " 
								. $this->pfx . "timestamp ) "
						. " VALUES ("
						. " '" . mysql_real_escape_string( $this->value ) . "', " 
						. ( $this->parent ? " " . $this->id_parent . ", " : " " )  
						. " 1, " . time() . " "  
						. ")"; 
			} 
			//echo $query;
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){ 
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record to catalogue " . $this->table . ". ", ERR_DB_EXEC, 3 );
				return FALSE;
			} 
		} // validate() 
		else return FALSE;
	} 
	 
	/**
	* delete()    
	* Changes status from User to 0 in the DB.. 
	*
	* @return 	TRUE on success; FALSE otherwise 
	*/  
	public function delete(){
		if ( IS_ADMIN ){
			global $obj_bd;
			$query = " UPDATE " . PFX_MAIN_DB . $this->table . " SET "
						. " " . $this->pfx . "status = 0 "
					. " WHERE id_" . $this->table ." = " . $this->id . " ";
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){ 
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to set status to on catalogue " . $this->table . ". ", ERR_DB_EXEC, 3 );
				return FALSE;
			} 
		}
	}
	
	public function get_array(){
		return array(
				'catalogue'	=> $this->table,
				'id' 		=> $this->id,
				'value'		=> $this->value,
				'id_parent' => $this->id_parent
			);
	}
	
}

?>