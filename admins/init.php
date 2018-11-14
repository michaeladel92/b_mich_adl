<?php
	//connect to database
	include("connect.php");
	//Routes initialize[to make page more dinamic and easy to change name]
	$tpl  = 'includes/templates/';  // template Directory
	$lang = 'includes/languages/'; //language Directory
	$func = 'includes/functions/'; //Functions Directory
	$css  = 'layout/css/'; 		 	//Css Directory
	$js   = 'layout/js/'; 			//Js Directory

	//Include the Important Files
	include ($func . "functions.php");
	include ($lang . "english.php");
	include ($tpl . "header.php");
	//Include navbar in all pages except with the one with $noNavbar
	if (!isset($noNavbar)) {include ($tpl . "navbar.php");}










