<?php
	
	//start session
	session_start();
	//Unset the data
	session_unset(); 
	//destroy session
	session_destroy();

	header("location:index.php");
	exit();