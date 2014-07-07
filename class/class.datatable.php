<?php 
	class DataTable{ 
		
		public $page;
		public $rows;
		public $table_id;
		
		public $sord;
		public $sidx;
		
		public $fidx;
		public $fval;
		
		public $acciones;
		public $idioma = 1;
		public $title;
		
		private $which;
		private $query;
		private $where;
		private $group;
		private $sort;
		private $limit;
		 
		public $total_pages 	= 0;
		public $total_records	= 0;
		
		private $columns = array();
		private $template = "";
		private $template_header = "";
		private $template_foot = "";
		private $cols = array();
		
		private $id_proyecto;
		private $showing_template = " Mostrando %s - %s de %s registros. ";
		
		public $error = array();
		
		public function DataTable( $which = '', $table_id = '' ){
			
			if ( $which != ''){
				
				$this->which = $which;
				$this->page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1;
				$this->rows = isset($_REQUEST['rows']) && is_numeric($_REQUEST['rows']) ? $_REQUEST['rows'] : 25;
				$this->sord = isset($_REQUEST['sord']) && $_REQUEST['sord'] != '' ? $_REQUEST['sord'] : "ASC";
				$this->sidx = isset($_REQUEST['sidx']) && $_REQUEST['sidx'] != '' ? $_REQUEST['sidx'] : "id";
				
				if ( $table_id != '')
					$this->table_id = $table_id;
				else 
					$this->table_id = $which ;
				
				$this->set_query(); 
				$this->set_search();
				$this->set_template(); 
				
			} else {
				$this->clean();
				$this->error[] = "Listado inválido.";
			}
			
		}
		
		private function set_query() {
			switch ( $this->which ){
				case 'lst_admin_users':
					$this->query = " SELECT * FROM (  SELECT "
									. " id_user , us_user, pf_profile, id_profile, CONCAT( co_lastname, ' ', co_name ) as name, co_sex, us_lastlogin " 
								. " FROM " . PFX_MAIN_DB . "user u "
									. " INNER JOIN " . PFX_MAIN_DB . "profile p ON id_profile = us_pf_id_profile  "
									. "  LEFT JOIN " . PFX_MAIN_DB . "contact c ON co_us_id_user = id_user  "
								. " WHERE us_status = 1 GROUP BY id_user ) as tbl WHERE id_user > 0 ";
					$this->group = " GROUP BY id_user ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_user';
					break; 
				case 'lst_company':
					$this->query = " SELECT *, id_company as id, cm_company as value  FROM " . PFX_MAIN_DB . "company c WHERE cm_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_company';
					break;
				case 'lst_country':
					$this->query = " SELECT *, id_country as id, cnt_country as value  FROM " . PFX_MAIN_DB . "country c WHERE cnt_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_country';
					break;
				case 'lst_evidence_type':
					$this->query = " SELECT *, id_evidence_type as id, et_evidence_type as value FROM " . PFX_MAIN_DB . "evidence_type c WHERE et_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_evidence_type';
					break;
				case 'lst_proyects':
					$this->query = "SELECT  "
										. " *  "
									. " FROM " . PFX_MAIN_DB . "proyect  "
										. " INNER JOIN " . PFX_MAIN_DB . "region "
										. " INNER JOIN " . PFX_MAIN_DB . "proyect_type "
										. " INNER JOIN " . PFX_MAIN_DB . "company "
									. " WHERE pr_status = 1 ";
					$this->group = " GROUP BY id_proyect "; 
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_proyect';
					break;
				case 'lst_region':
					$this->query = " SELECT *, id_region as id, re_region as value FROM " . PFX_MAIN_DB . "region WHERE re_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_region';
					break;
				case 'lst_visit_reschedule_cause':
					$this->query = " SELECT *, id_visit_reschedule_cause as id, vrc_visit_reschedule_cause as value FROM " . PFX_MAIN_DB . "visit_reschedule_cause WHERE vrc_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_visit_reschedule_cause';
					break;
				case 'lst_state':
					$this->query = " SELECT *, id_state as id, st_state as value, cnt_country as parent FROM " . PFX_MAIN_DB . "state "
						. " INNER JOIN " . PFX_MAIN_DB . "country ON id_country = st_cnt_id_country " 
					. " WHERE st_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_state';
					break;
				case 'lst_task_omition_cause':
					$this->query = " SELECT *, id_task_omition_cause as id, toc_task_omition_cause as value, tt_task_type as parent FROM " . PFX_MAIN_DB . "task_omition_cause "
						. " INNER JOIN " . PFX_MAIN_DB . "task_type ON id_task_type = toc_tt_id_task_type " 
					. " WHERE toc_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_task_omition_cause';
					break;
				case 'lst_supplier':
					$this->query = " SELECT *, id_supplier as id, su_supplier as value, '1' as detail FROM " . PFX_MAIN_DB . "supplier WHERE su_status = 1 ";
					$this->sidx = ( $this->sidx != 'id') ? $this->sidx : 'id_supplier';
					break;
			}
			$this->sort = " ORDER BY " . $this->sidx . " " . $this->sord . " ";
		}
		
		public function set_filter( $col , $val, $signo = '=', $modo = 'AND', $open = '', $close = '' ){
			if ($signo == 'LIKE')
				$this->where .= " " . $modo . " " . $open . " " . mysql_real_escape_string($col) . " " . $signo . " '%" . mysql_real_escape_string($val) . "%' " . $close . " ";
			else
				$this->where .= " " . $modo . " " . $open . " " . mysql_real_escape_string($col) . " " . $signo . "  '" . mysql_real_escape_string($val) . "'  " . $close . " ";
		}
		
		public function set_title( $title ){
			$this->title = $title;
		}
		 
		private function set_template() {
			switch ( $this->which ){
				case 'lst_admin_users':
					$this->title = " Usuarios ";
					$this->columns = array(
						array( 'idx' => 'id_user', 		'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'image', 		'lbl' => 'Imágen',  	'sortable' => FALSE, 	'searchable' => FALSE 	),
						array( 'idx' => 'us_user',	 	'lbl' => 'Usuario', 	'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'name',		 	'lbl' => 'Nombre', 		'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'pf_profile', 	'lbl' => 'Perfil', 		'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'us_lastlogin', 'lbl' => 'Último Acceso', 'sortable' => TRUE, 	'searchable' => FALSE  	),
						array( 'idx' => 'actions',	 	'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.users.php"; 
					break; 
				case 'lst_company':
					$this->title = " Compañías ";
					$this->columns = array(
						array( 'idx' => 'id_company',	'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'cm_company',	'lbl' => 'Compañía',  	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',	 	'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break; 
				case 'lst_country':
					$this->title = " Países ";
					$this->columns = array(
						array( 'idx' => 'id_country',	'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'cnt_country',	'lbl' => 'País',  		'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',	 	'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break; 
				case 'lst_supplier':
					$this->title = " Mayorista ";
					$this->columns = array(
						array( 'idx' => 'id_supplier',	'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'su_supplier',	'lbl' => 'Mayorista',  	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',	 	'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break;
				case 'lst_evidence_type':
					$this->title = " Tipos de Evidencia ";
					$this->columns = array(
						array( 'idx' => 'id_evidence_type',	'lbl' => 'ID', 					'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'et_evidence_type',	'lbl' => 'Tipo de Evidencia',  	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',	 		'lbl' => 'Acciones', 			'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break; 
				case 'lst_proyects':
					$this->title = " Proyectos ";
					$this->columns = array(
						array( 'idx' => 'id_proyect',	'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'pr_proyect',	'lbl' => 'Proyecto',  	'sortable' => TRUE, 	'searchable' => TRUE 	),
						array( 'idx' => 'pt_proyect_type',	'lbl' => 'Tipo',	'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'cm_company', 	'lbl' => 'Compañía',	'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 're_region', 	'lbl' => 'Región', 		'sortable' => TRUE, 	'searchable' => TRUE  	), 
						array( 'idx' => 'actions',	 	'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.proyects.php"; 
					break; 
				case 'lst_region':
					$this->title = " REgiones ";
					$this->columns = array(
						array( 'idx' => 'id_region',	'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 're_region',	'lbl' => 'Región',  	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',	 	'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break;
				case 'lst_state':
					$this->title = " Estados ";
					$this->columns = array(
						array( 'idx' => 'id_state',		'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'cnt_country',	'lbl' => 'País', 	 	'sortable' => TRUE, 	'searchable' => TRUE 	),
						array( 'idx' => 'st_state',		'lbl' => 'Estado',  	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',		'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break; 
				case 'lst_supplier':
					$this->title = " Estados ";
					$this->columns = array(
						array( 'idx' => 'id_state',		'lbl' => 'ID', 			'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'cnt_country',	'lbl' => 'País', 	 	'sortable' => TRUE, 	'searchable' => TRUE 	),
						array( 'idx' => 'st_state',		'lbl' => 'Estado',  	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',		'lbl' => 'Acciones', 	'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break; 
				case 'lst_task_omition_cause':
					$this->title = " Motivos de Omisión de tarea ";
					$this->columns = array(
						array( 'idx' => 'id_task_omition_cause',	'lbl' => 'ID', 					'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'tt_task_type',				'lbl' => 'Tipo de Tarea',		'sortable' => TRUE, 	'searchable' => TRUE 	),
						array( 'idx' => 'toc_task_omition_cause',	'lbl' => 'Motivo de Omisión',	'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',					'lbl' => 'Acciones', 			'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break; 
				case 'lst_visit_reschedule_cause':
					$this->title = " Motivos de Reagendación ";
					$this->columns = array(
						array( 'idx' => 'id_visit_reschedule_cause',	'lbl' => 'ID', 						'sortable' => TRUE, 	'searchable' => TRUE  	),
						array( 'idx' => 'vrc_visit_reschedule_cause',	'lbl' => 'Motivo de Reagendación',  'sortable' => TRUE, 	'searchable' => TRUE 	),  
						array( 'idx' => 'actions',	 					'lbl' => 'Acciones', 				'sortable' => FALSE, 	'searchable' => FALSE	)
					); 
					$this->template = DIRECTORY_VIEWS . "/lists/lst.admin.catalogue.php"; 
					break;
			}
		}
		
		public function set_search(){ 
			if (isset($_REQUEST['searchField']) && $_REQUEST['searchField'] != '' && isset($_REQUEST['searchString']) && $_REQUEST['searchString'] != '') { 
				$sfield = $_REQUEST['searchField'];
				$sstr 	= $_REQUEST['searchString'];
				$this->where .= " AND $sfield LIKE '%" . mysql_real_escape_string($sstr) . "%' "; 
			}
		}
		
		public function get_list_html( $ajax = FALSE ){ 
			if (count($this->error) == 0 && $this->query != '' && $this->template != ''){ 
				global $obj_bd;
				$query = $this->query 
							. " " . $this->where 
							. " " . $this->group
							. " " . $this->sort; 
				//echo $query;
				$q_cuantos =  "SELECT count(*) as RecordCount FROM (" . $query . ") as cuenta" ; 
				$record = $obj_bd->query( $q_cuantos );
				
				if ( $record === FALSE ){
					$this->set_error( 'Ocurrió un error al contar los registros en la BD. ' . $q_cuantos , LOG_DB_ERR, 1);
					return FALSE;
				}
				
				$this->total_records = (int)$record[0]["RecordCount"];
				$start = (($this->page - 1) * $this->rows);
				
				if ($this->total_records > 0) { $this->total_pages = ceil($this->total_records / $this->rows);
				} else { $this->total_pages = 0; }  
				$limit = " LIMIT " . $start . ", " . $this->rows;
				
				$result = $obj_bd->query( $query . $limit ); 
				//echo $query . $limit ;
				if ( $result !== FALSE ){
					if ( !$ajax ) 
						$this->get_header_html();
					if ( count( $result ) > 0 ){
						$resp = "";
						foreach ($result as $k => $record) {
							ob_start();
							require $this->template; 
							$resp .= ob_get_clean(); 
						}
					} else {
						$resp =  "<tr> <td align='center' colspan='" . count($this->columns) . "'> No se encontraron registros. </td> <tr>";
					} 
					if ( $ajax ) {
						return $resp;
					} else{
						echo $resp; 
						$this->get_foot_functions();
					}
				}
				else {
					$this->set_error( 'Ocurrió un error al obtener los registros de la BD', LOG_DB_ERR, 2);
					return false;
				} 
			}
		}

		public function get_html_search(){
			?>
			<select id="inp_<?php echo $this->table_id ?>_srch_idx">
			<?php 
				foreach ($this->columns as $k => $col) {
					if ($col['searchable']){
						echo "<option value='" . $col['idx'] . "'>" . $col['lbl'] . "</option>";
					}
				}
			?>
			</select>
			<input type="text" id="inp_<?php echo $this->table_id ?>_srch_string">
			<button onclick="reload_table('<?php echo $this->table_id ?>')"><i class="fa fa-search"></i></button>
			<?php
		}

		private function get_header_html(){ 
			if ( is_array($this->columns) ){  ?>
				<thead>
					<tr >
						<td colspan="<?php echo count($this->columns) ?>">
							<div class="row">
								<div class="col-xs-12 text-center"> <h4 id='lbl_title'><?php echo $this->title ?></h4> </div>
								<div class="col-xs-6">
									Buscar 
									<?php $this->get_html_search(); ?>  
								</div>
								<div class="col-xs-6 text-right"> 
									<span id='<?php echo $this->table_id ?>_lbl_foot' >
									<?php  echo $this->get_foot_records_label(); ?>
									</span>
									<select id="inp_<?php echo $this->table_id ?>_rows" name="<?php echo $this->table_id ?>_rows" onchange="reload_table('<?php echo $this->table_id ?>');">
										<option value="25"  <?php $this->rows == 25  ? "selected='selected'" : "" ?>>25</option>
										<option value="50"  <?php $this->rows == 50  ? "selected='selected'" : "" ?>>50</option>
										<option value="100" <?php $this->rows == 100 ? "selected='selected'" : "" ?>>100</option>   
									</select> 
									registros por página. 
									<button onclick="reload_table('<?php echo $this->table_id ?>');"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</td>
					</tr>
					<tr>
				<?php foreach ($this->columns as $k => $col) {
					$sort_cls = ""; 
					$sort_func = "";
					if ( $col['sortable'] ){
						$sort_cls = "sortable sorting";
						$sort_dir = "ASC";
						if ( $this->sidx == $col['idx'] ){
							$sort_cls .= ( $this->sord == 'DESC') ? "_asc" : "_desc";
							$sort_dir  = ( $this->sord == 'DESC') ? "ASC"  : "DESC";
						}
						$sort_func = "onclick='sort_table(\"" . $this->table_id . "\", \"" . $col['idx'] . "\", \"" . $sort_dir . "\" )'";
					} 
					echo "<th id='" . $this->table_id . "_hd_" . $col['idx'] . "' class='" . $this->table_id . "_head " . $sort_cls . "' " . $sort_func . " > " . $col['lbl'] . "</th>";	
				?>  
				<?php } ?> 
					</tr>
				</thead> 
				<tbody id="<?php echo $this->table_id ?>_tbody" > 
			<?php
			}
		} 
		
		public function get_foot_records_label(){
			$start 	= (($this->page - 1) * $this->rows);
			$stop 	= $start + $this->rows;
			$stop 	= ( $stop <= $this->total_records ) ? $stop : $this->total_records;
			return sprintf( $this->showing_template, $start+1, $stop, $this->total_records );
		}
		
		private function get_foot_functions(){ ?>
			</tbody>
			<tfoot>
				<tr> 
					<td colspan="<?php echo count($this->columns) ?>">
						<div class="row">
							<div class="col-xs-6" style="margin-top: 10px;" >
								
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_sord" name="<?php echo $this->table_id ?>_sord" value="<?php echo $this->sord ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_sidx" name="<?php echo $this->table_id ?>_sidx" value="<?php echo $this->sidx ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_fval" name="<?php echo $this->table_id ?>_fval" value="<?php echo $this->fval ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_fidx" name="<?php echo $this->table_id ?>_fidx" value="<?php echo $this->fidx ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_rows" name="<?php echo $this->table_id ?>_rows" value="<?php echo $this->rows ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_list" name="<?php echo $this->table_id ?>_list" value="<?php echo $this->which ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_cols" name="<?php echo $this->table_id ?>_cols" value="<?php echo count($this->columns)  ?>" />
								<input type="hidden" id="inp_<?php echo $this->table_id ?>_tpages" name="<?php echo $this->table_id ?>_tpages" value="<?php echo $this->total_pages ?>" /> 
							</div> 
							<div class="col-xs-6 text-right">
								<div class="datatable-paginate">
									<ul class="pagination">
										<li <?php echo ( $this->page > 1 ) ? "" : "class='disabled'"; ?> >
											<a href="#"><i class="fa fa-angle-double-left"></i></a>
										</li>
										<li <?php echo ( $this->page > 1 ) ? "" : "class='disabled'"; ?> >
											<a href="#"><i class="fa fa-angle-left"></i></a>
										</li>
										<li>
											<a href="#">
												Página <input id="inp_<?php echo $this->table_id ?>_page" name="page" value="<?php echo $this->total_pages; ?>"  />
												<button style='margin-left: -5px;' onclick="reload_table('<?php echo $this->table_id ?>');"><i class="fa fa-gear"></i></button> de 
												<span id="<?php echo $this->table_id ?>_lbl_tpages"><?php echo $this->total_pages; ?></span>  
											</a>
										</li> 
										<li <?php echo ( $this->page < $this->total_pages ) ? "" : "class='disabled'"; ?> >
											<a href="#"><i class="fa fa-angle-right"></i></a>
										</li>
										<li <?php echo ( $this->page < $this->total_pages ) ? "" : "class='disabled'"; ?> >
											<a href="#"><i class="fa fa-angle-double-right"></i></a>
										</li> 
									</ul>
								</div> 
							</div>
						</div>
					</td>
				</tr>
			</tfoot>
			<?php
		}
		
		public function get_list_xml(){
			if (count($this->error) == 0 && $this->query != '' && $this->template != ''){
				global $obj_bd;
				$consulta = $this->query 
							. " " . $this->where 
							. " " . $this->group
							. " " . $this->sort; 
				//echo $consulta;
				$cuantos = $obj_bd -> consulta_bd("SELECT count(*) as RecordCount FROM (" . $this->query. " " . $this->where  . ") as cuenta");
				$many = $cuantos[0];
				$total = (int)$many["RecordCount"];
				
				$start = (($this->page - 1) * $this->rows);
				if ($total > 0) { $total_pages = ceil($total / $this->rows);
				} else { $total_pages = 0; } 
				$limit = " LIMIT " . $start . ", " . $this->rows; 
				 
				//echo $consulta;
				$result = $obj_bd->consulta_bd( $consulta . $limit );
				if ( $result !== FALSE ){
					$this->set_template( true );
					$this->set_xml_header();  
					echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
					echo "<rows>\n";
					echo "<page>" . $this->page . "</page>\n";
					echo "<total>" . $total_pages . "</total>\n";
					echo "<records>" . $total . "</records>\n";
					foreach ($result as $k => $record) { 
						require $this->template; 
					}
					echo "</rows>";
				} 
			}
		}

		private function set_header_xml(){
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
			header("Content-type: text/xml");
		}

		public function get_array(){
			if (count($this->error) == 0 && $this->query != ''){
				$this->bd = new IBD;
				$query = $this->query 
							. " " . $this->where 
							. " " . $this->group ;
				global $obj_bd;
				$result = $obj_bd->query( $query ); 
				if ( $result !== FALSE ){ 
					return $result;   
				}
				else {
					$this->set_error( 'Ocurrió un error al obtener los registros de la BD', LOG_DB_ERR, 2);
					return FALSE;
				}  
			}
		}
			
		public function clean(){ 
			$this->where = "";
			$this->columns = array();
			$this->error 	= array();
		}  
		
		public function get_errors( $break = "<br/>" ){
			$resp = "";
			if ( count ($this->error) > 0 ){
				foreach ( $this->error as $k => $err)
					$resp .= " ERROR @ Class DataTable: " . $err . $break;
			}
			return $resp;
		}
		
		private function set_error( $err , $type, $lvl = 1 ){
			global $Log;
			$this->error[] = $err;
			//$Log->write_log( " ERROR @ Class Listado : " . $err, $type, $lvl );
		}  
	}
	
?>