<?php

class Catalogue{ 

	public function Catalogue(){
		
	} 
	
	
	/**
	 * function get_catalgue_options()
	 * Returns an array of catalogue options 
	 * 
	 * @param 		$which 		String 	catalogue to query
	 * @param 		$opt	 	Boolean	if TRUE returns an array for lists od ID's and options.   
	 * 
	 * @return		$html		Array	An array of information from a catalogue , FALSE on error
	 * 
	 */ 
	public function get_catalogue( $which = '', $opt = FALSE, $extra = FALSE ){
		if ($which != ''){
			switch ($which){
				case 'company':
					$query = "SELECT * " . ( $opt ? ", id_company as id, cm_company as opt " : "") . " FROM " . PFX_MAIN_DB . "company ORDER BY id_company "; 
					break;
				case 'country':
					$query = "SELECT * " . ( $opt ? ", id_country as id, cnt_country as opt " : "") . " FROM " . PFX_MAIN_DB . "country ORDER BY id_country "; 
					break;
				case 'data_type':
					$query = "SELECT * " . ( $opt ? ", id_data_type as id, dt_data_type as opt " : "") . " FROM " . PFX_MAIN_DB . "data_type ORDER BY id_data_type "; 
					break;
				case 'file_type':
					$query = "SELECT * " . ( $opt ? ", id_file_type as id, ft_file_type as opt " : "") . " FROM " . PFX_MAIN_DB . "file_type ORDER BY id_file_type ";
					break; 
				case 'media_type':
					$query = "SELECT * " . ( $opt ? ", id_media_type as id, mt_media_type as opt " : "") . " FROM " . PFX_MAIN_DB . "media_type ORDER BY id_media_type ";
					break; 
				case 'proyect_type':
					$query = "SELECT * " . ( $opt ? ", id_proyect_type as id, pt_proyect_type as opt " : "") . " FROM " . PFX_MAIN_DB . "proyect_type ORDER BY id_proyect_type "; 
					break;
				case 'pdv_type':
					$query = "SELECT * " . ( $opt ? ", id_pdv_type as id, pvt_pdv_type as opt " : "") . " FROM " . PFX_MAIN_DB . "pdv_type ORDER BY id_pdv_type ";
					break; 
				case 'task_type':
					$query = "SELECT * " . ( $opt ? ", id_task_type as id, tt_task_type as opt " : "") . " FROM " . PFX_MAIN_DB . "task_type ORDER BY id_task_type ";
					break; 
				case 'visit_status':
					$query = "SELECT * " . ( $opt ? ", id_visit_status as id, vs_visit_status as opt " : "") . " FROM " . PFX_MAIN_DB . "visit_status ORDER BY id_visit_status ";
					break; 
				case 'region':
					$query = "SELECT * " . ( $opt ? ", id_region as id, re_region as opt " : "") . " FROM " . PFX_MAIN_DB . "region ORDER BY id_region "; 
					break;
				case 'profiles':
					$query = "SELECT * " . ( $opt ? ", id_profile as id, pf_profile as opt " : "") . " FROM " . PFX_MAIN_DB . "profile ORDER BY id_profile ";
					break; 
				case 'users_contact_edition':
					$query = "SELECT * " . ( $opt ? ", id_user as id, us_user as opt " : "") . " FROM " . PFX_MAIN_DB . "user "
								. " WHERE id_user NOT IN (SELECT co_us_id_user FROM " . PFX_MAIN_DB . "contact ) "
								. ( ($extra != '' ) ? " OR id_user = " . $extra . " " : "" ); 
					break;
				default:
					$this->error[] = "Invalid catalogue.";
					return FALSE;
			}  
			
			global $obj_bd;
			// echo $query;
			$result = $obj_bd->query( $query ); 
			if ( $result !== FALSE ){
				return $result;
			} 
			else return FALSE;  
		}
	}
	
	/**
	 * function get_catalgue_options()
	 * Returns an html string of catalogue options from the database to be inserted in a 'selected' control
	 * 
	 * @param 		$which 		String 	catalogue to query
	 * @param 		$active 	Int		ID of the selected option 
	 * @param 		$option_0	String 	for the first option if string is empty no first option  will be added 
	 * 
	 * @return		$html		String	HTML list of the catalogue options, FALSE on error
	 * 
	 */ 
	public function get_catalgue_options( $which, $selected = 0, $option_0 = 'Elija una opción', $extra = FALSE ){
		if ($which != ''){
			$html = "";
			if ( $option_0 != '' )
				$html .= "<option value='0' " . ( $selected == 0 ? "selected='selected'" : "" ) . " >" . $option_0 . "</option>";
			$options = $this->get_catalogue( $which, true, $extra); 
			if ( $options ){
				foreach ($options as $k => $ops) {
					$html .= "<option value='" . $ops['id'] . "' "
					 			. ( $selected == $ops['id'] ? "selected='selected'" : "" ) 
								. "  >" . $ops['opt'] 
							. "</option>";
				}
			}
			return $html;
		} else {
			$this->error[] = "Invalid catalogue.";
			return FALSE; 
		}
	}
	
	/**
	 * function get_catalgue_lists()
	 * Returns an html string of a listed tab menu from a catalogue
	 * 
	 * @param 		$which 		String 	catalogue to query
	 * @param 		$active 	Int		ID for the active tab		
	 * @param		$link_tmpl	String	link string template to concatenate to the id to change view
	 * @param 		$tab_0		String 	for the first tab if string is empty no first tab before the catalogue
	 * @param		$css		Strng 	Class for the link in the tab
	 * 
	 * @return		$html		String	HTML list of the catalogue tabs , FALSE on error
	 * 
	 */ 
	public function get_catalgue_lists( $which, $active = 0, $link_tmpl = '', $tab_0 = '', $css = 'tab-link' ){
		if ($which != ''){
			$html = "";
			if ( $tab_0 != '' )
				$html .=  "<li " . ( $active == 0 ? "class='active'" : "" ) . " >" 
							. "<a id='tab_" . $which . "_0' " 
									. " class='" . $css . "' " 
									. " href='" . ( $link_tmpl != '' ? $link_tmpl . "0" : "#" ) . "'>" 
								. $tab_0 
							. "</a>"  
						. "</li>";
			$options = $this->get_catalogue( $which, true); 
			if ( $options ){
				foreach ($options as $k => $ops) {
					$html .= "<li " . ( $active == $ops['id'] ? "class='active'" : "" ) . " >" 
							. "<a id='tab_" . $which . "_" . $ops['id'] . "' " 
									. " class='" . $css . "' " 
									. " href='" . ( $link_tmpl != '' ? $link_tmpl . $ops['id'] : "#" ) . "'>" 
								. $ops['opt']
							. "</a>"  
						. "</li>"; 
				}
			}
			return $html;
		} else {
			$this->error[] = "Invalid catalogue.";
			return FALSE; 
		}
	}
	
}

?>