<?php


session_start();
//title PAGE
$pageTitle = 'Profile';
//initialize
include ("init.php");  

if (isset($_SESSION['user'])) {

		if (isset($_POST['Update'])) {
				
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
							
												move_uploaded_file($img_tmp,"admins\uploads\avatars\\" . $avatar);
								
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
		}






					//check if GET request userid is numeric and get the Interger Value
					
				$userid = isset($_GET['editUser'])  && is_numeric($_GET['editUser'])? 
													intval($_GET['editUser']) : 0;
					
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
						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] . "?editUser=" . $userid;?>" method="POST" enctype="multipart/form-data">
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
										<input type="submit" name="Update" value="Update" class="btn btn-primary btn-lg">
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


}else{
	
		header('location:login.php');
		exit();
}
include ($tpl . "footer.php");