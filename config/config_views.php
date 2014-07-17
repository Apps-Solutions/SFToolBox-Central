<?php


define("HOME", 					"dashboard");
define("LOGIN",	 				"login");
define("LOGOUT",	 				"logout");

/** Error **/
define("FORBIDDEN",               "ERROR403");
define("NOT_FOUND",               "ERRROR404");

/** Configuración **/
define("FRM_APPEARANCE",		"frm_appearance");
define("FRM_VERSION_CTRL",		"frm_version_ctrl");
define("FRM_VERSION_APP",		"frm_version_app");
define("FRM_MESSAGING",			"frm_messaging");


/** Administración **/
define("LST_USER", 				"lst_users"); 
define("FRM_CONTACT", 			"frm_contact");
define("FRM_CONTACT_META", 		"frm_contact_meta");
define("LST_PROYECT", 			"lst_proyect"); 
define("FRM_PROYECT", 			"frm_proyect");
define("LST_PRODUCT", 			"lst_product"); 
define("FRM_PRODUCT", 			"frm_product");
define("LST_PDV", 				"lst_pdv"); 
define("FRM_PDV", 				"frm_pdv");

/** Catálogos **/
define("ADMIN_CATALOGUE", 		"admin_catalogue");

 
define("LST_COMPANIES", 		"lst_companies"); 
define("LST_STATE", 			"lst_state"); 
define("LST_EVIDENCE_TYPE", 	"lst_evidence_type"); 
define("LST_TASK_OMITION", 		"lst_task_omition"); 
define("LST_VISIT_OMITION", 	"lst_visit_omition"); 
define("LST_SUPPLIER", 			"lst_suppplier"); 
define("LST_REGION", 			"lst_region");   

/** Proyects **/
define("PRY_DASHBOARD", 		"pry_dashboard");
define("PRY_INFO", 				"pry_info"); 
define("PRY_USER",	 			"pry_user"); 
define("PRY_PDV", 				"pry_pdv");
define("PRY_PRODUCT", 			"pry_product"); 
define("PRY_MEDIA", 			"pry_media");
define("PRY_FORMS",				"pry_forms"); 
define("PRY_EVIDENCE_TYPE", 	"pry_evidence_type");
define("PRY_TASK_OMITION", 		"pry_task_omition");
define("PRY_VISIT_OMITION", 	"pry_visit_omition");
define("PRY_SUPPLIER",			"pry_supplier"); 
define("PRY_VISITS", 			"pry_visits");
define("PRY_ORDERS", 			"pry_orders");
define("PRY_SUPERVISORS", 		"pry_supervisors");
define("PRY_PENDING", 			"pry_pendientes");
define("PRY_REPORTS", 			"pry_reportes");



$uiCommand = array(); 	//Controla los permisos			Titulo						PHP													JS						CSS				AJAX
$uiCommand[LOGIN]		= array( 	""/*todos*/,     	"Iniciar Sesion", 			"frm.login.php",									"",						"",				""		);
$uiCommand[HOME]		= array( 	array(1,2,3,4,5),  	"Dashboard", 				DIRECTORY_VIEWS.DIRECTORY_BASE."dashboard.php", 	"",						"",				""		);
$uiCommand[LST_USER]	= array( 	array(1 ),			"Usuarios", 				DIRECTORY_VIEWS."admin/usuarios.php",  			array("admin.users.js"),	"",				""		); 
$uiCommand[FRM_CONTACT_META]= array(array(1), 			"Información de Contacto ", DIRECTORY_VIEWS."admin/frm.contact_meta.php",  	array("admin.contact_meta.js"), "",			""		);
$uiCommand[FRM_CONTACT] = array(	array(1,2,3), 		"Edición de Contacto",  	DIRECTORY_VIEWS."agenda/frm.contact.php",  		array("contact.js"),		"",				""		); 
$uiCommand[FRM_CONTACT_META]=array(	array(1), 			"Opciones de Contacto", 	DIRECTORY_VIEWS."admin/frm.contact_meta.php",   array("admin.contact_meta.js"),"",			""		);
$uiCommand[LST_PROYECT]  =	array(	array(1),  			"Proyectos", 				DIRECTORY_VIEWS."admin/lst_proyect.php", 			"",						"",				""		);
$uiCommand[FRM_PROYECT]	 =	array(  array(1,2),			"Edición de Proyecto",		DIRECTORY_VIEWS."admin/frm.proyect.php",		array("admin.proyect.js"),	"",				""		);
$uiCommand[LST_PRODUCT]  =	array(	array(1),  			"Productos", 				DIRECTORY_VIEWS."admin/lst_product.php", 			"",						"",				""		);
$uiCommand[FRM_PRODUCT]	 =	array(  array(1,2),			"Edición de Producto",		DIRECTORY_VIEWS."admin/frm_product.php",		array("admin.product.js"),	"",				""		); 
$uiCommand[LST_PDV] 	 =	array(	array(1),  			"PDVs", 					DIRECTORY_VIEWS."admin/lst_pdv.php", 				"",						"",				""		);
$uiCommand[FRM_PDV]		 =	array(  array(1,2),			"Edición de PDV",			DIRECTORY_VIEWS."admin/frm_pdv.php",			array("admin.pdv.js"),		"",				""		);

/** Error **/
$uiCommand[FORBIDDEN]	 =	array(  ""/*todos*/,	    "ACCESO DENEGADO",			DIRECTORY_VIEWS.DIRECTORY_BASE."403.php",		"",		                    "",				""		);
$uiCommand[NOT_FOUND]	 =	array(  ""/*todos*/,	    "NO ENCONTRADO",			DIRECTORY_VIEWS.DIRECTORY_BASE."404.php",		"",		                    "",				""		);


/** Configuración **/
$uiCommand[FRM_APPEARANCE]=	array(	array(1),  			"Apariencia", 				DIRECTORY_VIEWS."config/frm.appearance.php", 		"",						"",				""		);
$uiCommand[FRM_VERSION_APP]=array(  array(1), 			"Versión de App",  			DIRECTORY_VIEWS."config/frm.version_app.php",		"",						"",				""		);
$uiCommand[FRM_VERSION_CTRL]=array( array(1), 			"Versión de Backend", 		DIRECTORY_VIEWS."config/frm.version_backend.php",   "",						"",				""		);

/*** Catálogos ****/
$uiCommand[ADMIN_CATALOGUE]=array(	array(1),  			"Administración de Catálogo",DIRECTORY_VIEWS."admin/admin.catalogues.php", 		array("admin.catalogues.js"), "",		""		);

$uiCommand[LST_COMPANIES]=	array(	array(1),  			"Compañías", 				DIRECTORY_VIEWS."catalogue/lst_company.php", 		array("admin.catalogues.js"), "",		""		);
$uiCommand[LST_STATE]	 =	array(  array(1), 			"Estados y Países",			DIRECTORY_VIEWS."catalogue/lst_state.php",			array("admin.catalogues.js"), "",		""		);
$uiCommand[LST_EVIDENCE_TYPE]=array(array(1), 			"Tipos de evidencia", 		DIRECTORY_VIEWS."admin/admin.catalogues.php",  		array("admin.catalogues.js"), "",		""		);
$uiCommand[LST_TASK_OMITION] =array(array(1),  			"Motivos Omisión Tarea", 	DIRECTORY_VIEWS."catalogue/lst_task_omition_cause.php",	array("admin.catalogues.js"), "",	""		);
$uiCommand[LST_VISIT_OMITION]=array(array(1), 			"Motivos Omisión Visita",	DIRECTORY_VIEWS."catalogue/lst_visit_omition_cause.php",array("admin.catalogues.js"), "",	""		); 
$uiCommand[LST_SUPPLIER] =	array(	array(1),  			"Mayoristas", 				DIRECTORY_VIEWS."catalogue/lst_supplier.php", 		"",						"",				""		);
$uiCommand[LST_REGION]	 =	array(  array(1), 			"Regiones",					DIRECTORY_VIEWS."catalogue/lst_region.php",			array("admin.catalogues.js"), "",		""		);   



$config_menu = array( 'cmd' => 'root', 'lnk' => array(
	array( 	"cmd" => HOME, 		"prf" => array(1,2,3,4,5), 		"lbl" => "Dashboard", 	"ico" => "fa-dashboard", 	"lnk" => array() 	),
	array(  
		"cmd" => "#",
		"lbl" => "Administración", 
		"prf" => array(1),
		"ico" => "fa-shield",
		"lnk" => array(
					array(
						'cmd' => '#',
						'prf' => array(1),
						'lbl' => 'Catálogos',  
						'ico' => "fa-th", 
						'lnk' => array( 
							array( "cmd" => ADMIN_CATALOGUE	. "&cat=client", 			"prf" => array(1),	"lbl" => "Clientes",			 			"ico" => "fa-truck", 		"lnk" => array() ),
							array( "cmd" => ADMIN_CATALOGUE	. "&cat=state", 			"prf" => array(1),	"lbl" => "Estados",			 				"ico" => "fa-globe", 		"lnk" => array() ),
							array( "cmd" => ADMIN_CATALOGUE	. "&cat=country", 			"prf" => array(1),	"lbl" => "Países",			 				"ico" => "fa-globe", 		"lnk" => array() ), 
							array( "cmd" => ADMIN_CATALOGUE . "&cat=evidence_type",		"prf" => array(1),	"lbl" => "Tipos de evidencia", 				"ico" => "fa-camera", 		"lnk" => array() ), 
							array( "cmd" => ADMIN_CATALOGUE . "&cat=task_omition_cause","prf" => array(1),	"lbl" => "Motivos de omisión de tarea", 	"ico" => "fa-minus-circle", "lnk" => array() ), 
							array( "cmd" => ADMIN_CATALOGUE . "&cat=visit_reschedule_cause", "prf" => array(1),	"lbl" => "Motivos de reagendación",		 	"ico" => "fa-minus-circle", "lnk" => array() ), 
							array( "cmd" => ADMIN_CATALOGUE . "&cat=supplier", 			"prf" => array(1),	"lbl" => "Mayoristas",			 			"ico" => "fa-truck", 		"lnk" => array() ),  
							array( "cmd" => ADMIN_CATALOGUE	. "&cat=region",	 		"prf" => array(1),	"lbl" => "Regiones",			 			"ico" => "fa-globe", 		"lnk" => array() ),  
						) 
					), 
					array( 	'cmd' => LST_PROYECT, 	'prf' => array(1), 		'lbl' => 'Proyectos', 	'ico' => "fa-puzzle-piece", 	'lnk' => array() ), 
					array(
						'cmd' => '#',
						'prf' => array(1),
						'lbl' => 'Usuarios',  
						'ico' => "fa-group", 
						'lnk' => array(
							array( "cmd" => LST_USER, 			"prf" => array(1),		"lbl" => "Usuarios", 			"ico" => "fa-male", 	"lnk" => array() ), 
							array( "cmd" => FRM_CONTACT_META, 	"prf" => array(1),		"lbl" => "Config. Contactos",	"ico" => "fa-gear", 	"lnk" => array() ), 
						) 
					), 
				 )
	),
	array(  
		"cmd" => "#",
		"lbl" => "Proyecto", 
		'prf' => array(1,2,3),
		'ico' => "fa-puzzle-piece", 
		"lnk" => array(
					array( "cmd" => PRY_DASHBOARD,	 	'prf' => array(1,2,3),	"lbl" => "Dashboard",		"ico" => "fa-dashboard", 		"lnk" => array() ),
					array( "cmd" => PRY_INFO, 			'prf' => array(1,2,3),	"lbl" => "Información",		"ico" => "fa-info",		 		"lnk" => array() ),
					array( "cmd" => "#",				'prf' => array(1,2,3),	"lbl" => "Recursos",		"ico" => "fa-archive",		 		
								"lnk" => array(  
									array( "cmd" => PRY_USER,		 	'prf' => array(1,2,3),	"lbl" => "Usuarios",						"ico" => "fa-group",	 		"lnk" => array() ),
									array( "cmd" => PRY_PDV,		 	'prf' => array(1,2,3),	"lbl" => "PDVs",	 						"ico" => "fa-bullseye", 		"lnk" => array() ),
									array( "cmd" => PRY_PRODUCT,		'prf' => array(1,2,3),	"lbl" => "Productos",						"ico" => "fa-barcode",	 		"lnk" => array() ),
									array( "cmd" => PRY_MEDIA,			'prf' => array(1,2,3),	"lbl" => "Materiales",						"ico" => "fa-film",		 		"lnk" => array() ),
									array( "cmd" => PRY_FORMS,			'prf' => array(1,2,3),	"lbl" => "Formularios",						"ico" => "fa-clipboard", 		"lnk" => array() ), 
									array( "cmd" => PRY_EVIDENCE_TYPE,	"prf" => array(1,2),	"lbl" => "Tipos de evidencia", 				"ico" => "fa-camera", 			"lnk" => array() ), 
									array( "cmd" => PRY_TASK_OMITION, 	"prf" => array(1,2),	"lbl" => "Motivos de omisión de tarea", 	"ico" => "fa-minus-circle", 	"lnk" => array() ), 
									array( "cmd" => PRY_VISIT_OMITION, 	"prf" => array(1,2),	"lbl" => "Motivos de omisión de visita", 	"ico" => "fa-minus-circle", 	"lnk" => array() ), 
									array( "cmd" => PRY_SUPPLIER, 		"prf" => array(1,2),	"lbl" => "Mayoristas",			 			"ico" => "fa-truck", 			"lnk" => array() ), 
								) 
					), 
					array( "cmd" => PRY_VISITS,			'prf' => array(1,2,3),	"lbl" => "Visitas",			"ico" => "fa-thumb-tack",	 	"lnk" => array() ),
					array( "cmd" => PRY_ORDERS,			'prf' => array(1,2,3),	"lbl" => "Pedidos",			"ico" => "fa-usd",			 	"lnk" => array() ),
					array( "cmd" => PRY_SUPERVISORS,	'prf' => array(1,2,3),	"lbl" => "Supervisores",	"ico" => "fa-gavel",		 	"lnk" => array() ),
					array( "cmd" => PRY_PENDING,		'prf' => array(1,2,3),	"lbl" => "Pendientes",		"ico" => "fa-coffee",		 	"lnk" => array() ),
					array( "cmd" => PRY_REPORTS,		'prf' => array(1,2,3),	"lbl" => "Reportes",		"ico" => "fa-bar-chart-o",	 	"lnk" => array() )
				 )
	),
	array(  
		"cmd" => "#",
		"lbl" => "Configuración", 
		'prf' => array(1),
		"ico" => "fa-gear",
		"lnk" => array(
					array( "cmd" => FRM_APPEARANCE, 	"lbl" => "Apariencia", 		"ico" => "fa-tint",		 	"lnk" => array() ),
					array( "cmd" => FRM_VERSION_APP, 	"lbl" => "Aplicación", 		"ico" => "fa-android", 		"lnk" => array() ),
					array( "cmd" => FRM_VERSION_CTRL, 	"lbl" => "Backend",			"ico" => "fa-cloud",	 	"lnk" => array() ) 
				 )
	) 
 )
);
?>