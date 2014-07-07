<?php
session_start();
if (!isset($_GET['dbg']))
	ini_set('display_errors',  0);
else ini_set('display_errors', 1);

ini_set('session.cookie_domain', str_replace("www.", "", $_SERVER['HTTP_HOST']));

define("DIRECTORY_CONFIG", 		"config/"); 
require_once(DIRECTORY_CONFIG . 'config.php');
require_once(DIRECTORY_CONFIG . 'config_views.php');

include_once(DIRECTORY_CLASS  . 'class.log.php');
include_once(DIRECTORY_CLASS  . 'class.adodb_mysql.php');
include_once(DIRECTORY_CLASS  . 'class.settings.php');
include_once(DIRECTORY_CLASS  . 'class.session.php');
include_once(DIRECTORY_CLASS  . 'class.index.php');
include_once(DIRECTORY_FUNCS  . 'func.php');
$Log 		= new Log();
$obj_bd 	= new ADODB_mysql( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );

$Settings	= new Settings(); 

$Session 	= new Session();
$Index		= new Index();

include_once(DIRECTORY_CLASS  . 'class.validate.php');
$Validate 	= new Validate();

include_once(DIRECTORY_CLASS . 'class.catalogue.php');
$catalogue	= new Catalogue();
?>