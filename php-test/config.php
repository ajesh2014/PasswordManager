<?php
// defining a path variable for the application
defined("CONTROLLERS_PATH")
    or define("CONTROLLERS_PATH", 
				realpath(dirname(__FILE__) . '/controllers'));
				
				
// defining a view path variable for the application
defined("VIEW_PATH")
    or define("VIEW_PATH", 
		realpath(dirname(__FILE__) . '/views'));
 
 // defining database details
defined("DB_USER")
	or define("DB_USER", "password_manager");
	 
defined("DB_PASSWORD")
	or define("DB_PASSWORD", "b3XYYbUXuEPzwttL");
	
defined("DB_DATABASE")	
	or define("DB_DATABASE", "password_manager");
	
defined("DB_HOST")
	or define("DB_HOST", "localhost");
 
 
//Error reporting.
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);


?>