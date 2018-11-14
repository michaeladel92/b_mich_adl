<?php 
session_start();
//title PAGE
$pageTitle = 'Create New Item';
//initialize
include ("init.php");  

if (isset($_SESSION['user'])) {


		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$formErrors = array();
		
			
			//Upload Photo ==> he said check file system
			$itmPhoto   = $_FILES['itmPhoto'];
			
			$img_name = $itmPhoto['name'];
			$img_type = $itmPhoto['type'];
			$img_tmp  = $itmPhoto['tmp_name'];
			$img_err  = $itmPhoto['error'];
			$img_size = $itmPhoto['size'];

			//SANITIZE for Security
		
			$name 	  = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
			$Description     = filter_var($_POST['Description'],FILTER_SANITIZE_STRING);
			$price 	  = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
			$status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
			$category = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
			$tags	  = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

			//Validation
			if (strlen($name) < 4) {
				$formErrors[] = "Title Can't be less than 4 Char";
			}

			if (strlen($name) > 25) {
				$formErrors[] = "Title Maximum length 25 Char";
			}

			if (strlen($Description) < 20) {
				$formErrors[] = "Description Can't be less than 10 Char";
			}
			
			if (empty($price)) {
				$formErrors[] = "Must Enter a Price & Should be a Number";
			}
			if (empty($price > 15)) {
				$formErrors[] = "Maximum 15 Digits ";
			}
			
			if (empty($status)) {
				$formErrors[] = "Must Choose one of the Status";
			}
			
			if (empty($category)) {
				$formErrors[] = "Must Choose Category for the Item";
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
																	'zmember'    => $_SESSION['user_id'],
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
																	'zmember'    => $_SESSION['user_id'],
																	'zcategory'  => $category,
																	'ztags' 	 => $tags,
																	'zitmPhoto'	 => 'default.png'

																));

											//echo Success Message
											
											if ($stmt) {
												
											$successMsg = 'New Item Added Successfully'; 

											}
											
									
						}

							}	




		}

?>
	<h1 class="text-center"><?php echo $pageTitle; ?> </h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-warning">
			<div class="panel-heading"><i class="fas fa-plus-circle fa-lg"></i> <?php echo $pageTitle; ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						<!-- form -->
								<form class="form-horizontal item_user_form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
							<!-- Name Field -->			
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Name</label>
										<div class="col-sm-10 col-md-8">
											<input 
												   pattern=".{4,}"
												   title="This field Required at least 4 char" 	
												   type="text"
												   name="name" 
												   class="form-control live"
												   placeholder="Name of the Item"
												   data-class=".live-title"	 
												   required 
												   >
										</div>	
									</div>
									
									<!-- Description -->			
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Description</label>
										<div class="col-sm-10 col-md-8">
											<textarea 
													  pattern=".{10,}"
												   	  title="This field Required at least 10 char" 	
													  name="Description"  
											          rows="5"
											          class="form-control live"
											          placeholder="Describe the Item"
												      data-class=".live-desc"
													  required	
											          ></textarea>
										</div>	
									</div>
									<!-- Price Field -->			
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Price</label>
										<div class="col-sm-10 col-md-8">
											<input type="number" 
												   autocomplete="off" 
												   name="price" 
												   class="form-control live"
												   placeholder="Price of the Item in Egyptian Currency"
												   data-class=".live-price"	 
												   required 
												   >
										</div>	
									</div>

											<!-- Status Field -->			
									<div class="form-group form-group-md">
										<label class="col-sm-3 control-label">Status</label>
										<div class="col-sm-5 col-md-8">
											<select class="form-control" name="status" required>
												<option value=""></option>
												<option value="1">New</option>
												<option value="2">Like New</option>
												<option value="3">Used</option>
												<option value="4">Old</option>
											</select>
										</div>	
									</div>
									

								<!-- categories Field -->			
								<div class="form-group form-group-md">
									<label class="col-sm-3 control-label">Categories</label>
									<div class="col-sm-5 col-md-8 ">
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
										<label class="col-sm-3 control-label">Tags</label>
										<div class="col-sm-10 col-md-8">
											<input type="text" name="tags" class="form-control" placeholder="Separate Tags with ( , )">
										</div>	
									</div>
								<!-- imgItem -->			
									<div >
										<label class="col-sm-3 control-label">Photo</label>
										<div class="col-sm-10 col-md-8">
											<input type="file" name="itmPhoto"   >
										</div>	
									</div>

									<!-- Submit -->			
									<div class="form-group form-group-lg">
										<div class="col-sm-offset-3 col-sm-10">
											<input type="submit" value="Add Item" class="btn btn-primary">
										</div>	
									</div>

							</form>
					</div>

					<!-- ads -->
					<div class="col-md-4">
								<div class="thumbnail item-box live_Prev">
													<span class="price-tag">LE
												<span class=" live-price">0</span>
													</span>									
												<img class="img-responsive" src="admins/uploads/item_photo/default.png">
												<div class="caption">
													<h4 class="live-title"></h4>
												</div>
											</div>
					</div>
				</div>

				<!-- Loop Errors -->
					<?php

							if (!empty($formErrors)) {
								
									foreach ($formErrors as $error) {
										echo "<div class='alert alert-danger'>" .$error."</div>";
									}
							}

							if (isset($successMsg)) {
								echo "<div class=\"alert alert-success\">".$successMsg ."</div>";
							}

					?>
			</div>
		</div>
	</div>
</div>




 <?php
}else{
	
		header('location:login.php');
		exit();
}
include ($tpl . "footer.php");