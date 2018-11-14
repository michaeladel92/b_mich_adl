<?php
	//Error Reporting	
	ini_set('display_errors','On');
	error_reporting(E_ALL);

	//connect to database
	include("admins/connect.php");

	//session user
	$sessionUser = '';
	if (isset($_SESSION['user'])) {
		$sessionUser =  $_SESSION['user'];
	}

	//Routes initialize[to make page more dinamic and easy to change name]
	$tpl  = 'includes/templates/';  // template Directory
	$lang = 'includes/languages/'; //language Directory
	$func = 'includes/functions/'; //Functions Directory
	$css  = 'layout/css/'; 		 	//Css Directory
	$js   = 'layout/js/'; 			//Js Directory

	//Include the Important Files
	include ($func . "functions.php");
	include ($lang . "english.php");
	include ($tpl . "header.php"); //navbar and Header in one file
	










