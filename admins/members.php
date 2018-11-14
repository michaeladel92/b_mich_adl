<?php
/*
	=================================================
	== Manage  members page
	== You can Add | Edit | Delete Members  From here
	=================================================
 */
ob_start(); // output Buffering Start
session_start();

$pageTitle = 'Members';
if (isset($_SESSION['Username'])) {		
		
		include ("init.php");  

		$link = isset($_GET['get']) ? $_GET['get'] : 'members';

/*
================
Manage members Page
================		
*/
		if ($link == 'members') { 
				$query = '';

				if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
				$query = 'AND RegStatus = 0';
				}
					//Select all Users except Admins

					$stmt = $con->prepare("SELECT * FROM 
														users WHERE 
																GroupID != 1 $query 
															  ORDER BY 
															  UserID DESC
															  ");

					//Execute statment

					$stmt->execute();

					//Assign to Variable
					
					$rows = $stmt->fetchAll();

					if (!empty($rows)) {
		?>		
					
				<h1 class="text-center">Manage Members </h1>
				<div class="container" style="min-height: 400px;">
					<div class="table-responsive">
						<table class="main-table table table-bordered">
								<tr>
									<th>#ID</th>
									<th>Avatar</th>
									<th>Username</th>
									<th>Email</th>
									<th>Full Name</th>
									<th>Registered date</th>
									<th>Control</th>
								</tr>
						<?php 
								$num = 1;
								foreach ($rows as $row) { ?>
								<tr>
									<td><?php echo $row['UserID'];?></td>
									<td><img src="uploads/avatars/<?php echo ($row['avatar'] == ''?'default.jpg':$row['avatar']);?> "></td>
									<td><?php echo $row['Username'];?></td>
									<td><?php echo $row['Email'];?></td>
									<td><?php echo $row['FullName'];?></td>
									<td><?php echo $row['RegDate'];?></td>
									<td>
										<a href="members.php?get=Edit&userid=<?php echo $row['UserID'];?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
										<a href="members.php?get=Delete&userid=<?php echo $row['UserID'];?>" class="btn btn-danger  confirm"><i class="fa fa-trash"></i> Delete</a>
							<?php if ($row['RegStatus'] == 0) { ?>
										<a href="members.php?get=Activate&userid=<?php echo $row['UserID'];?>" class="btn btn-info">
											<i class="fas fa-check-circle"></i> Activate </a>
							<?php } ?>

									</td>
								</tr>
						<?php $num++;} ?>
						</table>
					</div>
					
					<a href='members.php?get=Add' class="btn btn-primary btn-md">
						<i class="fa fa-plus"></i> New Members</a>
				</div>

		<?php		
	}else{ ?>
				<div class="container" style="min-height: 400px;">
					<h2 class="alert alert-info text-center">There's No Record Found Yet</h2>
					<a href='members.php?get=Add' class="btn btn-primary btn-md">
						<i class="fa fa-plus"></i> New Members</a>
				</div>
<?php	}
/*
================
Add members Page
================		
 */
		}elseif($link =='Add'){ ?>

						<h1 class="text-center">Add New Member</h1>
					<div class="container">
						<form class="form-horizontal" action="?get=insert" method="POST" enctype="multipart/form-data">
						<!-- username -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Username</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="username to login into shop">
									</div>	
								</div>
							

								<!-- Password -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Password</label>
									<div class="col-sm-10 col-md-6">
										<input type="password"  name="password" class="password form-control" autocomplete="new-password" placeholder="password" required="required">
										<i class="show-pass fa fa-eye fa-2x"></i> 
									</div>	
								</div>
								<!-- Email -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Email</label>
									<div class="col-sm-10 col-md-6">
										<input type="Email" name="email"  class="form-control" autocomplete="off" required="required" placeholder="Email must be valid">
									</div>	
								</div>
								<!-- Full name -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Full Name</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="full"  class="form-control" required="required" placeholder="full name that apper in your profile page" autocomplete="off">
									</div>	
								</div>
									<!-- Avatar -->			
								<div >
									<label class="col-sm-2 control-label">Avatar</label>
									<div class="col-sm-10 col-md-6">
										<input type="file" name="avatar"   >
									</div>	
								</div>
								<!-- Submit -->			
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Add Member" class="btn btn-primary btn-lg">
									</div>	
								</div>
						</form>
					</div>	


		<?php
/*
================
Insert members
================		
 */
		 }elseif ($link == 'insert') { 

			if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

					<h1 class="text-center">Insert Member</h1>
					<div class="container" style="min-height: 500px;">
		<?php

						//Upload Photo ==> he said check file system
						$avatar   = $_FILES['avatar'];
						
						$img_name = $avatar['name'];
						$img_type = $avatar['type'];
						$img_tmp  = $avatar['tmp_name'];
						$img_err  = $avatar['error'];
						$img_size = $avatar['size'];
						

						//Get variables From The Form
							
						$user 	  = $_POST['username'];	
						$pass     = $_POST['password'];
 						$email 	  = $_POST['email'];	
						$name 	  = $_POST['full'];	
						$hashpass = sha1($_POST['password']);
							
							/*
							==========================================================
							==Note that if you Submit an empty pass you will
							== find that there is an hash for sha1 which is 
									da39a3ee5e6b4b0d3255bfef95601890afd80709
							that is why in server side validation it consider not empty
							that is why we prefer to put in server side $hashSha1 but 
							check in client side the $pass
							===========================================================
							 */
						//Validate Form

						$formErrors =array();		
								
						if (empty($user)) {
									$formErrors[] = "Username Can't be <strong>empty</strong>";			
							}	
						if (strlen($user) < 4) {
									$formErrors[] = "Username Can't be less than <strong>4 charachter's</strong>";			
							}
						if (strlen($user) > 20) {
									$formErrors[] = "Username Can't be more than <strong>20 charachter's</strong>";			
							}
						if (empty($email)) {
									$formErrors[] = "Email Can't be <strong>empty</strong>";

							}
						if (empty($pass)) {
									$formErrors[] = "Password Can't be <strong>empty</strong>";	

							}		
						if (empty($name)) {
									$formErrors[] = "Full Name Can't be <strong>empty</strong>";

							}	
							
	
	
						
						//Loop into error array	
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger' style='margin:5px auto; width:400px;'>" . $error ."</div>";
						}
							
					//if Image Not Empty
					if (!empty($img_name)) {
									//check size
									if ($img_size > 4194304) {
												$formErrors[] =  "Avatar can't be Larger than 4 mb ";

									}else{
												//list of allow file typed		
												$allowExtentions = array('jpeg','jpg','png','gif');
													//get Avatar Extentions	
													$extention = explode("." , $img_name);
													$extention = end($extention);
													$extention = strtolower($extention);

											if (!in_array($extention,$allowExtentions)) {
														$formErrors[] =  "this Extention is NOT Allow ";

											}else{
													
												$avatar = rand(0, 100000) . '_' . $img_name;
							
												move_uploaded_file($img_tmp,"uploads\avatars\\" . $avatar);
								
								
												//check if user Exist to Prevent Duplicate
								
												$check = checkItem("Username","users",$user);

												if ($check == 1) {

													$TheMsg = "<div class='alert alert-danger'>sorry <b> $user  </b> already exist</div>";
														redirectHome($TheMsg,'back');
												}else{

														//Insert New user Info in Database
														//RegStatus Added by Admin Auto Activate to 1 
								
													$stmt = $con->prepare("INSERT INTO 
																	users(Username,Password,Email,FullName,RegStatus,RegDate,avatar)
																	VALUES(:zuser, :zpass, :zemail, :zname,1, now(),:zavatar)
																	");
													$stmt->execute(array(
																	'zuser'   => $_POST['username'],
																	'zpass'   => $hashpass,
																	'zemail'  => $_POST['email'],
																	'zname'   => $name,
																	'zavatar' => $avatar
																));

													//echo Success Message
											
													$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 
											
													redirectHome($TheMsg,'back');
													}	

												}	
											}

						//Loop into error array	
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger' style='margin:5px auto; width:400px;'>" . $error ."</div>";
						}		
				
							}else{

						//Check if there is no Errors Proceed the Update Operation
						if (empty($formErrors)) {
								
								
								//check if user Exist to Prevent Duplicate
								
								$check = checkItem("Username","users",$user);

								if ($check == 1) {

											$TheMsg = "<div class='alert alert-danger'>sorry <b> $user  </b> already exist</div>";
											redirectHome($TheMsg,'back');
										}else{

											//Insert New user Info in Database
											//RegStatus Added by Admin Auto Activate to 1 
								
											$stmt = $con->prepare("INSERT INTO 
																	users(Username,Password,Email,FullName,RegStatus,RegDate,avatar)
																	VALUES(:zuser, :zpass, :zemail, :zname,1, now(),:zavatar)
																	");
											$stmt->execute(array(
																	'zuser'   => $_POST['username'],
																	'zpass'   => $hashpass,
																	'zemail'  => $_POST['email'],
																	'zname'   => $name,
																	'zavatar' => 'default.jpg'
																));

											//echo Success Message
											
											$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 
											
											redirectHome($TheMsg,'back');
										}	
									}
								}	
					}else{
						//Function redirectHome()
						echo "<div class='container'>";
						
						$TheMsg = '<div class="alert alert-danger">Sorry you Can\'t Browse this page Directly</div>';
						
						redirectHome($TheMsg);
						
						echo "</div>";
				}
				echo "</div>";
/*
================
Edit members Page
================		
 */
		}elseif ($link == 'Edit') {

					//check if GET request userid is numeric and get the Interger Value
					
				$userid = isset($_GET['userid'])  && is_numeric($_GET['userid'])? 
													intval($_GET['userid']) : 0;
					
					// select all data depend of this id 
					
				$stmt =  $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

					//Execute Query 	
					
				$stmt->execute(array($userid));

					//fetch The data
					
				$row = $stmt->fetch();

					//The row Count
					
				$count = $stmt->rowCount();		

					//If there's Such Id Show the form
												
				if ($count > 0) {      ?>
				
					<h1 class="text-center">Edit Member</h1>
					<div class="container">
						<form class="form-horizontal" action="?get=Update" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="userid" value="<?php echo $userid; ?>">
						<!-- username -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Username</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="username"value="<?php echo $row['Username'];?>" class="form-control" autocomplete="off" required="required" placeholder="username">
									</div>	
								</div>

								<!-- Password -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Password</label>
									<div class="col-sm-10 col-md-6">
										<input type="hidden" name="oldpassword" value="<?php echo $row['Password'];?>">
										<input type="password" name="newpassword" class="password form-control" autocomplete="new-password" placeholder="leave blank if you dont want to change">
										<i class="show-pass fa fa-eye fa-2x"></i> 
									</div>	
								</div>
								<!-- Email -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Email</label>
									<div class="col-sm-10 col-md-6">
										<input type="Email" name="email" value="<?php echo $row['Email'];?>" class="form-control" autocomplete="off" required="required" placeholder="Email">
									</div>	
								</div>
								<!-- Full name -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Full Name</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="full" value="<?php echo $row['FullName'];?>" class="form-control" required="required" placeholder="full name">
									</div>	
								</div>
								<!-- Avatar -->			
								<div >
									<label class="col-sm-2 control-label">Avatar</label>
									<div class="col-sm-10 col-md-6">
										<input type="file" name="avatar"   >
									</div>	
								</div>
								<!-- Submit -->			
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Save" class="btn btn-primary btn-lg">
									</div>	
								</div>
						</form>
					</div>	

	<?php	
				//If There is No Such ID  Error Message
				}else{
						echo "<div class='container'>";
						
						$TheMsg = '<div class="alert alert-danger">There is No Such ID</div>';
						
						redirectHome($TheMsg);
						
						echo "</div>";
				}	
		
/*
===================
Update members 
===================		
 */

		}elseif($link == 'Update'){  

								if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

					<h1 class="text-center">Update Member</h1>
					<div class="container">
		<?php

						//Upload Photo ==> he said check file system
						$avatar   = $_FILES['avatar'];
						
						$img_name = $avatar['name'];
						$img_type = $avatar['type'];
						$img_tmp  = $avatar['tmp_name'];
						$img_err  = $avatar['error'];
						$img_size = $avatar['size'];
						
						//Get variables From The Form
							
						$id 	= $_POST['userid'];	
						$user 	= $_POST['username'];	
						$email 	= $_POST['email'];	
						$name 	= $_POST['full'];	

						//password trick Condition ? true : false
						
						$pass = empty($_POST['newpassword'])? 
								$_POST['oldpassword'] : sha1($_POST['newpassword']);	
						
						//Validate Form

						$formErrors =array();		
								
						if (empty($user)) {
									$formErrors[] = "Username Can't be <strong>empty</strong>";			
							}	
						if (strlen($user) < 4) {
									$formErrors[] = "Username Can't be less than <strong>4 charachter's</strong>";			
							}
						if (strlen($user) > 20) {
									$formErrors[] = "Username Can't be more than <strong>20 charachter's</strong>";			
							}
						if (empty($email)) {
									$formErrors[] = "Email Can't be <strong>empty</strong>";			
							}	
						if (empty($name)) {
									$formErrors[] = "Full Name Can't be <strong>empty</strong>";			
							}	
						//Loop into error array	
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger'>" . $error ."</div>";
						}
							

											//if Image Not Empty
					if (!empty($img_name)) {

									//check size
									if ($img_size > 4194304) {
												$formErrors[] =  "Avatar can't be Larger than 4 mb ";

									}else{
													//list of allow file typed		
													$allowExtentions = array('jpeg','jpg','png','gif');
													//get Avatar Extentions	
													$extention = explode("." , $img_name);
													$extention = end($extention);
													$extention = strtolower($extention);

											if (!in_array($extention,$allowExtentions)) {
														$formErrors[] =  "this Extention is NOT Allow ";

											}else{
													
												$avatar = rand(0, 100000) . '_' . $img_name;
							
												move_uploaded_file($img_tmp,"uploads\avatars\\" . $avatar);
								
												//Check if there is an Deplucit Entry
												$check = $con->prepare("SELECT * 
																			FROM 
																				users 
																			WHERE
																				 Username = ?
																			 AND
																			     UserID != ? ");
												$check->execute(array($user,$id));

												$count = $check->rowCount();	

												if ($count == 1) {
													$TheMsg = '<div class="alert alert-danger">Sorry User <b>'.$user. '</b> already Exist </div>'; 

												redirectHome($TheMsg,'back');
												}else{

													//Update Database with these Info
										
												$stmt = $con->prepare("UPDATE users 
																					SET
																					 Username = ?,
																					 Email    = ?,
																					 FullName = ?,
																					 Password = ?,
																					 avatar   = ?
																					WHERE
																					 UserID   = ?	 	
																					 ");
												$stmt->execute(array($user,$email,$name,$pass,$avatar, $id)); 

												//echo Success Message
												
												$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

												redirectHome($TheMsg,'back');

										}

												}	
											}

						//Loop into error array	
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger' style='margin:5px auto; width:400px;'>" . $error ."</div>";
						}		
				
							}else{
									//Check if there is no Errors Proceed the Update Operation
										if (empty($formErrors)) {

												//Check if there is an Deplucit Entry
												$check = $con->prepare("SELECT * 
																			FROM 
																				users 
																			WHERE
																				 Username = ?
																			 AND
																			     UserID != ? ");
												$check->execute(array($user,$id));

												$count = $check->rowCount();	
												if ($count == 1) {
													$TheMsg = '<div class="alert alert-danger">Sorry User <b>'.$user. '</b> already Exist </div>'; 

												redirectHome($TheMsg,'back');
												}else{

													//Update Database with these Info
										
												$stmt = $con->prepare("UPDATE users 
																					SET
																					 Username = ?,
																					 Email    = ?,
																					 FullName = ?,
																					 Password = ?
																					WHERE
																					 UserID   = ?	 	
																					 ");
												$stmt->execute(array($user,$email,$name,$pass, $id)); 

												//echo Success Message
												
												$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

												redirectHome($TheMsg,'back');

										}
									}
								}			

						}else{
							echo "<div class='container'>";
								
								$TheMsg = '<div class="alert alert-danger">You can\'t browse this Page Directly</div>';
								
								redirectHome($TheMsg);
								
							echo "</div>";
						}
					echo "</div>";
		
/*
================
Delete members
================		
 */
		}elseif ($link == 'Delete') { ?>

					<h1 class="text-center">Delete Member</h1>
					<div class="container">
		<?php					
					//check if GET request userid is numeric and get the Interger Value
					
				$userid = isset($_GET['userid'])  && is_numeric($_GET['userid'])? 
													intval($_GET['userid']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("UserID","users",$userid);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :userid
						$stmt = $con->prepare("DELETE FROM users WHERE UserID = :userid");

					//binding the userid		

						$stmt->bindParam(':userid',$userid);

						$stmt->execute();
					
					//echo Success Message
						echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Delete </div>';
						
							redirectHome($TheMsg ,'back');
						
						echo "</div>";
					
				}else{
					echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-danger"> There is no Record found</div>';
						
							redirectHome($TheMsg);
						
						echo "</div>";
				}

				echo "</div>";
/*
================
Activate members
================		
 */
		}elseif ($link = 'Activate') { ?>

					<h1 class="text-center">Active Member</h1>
					<div class="container">
		<?php					
					//check if GET request userid is numeric and get the Interger Value
					
				$userid = isset($_GET['userid'])  && is_numeric($_GET['userid'])? 
													intval($_GET['userid']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("UserID","users",$userid);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :userid
						$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = :userid");

					//binding the userid		

						$stmt->bindParam(':userid',$userid);

						$stmt->execute();
					
					//echo Success Message
						echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Activate </div>';
						
							redirectHome($TheMsg);
						
						echo "</div>";
					
				}else{
					echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-danger"> There is no Record found</div>';
						
							redirectHome($TheMsg);
						
						echo "</div>";
				}

				echo "</div>";
		}			

		 include ($tpl . "footer.php");
}else{
	header("location:index.php"); //redirect to login From
	exit();
}
ob_end_flush(); //Relase the OutPut