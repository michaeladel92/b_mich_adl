<?php

ob_start();
session_start();
//title PAGE
$pageTitle = 'Profile';
//initialize
include ("init.php");  

if (isset($_SESSION['user'])) {

		if (isset($_GET['itemID']) && isset($_GET['UserID'])) { 

					//check if itemID == Session	
					 $checkID = $con->prepare("SELECT itemID,Member_ID FROM items WHERE itemID = ?");

			        $checkID->execute(array($_GET['itemID']));

			        $check = $checkID->fetch();

				if ($_GET['UserID'] == $_SESSION['user_id'] && $check['Member_ID'] == $_SESSION['user_id']  ) {



			        		
			        		

					if (isset($_POST['updateItem'])) {
						//Upload Photo ==> he said check file system
						$itmPhoto   = $_FILES['itmPhoto'];
						
						$img_name = $itmPhoto['name'];
						$img_type = $itmPhoto['type'];
						$img_tmp  = $itmPhoto['tmp_name'];
						$img_err  = $itmPhoto['error'];
						$img_size = $itmPhoto['size'];
						//Get variables From The Form
							
						$itemID 	  = $_POST['itemID'];	
						$name 	  	  = $_POST['name'];	
						$Description  = $_POST['Description'];
 						$price 	  	  = $_POST['price'];	
						$status 	  = $_POST['status'];	
						$category 	  = $_POST['category'];	
						$tags   	  = $_POST['tags'];		
	
						 	
				
						
						//Validate Form

						$formErrors =array();		
						
						if (empty($name)) {
									$formErrors[] = "Item name Can't be <strong>empty</strong>";			
							
							}if (strlen($name) < 4) {
									$formErrors[] = "Item name Can't be less than <strong>4 charachter's</strong>";			
							}if (strlen($name) > 20) {
									$formErrors[] = "item name Can't be more than <strong>20 charachter's</strong>";			
							}if (empty($Description)) {
									$formErrors[] = "Item Description Can't be <strong>empty</strong>";			
							
							}if (empty($price)) {
									$formErrors[] = "Item Price Can't be <strong>empty</strong>";			

							}if ($status === '0') {
									$formErrors[] = "You must choose Item <strong>status</strong>";								
							}if ($category === '0') {
									$formErrors[] = "You must choose Item <strong>Category</strong>";								
							}
						
						//Loop into error array	
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger text-center' style='margin:5px auto; width:500px;' >" . $error ."</div>";
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
													
												$itmPhoto = rand(0, 100000) . '_' . $img_name;
							
												move_uploaded_file($img_tmp,"admins\uploads\item_photo\\" . $itmPhoto);
								
													//Update Database with these Info
						
																$stmt = $con->prepare("UPDATE items 
																									SET
																									 itemName 	   = ?,
																									 descItem      = ?,
																									 Price		   = ?,
																									 status 	   = ?,
																									 Cat_ID 	   = ?,
																									 tags          = ?,
																									 itmPhoto      =?


																									WHERE
																									 itemID   = ?	 	
																											 ");
																$stmt->execute(array(	$name,	  	 
																						$Description ,
																 						$price, 	  	 	
																						$status ,
																						$category ,			
																						$tags,
																						$itmPhoto,	 
																						$itemID
																					)); 

																//echo Success Message
																
																$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

																redirectHome($TheMsg,'back');



												}	
											}

						//Loop into error array	
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger' style='margin:5px auto; width:400px;'>" . $error ."</div>";
						}		
				
							}else{
									//Check if there is no Errors Proceed the Update Operation
									if (empty($formErrors)) {
											
											//Update Database with these Info
									
											$stmt = $con->prepare("UPDATE items 
																				SET
																				 itemName 	   = ?,
																				 descItem      = ?,
																				 Price		   = ?,
																				 status 	   = ?,
																				 Cat_ID 	   = ?,
																				 tags          = ?


																				WHERE
																				 itemID   = ?	 	
																				 ");
											$stmt->execute(array(	$name,	  	 
																	$Description ,
											 						$price, 	  	 	
																	$status ,
																	$category ,			
																	$tags,	 
																	$itemID,
																)); 

											//echo Success Message
											
											$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

											redirectHome($TheMsg,'back');


									}
								}		
							}


					$itemID = $_GET['itemID'];
					$userID = $_SESSION['user_id'];		

				$stmt =  $con->prepare("SELECT * FROM items WHERE itemID = ?");

					//Execute Query 	
					
				$stmt->execute(array($itemID));

					//fetch The data
					
				$item = $stmt->fetch();

					//The row Count
					
				$count = $stmt->rowCount();		

					//If there's Such Id Show the form
												
				if ($count > 0) { ?>
									
									<h1 class="text-center">Edit Item</h1>
					<div class="container" style="min-height: 400px;" >
						<div class="row">
						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ."?itemID=". $itemID ."&UserID=". $userID;  ?>" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
						<!-- Name Field -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Name</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="name" class="form-control" required="required" placeholder="Name of the Item" 
										value="<?php echo $item['itemName'];?>">
									</div>	
								</div>
								
								<!-- Description -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Description</label>
									<div class="col-sm-10 col-md-6">
										<textarea name="Description"  rows="5" class="form-control" required="required" placeholder="Describe the Item" ><?php echo $item['descItem'];?></textarea>
									</div>	
								</div>
								<!-- Price Field -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Price</label>
									<div class="col-sm-10 col-md-6">
										<input type="number" name="price" class="form-control" required="required" placeholder="Price of the Item" value="<?php echo $item['Price'];?>" >
									</div>	
								</div>

										<!-- Status Field -->			
								<div class="form-group form-group-md">
									<label class="col-sm-2 control-label">Status</label>
									<div class="col-sm-5 col-md-3">
										<select class="form-control" name="status">
											<option value="0"></option>
											<option value="1" <?php echo ($item['status'] == 1?'selected': '');?>>New</option>
											<option value="2" <?php echo ($item['status'] == 2?'selected': '');?>>Like New</option>
											<option value="3" <?php echo ($item['status'] == 3?'selected': '');?>>Used</option>
											<option value="4" <?php echo ($item['status'] == 4?'selected': '');?>>Old</option>
										</select>
									</div>	
								</div>

								<!-- categories Field -->			
								<div class="form-group form-group-md">
									<label class="col-sm-2 control-label">Categories</label>
									<div class="col-sm-5 col-md-3 ">
										<select class="form-control" name="category">
											<option value="0"></option>
											<?php
											$stmt = $con->prepare("SELECT * FROM categories");
											$stmt->execute();
											$categories = $stmt->fetchAll();
											foreach ($categories as $category) {
													echo "<option value='".$category['category_id']."'".($item['Cat_ID'] == $category['category_id']?'selected': '').">".$category['Name']."</option>";
												}	
											?>
										</select>
									</div>	
								</div>
							<!-- Tags Field http://aehlke.github.io/tag-it/ -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Tags</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="tags" class="form-control" placeholder="Separate Tags with ( , )" value="<?php echo $item['tags'];?>">
									</div>	
								</div>
							<!-- imgItem -->			
								<div >
									<label class="col-sm-2 control-label">Photo</label>
									<div class="col-sm-10 col-md-6">
										<input type="file" name="itmPhoto"   >
									</div>	
								</div>
							<!-- Submit -->			
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" name="updateItem" value="Update Item" class="btn btn-primary btn-lg">
									</div>	
								</div>

						</form>	
					</div>
				</div>
			<?php }else{

					echo '<div class="container" style="text-align: center; min-height: 400px;">';
					echo '<h3 class="alert alert-info col-md-5 col-md-offset-3" style="margin-top: 100px;">Page not found..</h3>';
					echo '</div>';
			} ?>					
						
			<?php	}else{
				header("location:profile.php");
			}

			
			
		
	}else{?>
		
				<div class="container" style="text-align: center; min-height: 400px;">
					<h3 class="alert alert-info col-md-5 col-md-offset-3" style="margin-top: 100px;">Page not found..</h3>
				</div>
	<?php }

	?>

				

<?php
}else{
	
		header('location:login.php');
		exit();
}
include ($tpl . "footer.php");
ob_end_flush();