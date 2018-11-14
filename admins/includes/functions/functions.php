<?php

/**
 ** Title Function V1.0
 ** Title Function that Echo the page title In Case the Page 
 ** has the Variable   $pageTitle and echo the default title for other page
 */

function getTitle(){

		global $pageTitle;

			if (isset($pageTitle)) {
				echo $pageTitle;
			}else{
				echo 'Default';
			}
	}

/**
 ** Home Redirect  Functions V2.0
 ** this function redirect parameters 
 ** $TheMsg   = Echo the message [Erro | Success | Warning]
 ** $seconds  = Seconds Before redirecting
 ** $url      = The link You want to redirect to
 */

function redirectHome($TheMsg,$url = null, $seconds = 3){

			if ($url === null) {
				
					$url = 'index.php';

					$link = 'Homepage';
			
			}else{
					if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
							
							//back one step [write = back]
							//Note must be used only if there is a parent, cant be used direct
							
							$url = $_SERVER['HTTP_REFERER'];
							$link = 'Previous Page';
					}else{

						$url = 'index.php';
						$link = 'Home Page';
					}

			}

			echo $TheMsg;

			echo "<div class='alert alert-info'>You will be Redirected to $link After $seconds Seconds.</div>";
			header("refresh:$seconds; url=$url");

			exit();
}

/**
 ** Check items Function V1.0
 ** Function to check items in database [Function Accept Parameter]
 ** $select  = The item to Select  [Example: user, item, category]
 ** $from    = the Table to select from [Example: users, items, categories]
 ** $value   = The Value of select [Example: osama, box, Electronics]
 */

function checkItem($select, $from, $value ){
	global $con;

	$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

	$statment->execute(array($value));

	$count = $statment->rowCount();
//diffrent between return and echo, echo just show the result, but you 
//can not execute it, unlike return you can use it 
	return $count;
}





/**
 ** Count Number of Items V1.0 
 ** Function to count Numbers of rows 
 **$item  = the Item to Count
 **$table = the table to Choose From 
 */

function countItems($item, $table){
			global $con;

			$stmt = $con->prepare("SELECT COUNT($item) FROM $table");
			$stmt->execute();

			return $stmt->fetchColumn();
}


/*
** GET latest Records Function V1.0 
** Function To get latest items From Database [users | Items | Comments] 
** $select = Field to select
** $table  = the table to choose from 
** $limit  = limit number of records
** $order  = name of column that order by 
 */
Function getLatest($select, $table, $order, $limit = 5){
	global $con;

	$stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");

	$stmt->execute();

	$rows = $stmt->fetchAll();

	return $rows;
}

/*
** GET ALL Function V2.0 
** Function to Execute any SQL that can be shown in pages 
**  $field 		= Name of Column in table OR *
**  $table 		= Name of the Table it self
**  $Where 		= Default NULL, but can Write SQL Code to Execute EX:[WHERE ColumnName = 1] 
**  $orderField = Name of Coulmn you want to ORDER BY 
**  $ordering 	= Default equals DESC, you can change it to ASC 
*/
Function getAllFrom($field,$table,$where = NULL,$orderField,$ordering="DESC"){

	global $con;


	$query = $con->prepare("SELECT $field FROM $table $where ORDER BY $orderField $ordering");

	$query->execute();

	$result = $query->fetchAll();

	return $result;
}