<?php
define("DIRECTORY_CONFIG", 		"config/");
require_once(DIRECTORY_CONFIG . 'config.php');
require_once(DIRECTORY_CONFIG . 'config_views.php');

function __autoload($className){
    $className = strtolower($className);
    $classFile = sprintf('%sclass.%s.php', DIRECTORY_CLASS, $className);
    require_once($classFile);
}
include_once(DIRECTORY_FUNCS  . 'func.php');

ini_set('display_error', DEBUG_MODE? 1: 0);
ini_set('session.cookie_domain', str_replace("www.", "", $_SERVER['HTTP_HOST']));

$Log 		= new Log();
$obj_bd 	= new ADODB_mysql( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
$Settings	= new Settings(); 
$Session 	= new Session();
$Index		= new Index();

$Validate 	= new Validate();
$catalogue	= new Catalogue();
?>