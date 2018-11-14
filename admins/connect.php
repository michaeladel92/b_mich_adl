<?php
	
/**=================================
 * Connection to database using PDO
 =================================*/
	$dsn  	= 'mysql:host=localhost;dbname=shop';
	$user 	= 'root';
	$pass 	= '';
	$option = array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
					);
	try {
		$con = new PDO($dsn, $user, $pass, $option);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "You are connected";
	}
	catch(PDOException $e){
		echo "Failed to Connect" . $e->getMessage();
	}