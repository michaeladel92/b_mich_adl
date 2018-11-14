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

/*
** GET  Categories Function V1.0 
** Function To get Categories From Database  
 */
Function getCat(){
	global $con;

	$getCat = $con->prepare("SELECT * FROM categories ORDER BY category_id");

	$getCat->execute();

	$cats = $getCat->fetchAll();

	return $cats;
}


/*
** GET  Items Function V2.0 
** Function To get Items From Database  
 */
Function getItems($where, $value,$approve = NULL){
	global $con;

	if ($approve == NULL) {
		$sql = 'AND Approve = 1';
	}else{
		$sql = NULL;
	}

	$getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY itemID DESC");

	$getItems->execute(array($value));

	$items = $getItems->fetchAll();

	return $items;
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
 ** check if user Activated V1.0
 ** Function to check the regStatus of the User
 ** 
 */

function checkUserStatus($user){
	global $con;
		$stmt =  $con->prepare("SELECT 
									Username, RegStatus 
														FROM users 
														WHERE Username = ?
														AND RegStatus = 0
															 ");
		$stmt->execute(array($user));
		$status = $stmt->rowCount();

		return $status;
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