<?php
/**
* Proyect CLass
* 
* @package		SF Tracker			
* @since        18/05/2014 
* 
*/ 
class Proyect{
	
	public $id_proyect; 
	public $proyect;
	
	public $id_company;
	public $company;
	public $id_region;
	public $region;
	public $id_proyect_type;
	public $proyect_type;
	public $shift_start;
	public $shift_end;
	public $str_workdays;
	public $workdays;
	public $day_visits; 
	
	public $timestamp;
	
	public $cycles = array();
	public $error = array();
	
	/**
	* User()    
	* Creates a User object from the DB.
	*  
	* @param	$id_proyect (optional) If set populates values from DB record. 
	* 
	*/  
	function Proyect( $id_proyect = 0 ){
		global $obj_bd;
		$this->error = array();
		if ( $id_proyect > 0 ){
			$query = "SELECT "
							. " id_proyect, id_proyect_type, id_company, id_region, "
							. " pt_proyect_type, cm_company, re_region, pr_proyect, pr_day_visits,"
							. " pr_shift_start, pr_shift_end, pr_workdays, pr_timestamp, pr_status "
						. " FROM " . PFX_MAIN_DB . "proyect " 
							. " INNER JOIN " . PFX_MAIN_DB . "proyect_type ON id_proyect_type = pr_pt_id_proyect_type "
							. " INNER JOIN " . PFX_MAIN_DB . "company ON id_company = pr_cm_id_company  "
							. " INNER JOIN " . PFX_MAIN_DB . "region ON id_region = pr_re_id_region "
					. " WHERE id_proyect = " . $id_proyect; 
			$info = $obj_bd->query( $query );
			if ( $info !== FALSE ){
				if ( count($info) > 0 ){
					$usr = $info[0];
					$this->id_proyect 		= $usr['id_proyect'];
					$this->id_proyect_type	= $usr['id_proyect_type'];
					$this->id_company 		= $usr['id_company'];
					$this->id_region 		= $usr['id_region'];
					
					$this->proyect	 		= $usr['pr_proyect'];
					$this->proyect_type		= $usr['pt_proyect_type'];
					$this->company		 	= $usr['cm_company'];
					$this->region		 	= $usr['re_region']; 
					 
					$this->day_visits	 	= $usr['pr_day_visits'];
					$this->shift_start	 	= $usr['pr_shift_start'];
					$this->shift_end	 	= $usr['pr_shift_end'];
					$this->str_workdays	 	= $usr['pr_workdays'];
					$this->workdays		 	= explode(';',$usr['pr_workdays']);
					
					$this->timestamp	 	= $usr['pr_timestamp'];
					
				} else {
					$this->clean();
					$this->set_error( "Proyect not found (" . $id_proyect . "). ", ERR_DB_NOT_FOUND, 2 ); 
				}
			} else {
				$this->clean();
				$this->set_error( "An error ocurred while querying the Data Base. ", ERR_DB_QRY, 2 );
			} 
		} else {
			$this->clean();
		}  
	}
  
	 
	/**
	* validate()    
	* Validates the values before inputing to Data Base 
	*  
	* @return        Boolean TRUE if valid; FALSE if invalid
	*/ 
	public function validate(){ 
		global $Validate; 
		if ( !$this->proyect != '' ){
			$this->set_error( 'Proyect value empty. ', ERR_VAL_EMPTY );
			return FALSE;
		} 
		if ( !$this->id_proyect_type > 0 || !$Validate->exists( 'proyect_type', 'id_proyect_type', $this->id_proyect_type)){
			$this->set_error( 'Invalid proyect type. ', ERR_VAL_EMPTY );
			return FALSE;
		}
		if ( !$this->id_company > 0 || !$Validate->exists( 'company', 'id_company', $this->id_company)){
			$this->set_error( 'Invalid company. ', ERR_VAL_EMPTY );
			return FALSE;
		}
		if ( !$this->id_region > 0 || !$Validate->exists( 'region', 'id_region', $this->id_region)){
			$this->set_error( 'Invalid region. ', ERR_VAL_EMPTY );
			return FALSE;
		}
		if ( ! $Validate->is_int_between($this->shift_start, 0, 23) ){
			$this->set_error( 'Invalid Shift start. ', ERR_VAL_INVALID );
			return FALSE;
		}
		if ( ! $Validate->is_int_between($this->shift_end, 0, 23) ){
			$this->set_error( 'Invalid Shift end. ', ERR_VAL_INVALID );
			return FALSE;
		}
		if ( ! $Validate->is_int_between($this->day_visits, 1, 16) ){
			$this->set_error( 'Invalid day visits. ', ERR_VAL_INVALID );
			return FALSE;
		}
		if ( !count($this->workdays) > 0 ){
			$this->set_error( 'Invalid workdays. ', ERR_VAL_INVALID );
			return FALSE;
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
			if ( $this->id_proyect > 0 ){
				$query = " UPDATE " . PFX_MAIN_DB . "proyect SET " 
							. " pr_pt_id_proyect_type = '" . $this->id_proyect_type . "', "
							. " pr_cm_id_company = '" . $this->id_company 	. "', "
							. " pr_re_id_region  = '" . $this->id_region 	. "', " 
							. " pr_proyect 	 	 = '" . mysql_real_escape_string( $this->proyect ) . "', "
							. " pr_shift_start   = '" . $this->shift_start 	. "', "
							. " pr_shift_end	 = '" . $this->shift_end	. "', " 
							. " pr_workdays		 = '" . $this->workdays 	. "', " 
							. " pr_day_visits	 = '" . $this->day_visits 	. "', " 
							. " pr_status	 = 1, "
							. " pr_timestamp = " . time() . " "
						. " WHERE id_proyect = " . $this->id_proyect . " ";
			} else {
				$query = "INSERT INTO " . PFX_MAIN_DB . "proyect " 
						. "( pr_pt_id_proyect_type, pr_cm_id_company, pr_re_id_region, "
							. " pr_proyect, pr_shift_start, pr_shift_end, pr_workdays, " 
							. " pr_day_visits, pr_status, pr_timestamp ) "
						. " VALUES ("
						. " '" . $this->id_proyect_type  . "', "
						. " '" . $this->id_company  . "', "
						. " '" . $this->id_region  . "', "  
						. " '" . mysql_real_escape_string( $this->proyect ) . "', "
						. " '" . $this->shift_start . "', "
						. " '" . $this->shift_end . "', "  
						. " '" . $this->workdays . "', "   
						. " '" . $this->day_visits . "', "  
						. " 1, " . time() . " "  
						. ")";
			} 
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){
				if ( $this->id_proyect == 0){
					$this->id_proyect = $obj_bd->get_last_id(); 
					return $this->create_entities(); 
				}
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record. ", ERR_DB_EXEC, 3 );
				return FALSE;
			} 
		}
	} 

	private function create_entities(){
		if ( IS_ADMIN ){
			if ( $this->id_proyect > 0 ){
				$pfx = PFX_MAIN_DB . $this->id_proyect ."_";
				$sql_cycle = "CREATE TABLE IF NOT EXISTS " . $pfx . "cycle ( cy_from int(11) NOT NULL, cy_to int(11) NOT NULL, PRIMARY KEY (cy_from,cy_to) );";
				$sql_fday  = "CREATE TABLE IF NOT EXISTS " . $pfx . "free_day ( id_free_day int(11) NOT NULL AUTO_INCREMENT, fd_date_string varchar(45) DEFAULT NULL, fd_date_timestamp int(11) NOT NULL, PRIMARY KEY (id_free_day) );";
				$sql_media = "CREATE TABLE IF NOT EXISTS " . $pfx . "media_file ( " 
							. " id_media_file int(11) NOT NULL AUTO_INCREMENT, mf_ft_id_file_type int(11) NOT NULL, mf_title varchar(64) NOT NULL, mf_description text, " 
							. " mf_route varchar(64) NOT NULL, mf_status int(11) NOT NULL DEFAULT '1', mf_timestamp int(11) NOT NULL DEFAULT '0', " 
							. " PRIMARY KEY (id_media_file), KEY mf_ft_id_file_type_idx_" . $this->id_proyect . " (mf_ft_id_file_type) " 
							. " CONSTRAINT mf_ft_id_file_type FOREIGN KEY (mf_ft_id_file_type) REFERENCES " . PFX_MAIN_DB . "file_type (id_file_type) ON DELETE NO ACTION ON UPDATE NO ACTION ) DEFAULT CHARSET=utf8;";
							
				$sql_ad 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "address ( id_address int(11) NOT NULL AUTO_INCREMENT, " 
								. " ad_street varchar(128) DEFAULT NULL, ad_ext_num varchar(24) DEFAULT NULL, ad_int_num varchar(24) DEFAULT NULL, " 
								. " ad_locality varchar(64) DEFAULT NULL, ad_city varchar(64) DEFAULT NULL, ad_st_id_state int(11) DEFAULT NULL, " 
								. " ad_brick varchar(40) DEFAULT NULL, ad_district varchar(120) DEFAULT NULL, ad_zipcode int(11) DEFAULT NULL, " 
  								. " PRIMARY KEY (id_address), KEY ad_st_id_state_idx (ad_st_id_state), " 
								. " CONSTRAINT ad_st_id_state FOREIGN KEY (ad_st_id_state) REFERENCES " . PFX_MAIN_DB . "state (id_state) ON DELETE NO ACTION ON UPDATE NO ACTION ) DEFAULT CHARSET=utf8 ";
						
				$sql_pvca 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "pdv_category (  id_pdv_category int(11) NOT NULL AUTO_INCREMENT, pvca_pvca_id_parent int(11) DEFAULT NULL, pvca_pdv_category varchar(64) DEFAULT NULL,"
	  							. " pvca_status int(11) NOT NULL DEFAULT '1', pvca_timestamp int(11) NOT NULL DEFAULT '0', PRIMARY KEY (id_pdv_category) "    
	  							. " ) DEFAULT CHARSET=utf8"; 
	  							
				$sql_pvca_2 = "ALTER TABLE " . $pfx . "pdv_category "   
	  							. " ADD CONSTRAINT pvca_pvca_id_parent FOREIGN KEY (pvca_pvca_id_parent ) REFERENCES " . $pfx . "pdv_category (id_pdv_category ) ON DELETE SET NULL ON UPDATE NO ACTION, "   
	  							. " ADD INDEX pvca_pvca_id_parent_idx_" . $this->id_proyect . " (pvca_pvca_id_parent ASC) ;";
						
				$sql_pdv 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "pdv ( " 
								. " id_pdv int(11) NOT NULL AUTO_INCREMENT, pdv_pvt_id_pdv_type int(11) DEFAULT NULL, pdv_pvca_id_pdv_category int(11) DEFAULT '1', pdv_ad_id_address int(11) DEFAULT NULL, pdv_name varchar(64) NOT NULL, " 
								. " pdv_latitude decimal(23,20) NOT NULL DEFAULT '0.00000000000000000000', pdv_longitude decimal(23,20) NOT NULL DEFAULT '0.00000000000000000000', pdv_id_viamente varchar(45) DEFAULT NULL, " 
								. " pdv_zone varchar(24) DEFAULT NULL, pdv_status int(11) NOT NULL DEFAULT '1', pdv_timestamp int(11) NOT NULL DEFAULT '0', " 
								. " PRIMARY KEY (id_pdv), KEY pdv_pvt_id_pdv_type_idx_" . $this->id_proyect . " (pdv_pvt_id_pdv_type), KEY pdv_ad_id_address_idx_" . $this->id_proyect . " (pdv_ad_id_address), "
									. " KEY pdv_pvca_id_pdv_category_idx_" . $this->id_proyect . " (pdv_pvca_id_pdv_category), "
  								. " CONSTRAINT pdv_pvca_id_pdv_category FOREIGN KEY (pdv_pvca_id_pdv_category) REFERENCES " . $pfx . "pdv_category (id_pdv_category) ON DELETE SET NULL ON UPDATE NO ACTION, " 
								. " CONSTRAINT pdv_ad_id_address FOREIGN KEY (pdv_ad_id_address) REFERENCES " . $pfx . "address (id_address) ON DELETE SET NULL ON UPDATE SET NULL, " 
								. " CONSTRAINT pdv_pvt_id_pdv_type FOREIGN KEY (pdv_pvt_id_pdv_type) REFERENCES " . PFX_MAIN_DB . "pdv_type (id_pdv_type) ON DELETE SET NULL ON UPDATE SET NULL ) DEFAULT CHARSET=utf8 "; 
				
				$sql_pvc	= "CREATE TABLE IF NOT EXISTS " . $pfx . "pdv_contact ( "
  								. " id_pdc_contact int(11) NOT NULL AUTO_INCREMENT, pvc_pdv_id_pdv int(11) NOT NULL, pvc_business_name varchar(128) DEFAULT NULL, pvc_rfc varchar(18) DEFAULT NULL,"
  								. " pvc_phone_1 varchar(24) DEFAULT NULL, pvc_phone_2 varchar(24) DEFAULT NULL, pvc_email varchar(45) DEFAULT NULL," 
  								. " PRIMARY KEY (id_pdc_contact), KEY pvc_pdv_id_pdv_idx_" . $this->id_proyect . " (pvc_pdv_id_pdv) ) DEFAULT CHARSET=utf8 ;";
				
				$sql_pvd 	= " CREATE TABLE IF NOT EXISTS " . $pfx . "pdv_drugstore ( "
  								. " id_pdv_drugstore int(11) NOT NULL AUTO_INCREMENT, pvd_pdv_id_pdv int(11) NOT NULL, pvd_attendant varchar(128) DEFAULT NULL, pvd_employees int(11) DEFAULT NULL,  "
  								. " pvd_control_management int(11) DEFAULT '0', pvd_otc int(11) DEFAULT '0', "
  								. " pvd_su_id_supplier_1 int(11) DEFAULT NULL, pvd_su_id_supplier_2 int(11) DEFAULT NULL, pvd_su_id_supplier_3 int(11) DEFAULT NULL, "
  								. " PRIMARY KEY (id_pdv_drugstore), KEY pvd_pdv_id_pdv_idx_" . $this->id_proyect . " (pvd_pdv_id_pdv), KEY pvd_su_id_supplier_1_idx_" . $this->id_proyect . " (pvd_su_id_supplier_1),  "
  								. " KEY pvd_su_id_supplier_2_idx_" . $this->id_proyect . " (pvd_su_id_supplier_2), KEY pvd_su_id_supplier_3_idx_" . $this->id_proyect . " (pvd_su_id_supplier_3), "
  								. " CONSTRAINT pvd_pdv_id_pdv 		FOREIGN KEY (pvd_pdv_id_pdv) 		REFERENCES " . $pfx . "pdv (id_pdv) ON DELETE CASCADE  ON UPDATE NO ACTION, "
  								. " CONSTRAINT pvd_su_id_supplier_1 FOREIGN KEY (pvd_su_id_supplier_1) 	REFERENCES " . PFX_MAIN_DB . "supplier (id_supplier) ON DELETE SET NULL ON UPDATE NO ACTION, "
  								. " CONSTRAINT pvd_su_id_supplier_2 FOREIGN KEY (pvd_su_id_supplier_2) 	REFERENCES " . PFX_MAIN_DB . "supplier (id_supplier) ON DELETE SET NULL ON UPDATE NO ACTION, "
  								. " CONSTRAINT pvd_su_id_supplier_3 FOREIGN KEY (pvd_su_id_supplier_3) 	REFERENCES " . PFX_MAIN_DB . "supplier (id_supplier) ON DELETE SET NULL ON UPDATE NO ACTION "
  								. " ) DEFAULT CHARSET=utf8 ";
				
				$sql_pvi 	= " CREATE TABLE IF NOT EXISTS " . $pfx . "pdv_info ( "
  								. " id_pdv_info int(11) NOT NULL AUTO_INCREMENT, pvi_pdv_id_pdv int(11) NOT NULL, pvi_fr_id_frequency int(11) DEFAULT NULL, "
  								. " pvi_weekdays varchar(24) DEFAULT NULL, pvi_schedule_from int(11) DEFAULT NULL, pvi_schedule_to int(11) DEFAULT NULL, "
  								. " PRIMARY KEY (id_pdv_info), KEY pvi_pdv_id_pdv_idx_" . $this->id_proyect . " (pvi_pdv_id_pdv), KEY pvi_fr_id_frequency_idx_" . $this->id_proyect . " (pvi_fr_id_frequency) "
  								. " CONSTRAINT pvd_pdv_id_pdv 		FOREIGN KEY (pvd_pdv_id_pdv) 		REFERENCES " . $pfx 		. "pdv (id_pdv) ON DELETE CASCADE  ON UPDATE NO ACTION, "
  								. " CONSTRAINT pvi_fr_id_frequency	FOREIGN KEY (pvi_fr_id_frequency) 	REFERENCES " . PFX_MAIN_DB  . "frequency (id_frequency) ON DELETE SET NULL  ON UPDATE NO ACTION, "
  								. " ) DEFAULT CHARSET=utf8";
				
				$sql_pc 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "product_category ( "
  								. " id_product_category int(11) NOT NULL AUTO_INCREMENT, pc_product_category varchar(64) NOT NULL, pc_pc_id_parent int(11) DEFAULT NULL,"
  								. " PRIMARY KEY (id_product_category) ) DEFAULT CHARSET=utf8";
  								
				$sql_pc2 	= "ALTER TABLE " . $pfx . "product_category "   
	  							. " ADD CONSTRAINT pc_pc_id_parent FOREIGN KEY (pc_pc_id_parent ) REFERENCES " . $pfx . "product_category (id_product_category ) ON DELETE SET NULL ON UPDATE NO ACTION, "   
	  							. " ADD INDEX pc_pc_id_parent_idx_" . $this->id_proyect . " (pc_pc_id_parent ASC) ;"; 
				
				$sql_pd 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "product ( "
  								. " id_product int(11) NOT NULL AUTO_INCREMENT, pd_pc_id_product_category int(11) NOT NULL DEFAULT '1', pd_product varchar(64) DEFAULT NULL, "
  								. " pd_rival int(11) DEFAULT '0', pd_sku varchar(45) DEFAULT NULL, pd_timestamp varchar(45) DEFAULT NULL, pd_status varchar(45) NOT NULL DEFAULT '1', "
  								. " PRIMARY KEY (id_product), KEY pd_pc_id_product_category_idx_" . $this->id_proyect . " (pd_pc_id_product_category), "
  								. " CONSTRAINT pd_pc_id_product_category FOREIGN KEY (pd_pc_id_product_category) REFERENCES " . $pfx . "product_category (id_product_category) ON DELETE NO ACTION ON UPDATE NO ACTION "
  								. " ) DEFAULT CHARSET=utf8 ";
  								
  				$sql_pz 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "prize ( "
  								. " id_prize int(11) NOT NULL AUTO_INCREMENT, pz_prize decimal(10,2) NOT NULL DEFAULT '0.00', pz_pp_id_product_presentacion int(11) DEFAULT NULL, pz_pd_id_product int(11) NOT NULL, pz_units int(11) NOT NULL, "
  								. " PRIMARY KEY (id_prize), KEY pz_pp_id_product_presentacion_idx (pz_pp_id_product_presentacion), KEY pz_pd_id_product_idx (pz_pd_id_product) "
  								. " CONSTRAINT pz_pd_id_product 			 FOREIGN KEY (pz_pd_id_product) 			 REFERENCES " . $pfx . "product (id_product_presentation) ON DELETE SET NULL ON UPDATE SET NULL "
  								. " CONSTRAINT pz_pp_id_product_presentacion FOREIGN KEY (pz_pp_id_product_presentacion) REFERENCES " . PFX_MAIN_DB . "product_presentation (id_product_presentation) ON DELETE SET NULL ON UPDATE SET NULL "
  								. " ) DEFAULT CHARSET=utf8 ";
				
				$sql_ev 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "evidence ( id_evidence int(11) NOT NULL AUTO_INCREMENT, ev_vi_id_visit int(11) NOT NULL, ev_et_id_evidence_type int(11) NOT NULL, ev_text text, ev_route varchar(64) NOT NULL, ev_timestamp int(11) NOT NULL DEFAULT '1', " 
								. " PRIMARY KEY (id_evidence), KEY ev_et_id_evidence_type_idx_" . $this->id_proyect . " (ev_et_id_evidence_type), "
								. " KEY ev_vi_id_visit_idx_" . $this->id_proyect . " (ev_vi_id_visit), "
								. " CONSTRAINT ev_vi_id_visit FOREIGN KEY (ev_vi_id_visit) REFERENCES " . $pfx . "visit (id_visit) ON DELETE CASCADE ON UPDATE NO ACTION, " 
								. " CONSTRAINT ev_et_id_evidence_type FOREIGN KEY (ev_et_id_evidence_type) REFERENCES " . PFX_MAIN_DB . "evidence_type (id_evidence_type) ON DELETE NO ACTION ON UPDATE NO ACTION )  DEFAULT CHARSET=utf8;";
				
				$sql_or 	= "CREATE TABLE IF NOT EXISTS " . $pfx . "order ( " 
								. " id_order int(11) NOT NULL AUTO_INCREMENT, or_pdv_id_pdv int(11) DEFAULT NULL, or_us_id_user int(11) DEFAULT NULL, or_date int(11) NOT NULL, or_su_id_supplier int(11) NOT NULL, " 
								. " or_ba_id_branch int(11) DEFAULT NULL, or_vi_id_visit int(11) DEFAULT NULL, or_client_code varchar(64) DEFAULT NULL, or_agent_number varchar(64) DEFAULT NULL, " 
								. " or_confirmation_code varchar(64) DEFAULT NULL, or_status int(11) DEFAULT '1', or_timestamp int(11) DEFAULT '0', " 
								. " PRIMARY KEY (id_order), KEY or_pdv_id_pdv_idx_" . $this->id_proyect . " (or_pdv_id_pdv), KEY or_us_id_user_idx_" . $this->id_proyect . " (or_us_id_user), "
								. " KEY or_su_id_supplier_idx_" . $this->id_proyect . " (or_su_id_supplier), KEY or_ba_id_branch_idx_" . $this->id_proyect . " (or_ba_id_branch), " 
								. " CONSTRAINT or_ba_id_branch 	 FOREIGN KEY (or_ba_id_branch) REFERENCES " . PFX_MAIN_DB . "branch (id_branch) ON DELETE NO ACTION ON UPDATE NO ACTION, " 
								. " CONSTRAINT or_pdv_id_pdv 	 FOREIGN KEY (or_pdv_id_pdv) REFERENCES " . $pfx . "pdv (id_pdv) ON DELETE SET NULL ON UPDATE NO ACTION, " 
								. " CONSTRAINT or_su_id_supplier FOREIGN KEY (or_su_id_supplier) REFERENCES " . PFX_MAIN_DB . "supplier (id_supplier) ON DELETE NO ACTION ON UPDATE NO ACTION, " 
								. " CONSTRAINT or_us_id_user FOREIGN KEY (or_us_id_user) 	REFERENCES "  . PFX_MAIN_DB . "user (id_user) ON DELETE SET NULL ON UPDATE NO ACTION " 
								. " ) DEFAULT CHARSET=utf8 "; 
			}
		} 
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
			$query = " UPDATE "  . PFX_MAIN_DB . "proyect SET "
						. " pr_status = 0 "
					. " WHERE id_proyect = " . $this->id_proyect . " ";
			$result = $obj_bd->execute( $query );
			if ( $result !== FALSE ){
				$this->clean();
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to set status to 0. ", ERR_DB_EXEC, 3 );
				return FALSE;
			} 
		}
	}
	 
	/**
	 * get_array()
	 * returns an Array with proyect information
	 * 
	 * @param 	$full Boolean if TRUE returns Proyect and Instance Arrays (default FALSE)
	 * 
	 * @return	$array Array width User information
	 */
	 public function get_array( ){
	 	$array = array(
	 					'id_proyect' 		=>	$this->id_proyect, 
	 					'proyect' 			=>	$this->proyect,
	 					
	 					'id_company' 		=>	$this->id_company, 
	 					'company' 			=>	$this->company,
	 					'id_proyect_type' 	=>	$this->id_proyect_type, 
	 					'proyect_type' 		=>	$this->proyect_type,
	 					'id_region' 		=>	$this->id_region, 
	 					'region' 			=>	$this->region,
	 					
	 					'shift_start'		=>	$this->shift_start,
	 					'shift_end'	 		=>	$this->shift_end, 
	 					'workdays'			=>	$this->workdays,
	 					'day_visits'		=> 	$this->day_visits,
	 					
	 					'timestamp'			=>	$this->timestamp 
					); 
		return $array;
	 }
	
	/**
	 * get_info_html()
	 * returns a String of HTML with user information
	 * 
	 * @param 	$full Boolean if TRUE returns Contact and Instance Arrays (default FALSE)
	 * 
	 * @return	$html String html user info template
	 */
	 public function get_info_html( $full = FALSE ){
	 	$html  = "";
		$proyect = $this;
		ob_start();
		require_once DIRECTORY_VIEWS . "proyect/info.proyect.php"; 
		$html .= ob_get_contents();
		ob_end_clean();
		
		return str_replace(array("\n", "\t"), "", $html);
	 }
	 
	
	
	/**
	* clean()    
	* Cleans all parameters and resets all objects
	*  
	*/  
	public function clean(){
		$this->id_proyect 	=  0;
		$this->proyect 		= "";
		
		$this->id_company 	=  0;
		$this->company 		= "";
		$this->id_region 	=  0;
		$this->region 		= "";
		$this->id_proyect_type 	=  0;
		$this->proyect_type = "";
		
		$this->shift_start 	= 0;
		$this->shift_end 	= 0; 
		$this->workdays		= array(); 
		$this->day_visits	= 0;
		
		$this->timestamp 	= 0;
		 
		$this->error = array(); 
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
		$Log->write_log( " ERROR @ Class Proyect (" . $this->id_proyect . "): " . $err, $type, $lvl );
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
	public function get_errors( $html = true ){
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
		$Log->write_log( $action . " @ Class Proyect (" . $this->id_proyect . "): " . $msg );
		if ( $echo != '') $mensaje .= $echo . " <br/> ";
	}
}

?>