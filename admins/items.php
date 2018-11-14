<?php
/*
	=================================================
	== Items  page [Items + Add + Update + Delete ]
	=================================================
 */
ob_start(); // output Buffering Start
session_start();

$pageTitle = 'Items';
if (isset($_SESSION['Username'])) {		
		
		include ("init.php");  

		$link = isset($_GET['get']) ? $_GET['get'] : 'items';

/*
================
   Manage  Items
================		
*/	
		if ($link == 'items') { 


					//Select all Users except Admins

					$stmt = $con->prepare("SELECT items.*,
												  categories.Name AS categoryName,
												  users.Username
												  	FROM 
												  		items
													INNER JOIN 
														categories 
													ON
														 categories.category_id = items.Cat_ID 
													INNER JOIN 
														users 
													ON
														 users.UserID = items.Member_ID
													ORDER BY 
													itemID DESC	 
										");

					//Execute statment

					$stmt->execute();

					//Assign to Variable
					
					$items = $stmt->fetchAll();

					if (!empty($items)) {
					
		?>		
					
				<h1 class="text-center">Manage items </h1>
				<div class="container" style="min-height: 400px;">
					<div class="table-responsive">
						<table class="main-table  table table-bordered table-condensed table-hover">
								<tr>
									<th>#ID</th>
									<th>Photo</th>
									<th>Name</th>
									<th>Price</th>
									<th>Adding date</th>
									<th>Catgeory</th>
									<th>UserName</th>
									<th>Control</th>
								</tr>
						<?php 
								$num = 1;
								foreach ($items as $item) { ?>
								<tr>
									<td><?php echo $item['itemID'];?></td>
									<td><img src="uploads/item_photo/<?php echo ($item['itmPhoto'] == ''?'default.png':$item['itmPhoto']);?> ">
									</td>
									<td><a target="_blank" href="../items.php?itemID=<?php echo $item['itemID']; ?>"><?php echo $item['itemName'];?></a></td>
									<td><?php echo $item['Price'];?> LE</td>
									<td><?php echo $item['item_date'];?></td>
									<td><?php echo $item['categoryName'];?></td>
									<td><?php echo $item['Username'];?></td>

									<td>
										<a href="items.php?get=Edit&itemID=<?php echo $item['itemID'];?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
										<a href="items.php?get=Delete&itemID=<?php echo $item['itemID'];?>" class="btn btn-danger  confirm"><i class="fa fa-trash"></i> Delete</a>
							<?php if ($item['Approve'] == 0) { ?>
										<a href="items.php?get=Approve&itemID=<?php echo $item['itemID'];?>" class="btn btn-info">
											<i class="fas fa-check-circle"></i> Approve </a>
							<?php } ?>
									</td>
								</tr>
						<?php $num++;} ?>
						</table>
					</div>
					
					<a href='items.php?get=Add' class="btn btn-primary btn-md">
						<i class="fa fa-plus"></i> Add Item</a>
									
				</div>
		<?php		
				}else{ ?>
					<div class="container" style="min-height: 400px;">
					<h2 class="alert alert-info text-center">There's No Record Found Yet</h2>
					<a href='items.php?get=Add' class="btn btn-primary btn-md">
						<i class="fa fa-plus"></i> Add Item</a>
				</div>
		<?php		}
/*
================
   Add  Items
================		
*/				
		}elseif($link =='Add'){	?>

						<h1 class="text-center">Add New Items</h1>
					<div class="container" style="min-height: 400px;">
						<form class="form-horizontal" action="?get=insert" method="POST" enctype="multipart/form-data">
						<!-- Name Field -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Name</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="name" class="form-control" required="required" placeholder="Name of the Item">
									</div>	
								</div>
								
								<!-- Description -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Description</label>
									<div class="col-sm-10 col-md-6">
										<textarea name="Description"  rows="5" class="form-control" required="required" placeholder="Describe the Item" ></textarea>
									</div>	
								</div>
								<!-- Price Field -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Price</label>
									<div class="col-sm-10 col-md-6">
										<input type="number" name="price" class="form-control" required="required" placeholder="Price of the Item's in Egyptian Currency">
									</div>	
								</div>

										<!-- Status Field -->			
								<div class="form-group form-group-md">
									<label class="col-sm-2 control-label">Status</label>
									<div class="col-sm-5 col-md-3">
										<select class="form-control" name="status">
											<option value="0"></option>
											<option value="1">New</option>
											<option value="2">Like New</option>
											<option value="3">Used</option>
											<option value="4">Old</option>
										</select>
									</div>	
								</div>
									<!-- Members Field -->			
								<div class="form-group form-group-md">
									<label class="col-sm-2 control-label">Members</label>
									<div class="col-sm-5 col-md-3">
										<select class="form-control" name="member">
											<option value="0"></option>
											<?php
											$allMembers = getAllFrom("*","users","","Username");

											foreach ($allMembers as $user) {
													echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
												}	
											?>
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
											$allCats = getAllFrom("*","categories","WHERE parent = 0","category_id");
											foreach ($allCats as $category) {
													echo "<option value='".$category['category_id']."'>".$category['Name']."</option>";
													$ChildCats = getAllFrom("*","categories","WHERE parent = {$category['category_id']}","category_id");

													foreach ($ChildCats as $child) {
														echo "<option value='".$child['category_id']."'>------- ".$child['Name']."</option>";
													}
												}	
											?>
										</select>
									</div>	
								</div>
							<!-- Tags Field http://aehlke.github.io/tag-it/ -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Tags</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="tags" class="form-control" placeholder="Separate Tags with ( , )">
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
										<input type="submit" value="Add Item" class="btn btn-primary btn-lg">
									</div>	
								</div>

						</form>
					</div>


	<?php
/*
================
   Insert  Items
================		
*/	
		 }elseif ($link == 'insert') { 
		 				echo "<div class='container text-center' style=\"min-height: 500px; \">";
		 		if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
 
					<h1 class="text-center" >Insert Items</h1>
		<?php
						
						//Upload Photo ==> he said check file system
						$itmPhoto   = $_FILES['itmPhoto'];
						
						$img_name = $itmPhoto['name'];
						$img_type = $itmPhoto['type'];
						$img_tmp  = $itmPhoto['tmp_name'];
						$img_err  = $itmPhoto['error'];
						$img_size = $itmPhoto['size'];
						//Get variables From The Form
							
						$name 	  	  = $_POST['name'];	
						$Description  = $_POST['Description'];
 						$price 	  	  = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);	
						$status 	  = $_POST['status'];	
						$member 	  = $_POST['member'];	
						$category 	  = $_POST['category'];	
						$tags 		  = $_POST['tags'];	

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

							}if ($member === '0') {
									$formErrors[] = "You must choose a <strong>member</strong>";								
							}if ($status === '0') {
									$formErrors[] = "You must choose Item <strong>status</strong>";								
							}if ($category === '0') {
									$formErrors[] = "You must choose Item <strong>Category</strong>";								
							}
						//Loop into error array	
				

						foreach ($formErrors as $error) {

							echo "<div class='alert alert-danger' style='width:400px; margin: 5px auto;'>" . $error ."</div>";
							}
        			
        			//if Image Not Empty
					if (!empty($img_name) AND empty($formErrors)) {
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
							
												move_uploaded_file($img_tmp,"uploads\item_photo\\" . $itmPhoto);
								
																				
											//Insert New user Info in Database
											//RegStatus Added by Admin Auto Activate to 1 
								
												$stmt = $con->prepare("INSERT INTO 
																	items(itemName,descItem,Price,item_date,status,Member_ID,Cat_ID,tags,itmPhoto)
																	VALUES(:zname, :zdesc, :zprice, now(), :zstatus,:zmember,:zcategory,:ztags,:zitmPhoto)
																	");
												$stmt->execute(array(
																	'zname'  	 => $name,
																	'zdesc'  	 => $Description,
																	'zprice' 	 => $price,
																	'zstatus'    => $status,
																	'zmember'    => $member,
																	'zcategory'  => $category,
																	'ztags' 	 => $tags,
																	'zitmPhoto'	 => $itmPhoto		

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
								
							
											//Insert New user Info in Database
											//RegStatus Added by Admin Auto Activate to 1 
								
											$stmt = $con->prepare("INSERT INTO 
																	items(itemName,descItem,Price,item_date,status,Member_ID,Cat_ID,tags,itmPhoto)
																	VALUES(:zname, :zdesc, :zprice, now(), :zstatus,:zmember,:zcategory,:ztags,:zitmPhoto)
																	");
											$stmt->execute(array(
																	'zname'  	 => $name,
																	'zdesc'  	 => $Description,
																	'zprice' 	 => $price,
																	'zstatus'    => $status,
																	'zmember'    => $member,
																	'zcategory'  => $category,
																	'ztags' 	 => $tags,
																	'zitmPhoto'  => 'default.png'	

																));

											//echo Success Message
											
											$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 
											
											redirectHome($TheMsg,'back');
									
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
   EDIT  Items
================		
*/
		}elseif ($link == 'Edit') {
					//check if GET request itemID is numeric and get the Interger Value
					
				$itemID = isset($_GET['itemID'])  && is_numeric($_GET['itemID'])? 
													intval($_GET['itemID']) : 0;
					
					// select all data depend of this id 
					
				$stmt =  $con->prepare("SELECT * FROM items WHERE itemID = ?");

					//Execute Query 	
					
				$stmt->execute(array($itemID));

					//fetch The data
					
				$item = $stmt->fetch();

					//The row Count
					
				$count = $stmt->rowCount();		

					//If there's Such Id Show the form
												
				if ($count > 0) {      ?>
								
									<h1 class="text-center">Edit Item</h1>
					<div class="container">
						<form class="form-horizontal" action="?get=Update" method="POST" enctype="multipart/form-data">
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
										<input type="text" name="price" class="form-control" required="required" placeholder="Price of the Item" value="<?php echo $item['Price'];?>" >
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
									<!-- Members Field -->			
								<div class="form-group form-group-md">
									<label class="col-sm-2 control-label">Members</label>
									<div class="col-sm-5 col-md-3">
										<select class="form-control" name="member">
											<option value="0"></option>
											<?php
											$stmt = $con->prepare("SELECT * FROM users");
											$stmt->execute();
											$users = $stmt->fetchAll();
											foreach ($users as $user) {
													echo "<option value='".$user['UserID']."' ".
													 ($item['Member_ID'] == $user['UserID']?'selected': '')." >".$user['Username']."</option>";
												}	
											?>
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
										<input type="submit" value="Update Item" class="btn btn-primary btn-lg">
									</div>	
								</div>

						</form>
<!--Comments That Copyed from comments mangment  -->
<?php
//Select all Comments 

					$stmt = $con->prepare("SELECT comments.*,
												  users.Username AS user_name
												  FROM 
												  		comments
												  INNER JOIN
												  		users
												  	ON
												  		users.UserID = comments.user_id 
												  	WHERE 
												  		item_id = ?				 
										 ");

					//Execute statment

					$stmt->execute(array($itemID));

					//Assign to Variable
					
					$rows = $stmt->fetchAll();
					//if There is No COMMENT	
					if (!empty($rows)) {
		?>		
					
				<h1 class="text-center">Manage [ <?php echo $item['itemName'];?> ] Comments </h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table  table table-bordered">
								<tr>
									<th>Comments</th>
									<th>User Name</th>
									<th>Added date</th>
									<th>Control</th>
								</tr>
						<?php 
								$num = 1;
								foreach ($rows as $row) { ?>
								<tr>
									<td><?php echo $row['comment'];?></td>
									<td><?php echo $row['user_name'];?></td>
									<td><?php echo $row['Com_date'];?></td>
									<td>
										<a href="comments.php?do=Edit&comid=<?php echo $row['com_ID'];?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
										<a href="comments.php?do=Delete&comid=<?php echo $row['com_ID'];?>" class="btn btn-danger  confirm"><i class="fa fa-trash"></i> Delete</a>
							<?php if ($row['status'] == 0) { ?>
										<a href="comments.php?do=Approve&comid=<?php echo $row['com_ID'];?>" class="btn btn-info">
											<i class="fas fa-check-circle"></i> Approve </a>
							<?php } ?>

									</td>
								</tr>
						<?php $num++;} ?>
						</table>
					</div>
					<?php
						}else{

				echo "<h3 class=\"alert alert-info text-center\">There is No Comments related to that item </h3>";
									
							}
					?>
				</div>

		
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
  Update  Items 
===================		
 */

		}elseif($link == 'Update'){  

					if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

					<h1 class="text-center">Update Item</h1>
					<div class="container" style="min-height: 500px;">
		<?php
						

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
						$member 	  = $_POST['member'];	
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

							}if ($member === '0') {
									$formErrors[] = "You must choose a <strong>member</strong>";								
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
							
												move_uploaded_file($img_tmp,"uploads\item_photo\\" . $itmPhoto);
								
													//Update Database with these Info
						
																$stmt = $con->prepare("UPDATE items 
																									SET
																									 itemName 	   = ?,
																									 descItem      = ?,
																									 Price		   = ?,
																									 status 	   = ?,
																									 Cat_ID 	   = ?,
																									 Member_ID     = ?,
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
																						$member ,
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
																				 Member_ID     = ?,
																				 tags          = ?


																				WHERE
																				 itemID   = ?	 	
																				 ");
											$stmt->execute(array(	$name,	  	 
																	$Description ,
											 						$price, 	  	 	
																	$status ,
																	$category ,			
																	$member ,
																	$tags,	 
																	$itemID,
																)); 

											//echo Success Message
											
											$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

											redirectHome($TheMsg,'back');


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
Delete Items
================		
 */
		}elseif ($link == 'Delete') { ?>
						<h1 class="text-center">Delete Member</h1>
						<div class="container">
		<?php					
					//check if GET request itemID is numeric and get the Interger Value
					
				$itemID = isset($_GET['itemID'])  && is_numeric($_GET['itemID'])? 
													intval($_GET['itemID']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("itemID","items",$itemID);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :userid
						$stmt = $con->prepare("DELETE FROM items WHERE itemID = :itemID");

					//binding the userid		

						$stmt->bindParam(':itemID',$itemID);

						$stmt->execute();
					
					//echo Success Message
						echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Delete </div>';
						
							redirectHome($TheMsg,'back');
						
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
Approve Item
================		
 */
		}elseif ($link = 'Approve') { ?>

					<h1 class="text-center">Approve item</h1>
					<div class="container">
		<?php					
					//check if GET request userid is numeric and get the Interger Value
					
				$itemID = isset($_GET['itemID'])  && is_numeric($_GET['itemID'])? 
													intval($_GET['itemID']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("itemID","items",$itemID);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :itemID
						$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE itemID = :itemID");

					//binding the userid		

						$stmt->bindParam(':itemID',$itemID);

						$stmt->execute();
					
					//echo Success Message
						echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Activate </div>';
						
							redirectHome($TheMsg,'back');
						
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