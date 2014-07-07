<?php


define("HOME", 					"dashboard");
define("LOGIN",	 				"login");

/** Administración **/
define("LST_USER", 				"lst_users");
define("LST_PROFILE", 			"profiles");
define("VW_PROFILE", 			"profile");

define("FRM_CONTACT_META", 		"frm_contact_meta");

/** AGENDA **/
define("LST_CONTACTS", 			"contact");
define("FRM_CONTACT", 			"frm_contact");
 
/** Clientes **/
define("LST_CLIENT", 			"clients");
define("LST_INSTANCE",			"instances");
define("REP_CLIENT",			"clients_reports");

/** Configuración **/
define("FRM_APPEARANCE",		"frm_appearance");
define("FRM_VERSION_CTRL",		"frm_version_ctrl");
define("FRM_VERSION_APP",		"frm_version_app");
define("FRM_MESSAGING",			"frm_messaging");


$uiCommand = array(); 
$uiCommand[LOGIN]	=	array(
	array(NIVEL_USERPUBLICO),
	"Iniciar Sesion",
	"frm.login.php",
	"",
	"",
	""
);

$uiCommand[HOME]	=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Dashboard", //Titulo
	DIRECTORY_VIEWS.DIRECTORY_BASE."dashboard.php", //Archivo PHP
	"", //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[LST_USER]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Usuarios", //Titulo
	DIRECTORY_VIEWS."admin/usuarios.php", //Archivo PHP
	array("jquery.form-validator.min.js", "datatable.js", "admin.users.js"), 
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[LST_PROFILE]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Perfiles", //Titulo
	DIRECTORY_VIEWS."admin/perfiles.php", //Archivo PHP
	"", //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[VW_PROFILE]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Perfil", //Titulo
	DIRECTORY_VIEWS."admin/perfil.php", //Archivo PHP
	"", //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[FRM_CONTACT_META]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Configuración de Información de Contacto ", //Titulo
	DIRECTORY_VIEWS."admin/frm.contact_meta.php", //Archivo PHP
	array("jquery.form-validator.min.js", "admin.contact_meta.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[FRM_CONTACT]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Edición de Contacto", //Titulo
	DIRECTORY_VIEWS."agenda/frm.contact.php", //Archivo PHP
	array("jquery.form-validator.min.js", "contact.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[FRM_CONTACT_META]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Perfil", //Titulo
	DIRECTORY_VIEWS."admin/frm.contact_meta.php", //Archivo PHP
	array("jquery.form-validator.min.js", "admin.contact_meta.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);


$uiCommand[LST_CLIENT]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Clientes", //Titulo
	DIRECTORY_VIEWS."clients/clientes.php", //Archivo PHP
	"", //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[LST_INSTANCE]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Instancias", //Titulo
	DIRECTORY_VIEWS."clients/instancias.php", //Archivo PHP
	"", //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);



$uiCommand[FRM_APPEARANCE]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Configuración de Apariencia", //Titulo
	DIRECTORY_VIEWS."config/frm.appearance.php", //Archivo PHP
	array("jquery.form-validator.min.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[FRM_VERSION_APP]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Configuración de Versión de App", //Titulo
	DIRECTORY_VIEWS."config/frm.version_app.php", //Archivo PHP
	array("jquery.form-validator.min.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[FRM_VERSION_CTRL]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Configuración de Versión de Central", //Titulo
	DIRECTORY_VIEWS."config/frm.version_backend.php", //Archivo PHP
	array("jquery.form-validator.min.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$uiCommand[FRM_MESSAGING]=	array(
	array(NIVEL_USERADMIN), //Controla los permisos
	"Central | Configuración de Mensajería", //Titulo
	DIRECTORY_VIEWS."config/frm.messaging.php", //Archivo PHP
	array("jquery.form-validator.min.js"), //array("ejemplo.js","ejemplo2.js")
	"", //array("css.css","css2.css")
	"" //Ajax File
);

$config_menu = array( 'cmd' => 'root', 'lnk' => array(
	array(  
		"cmd" => HOME,
		"lbl" => "Dashboard", 
		"ico" => "fa-dashboard",
		"lnk" => array()
	),
	array(  
		"cmd" => "#",
		"lbl" => "Administración", 
		"ico" => "fa-shield",
		"lnk" => array(
					array( "cmd" => LST_PROFILE, 		"lbl" => "Perfiles", 			"ico" => "fa-group", 	"lnk" => array() ),
					array( "cmd" => LST_USER, 			"lbl" => "Usuarios", 			"ico" => "fa-male", 	"lnk" => array() ),
					array( "cmd" => FRM_CONTACT_META, 	"lbl" => "Config. Contactos",	"ico" => "fa-gear", 	"lnk" => array() )
				 )
	),
	array(  
		"cmd" => LST_CONTACTS,
		"lbl" => "Agenda", 
		"ico" => "fa-book",
		"lnk" => array()
	),
	array(  
		"cmd" => "#",
		"lbl" => "Clientes", 
		"ico" => "fa-suitcase",
		"lnk" => array(
					array( "cmd" => LST_CLIENT, 	"lbl" => "Clientes", 		"ico" => "fa-suitcase", 	"lnk" => array() ),
					array( "cmd" => LST_INSTANCE, 	"lbl" => "Instancias", 		"ico" => "fa-hdd-o", 		"lnk" => array() ),
					array( "cmd" => REP_CLIENT, 	"lbl" => "Reportes",		"ico" => "fa-bar-chart-o", 	"lnk" => array() )
				 )
	),
	array(  
		"cmd" => "#",
		"lbl" => "Configuración", 
		"ico" => "fa-gear",
		"lnk" => array(
					array( "cmd" => FRM_APPEARANCE, 	"lbl" => "Apariencia", 		"ico" => "fa-tint",		 	"lnk" => array() ),
					array( "cmd" => FRM_VERSION_APP, 	"lbl" => "Aplicación", 		"ico" => "fa-android", 		"lnk" => array() ),
					array( "cmd" => FRM_VERSION_CTRL, 	"lbl" => "Backend",			"ico" => "fa-cloud",	 	"lnk" => array() ),
					array( "cmd" => FRM_MESSAGING, 		"lbl" => "Mensajería",		"ico" => "fa-envelope-o", 	"lnk" => array() )
				 )
	)
 )
);



?>