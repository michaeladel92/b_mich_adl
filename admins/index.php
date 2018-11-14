<?php 
ob_start();
session_start();
//print_r($_SESSION); //check who current session user
	$noNavbar  = '';
	$pageTitle = 'Login';

//if session already exist redirect it to dashbord
if (isset($_SESSION['Username'])) {
		header('location: dashboard.php'); //Redirect Dashboard page
}
//initialize
include ("init.php");  


	// Check if User Coming From Http POST Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username   = $_POST['user'];
			$password   = $_POST['password'];
			$hashedPass = sha1($password); //sha1 more secured than md5

	//check if User Exist in Database		
	/**
	 * statment
	 *  -prepare the SQL and the [?] means that we will execute 
	 *  -GrouID ==> for Admin code 1 
	 *  -execute means to execute these two variables for the sql
	 *  -rowCount --> it count the result of execute 
	 */
			
	$stmt =  $con->prepare("SELECT 
									UserID,Username, Password 
															FROM users 
															WHERE Username = ?
															AND Password = ?
															AND GroupID = 1
															LIMIT 1
															 ");
	$stmt->execute(array($username, $hashedPass));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();
	
	//if $count > 0 this means the database Contain record  about this username
	if ($count > 0) {
	 	$_SESSION['Username'] = $username; //Register Session name
	 	$_SESSION['ID'] = $row['UserID'];  //Register Session ID 
	 	header('location: dashboard.php'); //Redirect Dashboard page
	 	exit(); // to stop secript
	 } 
		}	
?>
<div class="container" style="min-height: 500px;">
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<h4 class="text-center">Admin Login</h4>
	
	<!-- username -->
		<input class="form-control" type="text" name="user" placeholder="username" autocomplete="off">
	<!-- password -->
		<input class="form-control" type="password" name="password" placeholder="password" autocomplete="new-password">
	<!-- submit -->
		<input class="btn btn-primary btn-block" type="submit" name="" value="login">
</form>
</div>


<?php 
include ($tpl . "footer.php");
ob_end_flush();