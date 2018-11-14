<?php
ob_start(); //output buffer
session_start();
$pageTitle = 'Login';

//check if user login [Note==> SESSIOn name must be not same as admin ]
if (isset($_SESSION['user'])) {
	header('location:index.php'); //Redirect to home page
}

//initialize
include("init.php");

	// Check if User Coming From Http POST Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		if (isset($_POST['login'])) {
				
						$user   = $_POST['username'];
						$pass   = $_POST['password'];
						$hashedPass = sha1($pass); //sha1 more secured than md5

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
																		 ");
				$stmt->execute(array($user, $hashedPass));

				$get = $stmt->fetch();	

				$count = $stmt->rowCount();
				
				//if $count > 0 this means the database Contain record  about this username
				if ($count > 0) {
				 	$_SESSION['user']    = $user; //Register Session name
				 	$_SESSION['user_id'] = $get['UserID']; //Register user ID
				 	 header('location: index.php'); //Redirect Home page
				 	 exit(); // to stop secript
				 } 
		

		}else { //sign UP
		
				$formErrors = array();

				$username = $_POST['username'];
				$password = $_POST['password'];
				$co_pass  = $_POST['con-password'];
				$email    = $_POST['email'];

				// username
				if (isset($username)) {
						//filter any tags 
						$filterUser = filter_var($username, FILTER_SANITIZE_STRING);

						//Validate
						if (strlen($filterUser) < 4) {
							$formErrors[] = 'Username must be more than 4 characters' ;
						}
				}

				//Password
				if (isset($password) AND isset($co_pass)) {

						if (empty($_POST['password'])) {
								$formErrors[] = 'Passowrd Can\'t be empty ' ;
							
						}
						
						//check if both identical 	
							if (sha1( $password ) !== sha1($co_pass)) {
								$formErrors[] = 'Passowrd dosen\'t Match ' ;
							}

				}

				//Email
				if (isset($email)) {
						
						$filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
						
						if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != TRUE) {
								
								$formErrors[] = 'Email Not Valid ' ;

						}

				}

				//Check if there is no Errors Proceed the ADD Operation
				if (empty($formErrors)) {
								
								//check if user Exist to Prevent Duplicate
								
								$check = checkItem("Username","users",$username);

								if ($check == 1) {

											$formErrors[] =  "Sorry <b> $username  </b> already exist in our database";
											
										}else{

											//Insert New user Info in Database
											//RegStatus Added by Admin Auto Activate to 1 
								
											$stmt = $con->prepare("INSERT INTO 
																	users(Username,Password,Email,RegStatus,RegDate)
																	VALUES(:zuser, :zpass, :zemail,0, now())
																	");
											$stmt->execute(array(
																	'zuser'  => $username,
																	'zpass'  => sha1($password),
																	'zemail' => $_POST['email']
																));

											//echo Success Message
											
											$successMsg= 'Congrats You\'ve Successfully Registered'; 
											
											
										}	
						}	





		}	
	}	
?>


<div class="container login-page" style="min-height: 400px;">
	<h1 class="text-center">
		<!-- These data-class are the relation of the form class -->
		<span class="selected" data-class="login">Login</span> |
		<span data-class="signup">Signup</span> 
	</h1>
	<!-- 
		login FORM 
					-->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">	
		<!-- username -->
		<div class="input-container">
			<input   class="form-control"
					 type="text" 
					 name="username"
					 autocomplete="off"
					 placeholder="Username" 	
					 >
		</div>
	
		
		<!-- password -->
		<input   class="form-control"
				 type="password"
				 name="password"
				 autocomplete="new-password"
				 placeholder="Password" 		
				 >

		<!-- Submit -->
		<input  class="btn btn-primary btn-block" 
				type="submit"
				name="login" 
				value="Login">	

	</form>

	<!-- 
		SignUp FORM 
					-->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">	
				<!-- username -->
				<div class="input-container">
				<input   class="form-control"
						 pattern=".{4,20}"
						 title="Username must be Between 4 & 20 Chars" 
						 type="text" 
						 name="username"
						 autocomplete="off"
						 placeholder="Username" 
						 required 
							
						 >
				</div>
				<!-- password -->
				<div class="input-container">
				<input   class="form-control"
						 minlength="4" 
						 type="password"
						 name="password"
						 autocomplete="new-password"
						 placeholder="Password"
						 required 	
						 >
				</div>
				<!-- con-password -->
				<div class="input-container">
				<input   class="form-control"
						 minlength="4" 				
						 type="password"
						 name="con-password"
						 autocomplete="new-password"
						 placeholder="confirm Password" 
						 required 
						
						 >
				</div>
				<!-- Email -->
				<div class="input-container">
				<input   class="form-control"
						 type="email"
						 name="email"
						 placeholder="Type a Valid Email" 	
						 required 
					
						 >
				</div>

				<!-- Submit -->
				<input  class="btn btn-success btn-block" 
						type="submit"
						name="signup" 
						value="Signup">	

	</form>
	<!-- Output Errors -->
	<div class="the-error text-center">
		<?php 
			 if (!empty($formErrors)) {
			 			
			 			foreach ($formErrors as $error) {
			 				 	
			 				echo "<div class='msg error'>" . $error .  "</div>";
			 				
			 			}
			 }

			 if (isset($successMsg)) {
			 				echo "<div class='msg success'>" . $successMsg .  "</div>";
			 	
			 }
		 ?>
	</div>
</div>






<?php 
	include ($tpl . "footer.php");
	ob_end_flush();
