<?php
/*
	=================================================
	== Category  page [Home + Add + Edit + Delete]
	=================================================
 */
ob_start(); // output Buffering Start
session_start();

$pageTitle = 'Categories';
if (isset($_SESSION['Username'])) {		
		
		include ("init.php"); //include headers & initialize Routes  

		$link = isset($_GET['get']) ? $_GET['get'] : 'category';

/*================
Manage Category
================*/

		if ($link == 'category') { 

				//ASC & DES Query Options
				$sort ='ASC';
				$sort_array = array('ASC','DESC');
				if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
					$sort = $_GET['sort'];
				}

				//Function
				$query = getAllFrom("*","categories","WHERE parent = 0 ","category_id",$sort);
				
				if (! empty($query)) {
				?>

				<h1 class="text-center"> Manage Categories</h1>	
				<div class="container categories" style="min-height: 400px;">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fas fa-chart-bar fa-lg"></i> Manage Categories
							<div class="option pull-right">
								<i class="fas fa-sort"></i> Ordering: [
								<a class="<?php echo ($sort =='ASC'? 'active':'');?>" href="?sort=ASC">Asc</a> |
								<a class="<?php echo ($sort =='DESC'? 'active':'');?>"href="?sort=DESC">Desc</a> ]
								<i class="fas fa-eye"></i> View: [
								<span class="active" data-view="full">Full</span> |
								<span data-view="classic">Classic</span> ]
							</div>
						</div>
							<div class="panel-body">
								<?php
								foreach ($query as $category) { ?>
									<div class='cat'>
										<div class='hidden-button'>
											<a href='categories.php?get=Edit&cate_id=<?php echo $category['category_id'];?>'
											 class='btn btn-primary btn-xs'>
											 <i class='fa fa-edit'></i> Edit</a>
											
											<a href='categories.php?get=Delete&cate_id=
												<?php echo $category['category_id'];?>' 
												class='confirm btn btn-danger btn-xs'>
												<i class='fa fa-trash'></i> Delete</a>
										</div>

										<h3>  <?php echo $category['Name'];?></h3>
											
										<div class='full-view'>
											<!-- Category Discription -->
												<p> <?php echo ($category['Description'] ==''? 'This Category has no description': $category['Description']);?> </p> 
											<!-- category Visibility -->
												 <?php echo ($category['Visibility'] == 1 ?'<span class="visible"><i class="far fa-eye"></i> Hidden</span>':'');?>
											<!-- Category Allow_comment -->
												<?php echo ($category['Allow_Comment'] == 1 ?'<span class="comment"><i class="fas fa-times"></i> Comment Disabled</span>':'');
												?>
											<!-- Allow_Ads  -->
												 <?php
												 ($category['Allow_Ads'] == 1 ?'<span class="ads"><i class="fas fa-times"></i> Ads Disabled</span>':'');
												 ?>
					<?php
					/**================================
					 * subCategories are the sub title 
					 * 		in each Main Category
					  ===============================*/
						   $subCategories = getAllFrom("*","categories","WHERE parent = {$category['category_id']}","category_id");

						   	if(!empty($subCategories)){ ?>
						   	    <h4 class='child_head'>Sub [ <?php echo $category['Name'];?> ]:-</h4>
						   	<ul class='list-unstyled child_cats'>
				         
				     <?php
				          		 foreach ($subCategories as $sub) {  ?>
				                  
				                    <li class='child_category'>
				                     			<a href='categories.php?get=Edit&cate_id= 
				                     			<?= $sub['category_id'];?>'> <?= $sub['Name']; ?> 
				                     		    </a>

				                     			<a href='categories.php?get=Delete&cate_id=
												<?= $sub['category_id']; ?>' class='confirm show-delete'>
												<i class='fa fa-trash'></i>
												</a>
				                     </li> 
       						 <?php } ?>
       						 </ul>	
       						 <?php } ?>  
										</div>		
								  	</div>		
       						 	 <hr>
								<?php } ?>
							</div>
						</div>
					<a class="btn-add-category btn btn-primary" href="categories.php?get=Add"><i class="fa fa-plus"></i> Add Category</a>
				</div>	

	<?php
		}else{?>
					<div class="container" style="min-height: 400px;">
					<h2 class="alert alert-info text-center">There's No Record Found Yet</h2>
					<a class="btn-add-category btn btn-primary" href="categories.php?get=Add"><i class="fa fa-plus"></i> Add Category</a>
				</div>
		<?php		}
/*
================
Add Category
================		
 */				
		}elseif($link =='Add'){  ?>

			
						<h1 class="text-center">Add New Category</h1>
					<div class="container">
						<form class="form-horizontal" action="?get=insert" method="POST">
						<!-- Name Field -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Name</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the Category">
									</div>	
								</div>

								<!-- Description -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Description</label>
									<div class="col-sm-10 col-md-6">
										<textarea name="Description"  rows="5" class="form-control" placeholder="Describe the Category"></textarea>
									</div>	
								</div>
								<!-- Category Type -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Category Type</label>
									<div class="col-sm-10 col-md-6">
										<select name="categoryType">
											<option value="0">Main Category</option>
				<?php
					//get all main category 
					$allCats = getAllFrom("*","categories","WHERE parent = 0","category_id");
					foreach ($allCats as $category) {
					 	echo "<option value='".$category['category_id']."'>".$category['Name']."</option>";
					 } 
						
				?>
										</select>	
									</div>	
								</div>
								<!-- Visibility -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Visible</label>
									<div class="col-sm-10 col-md-6">
											<div>
												<input id="vis_yes" type="radio" name="Visibility" value="0" checked="checked">
												<label for="vis_yes">YES</label>
											</div>
											<div>
												<input id="vis_No" type="radio" name="Visibility" value="1" >
												<label for="vis_No">NO</label>
											</div>
									</div>	
								</div>
									<!-- Allow_Comment -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Allow Comment</label>
									<div class="col-sm-10 col-md-6">
											<div>
												<input id="com_yes" type="radio" name="comment" value="0" checked="checked">
												<label for="com_yes">YES</label>
											</div>
											<div>
												<input id="com_No" type="radio" name="comment" value="1" >
												<label for="com_No">NO</label>
											</div>
									</div>	
								</div>
								<!-- Submit -->			
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Add Category" class="btn btn-primary btn-lg">
									</div>	
								</div>
						</form>
					</div>


	<?php
/*================
Insert Category
================*/
		 }elseif ($link == 'insert') { 

		 		if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

					<h1 class="text-center">Insert Category</h1>
					<div class="container"  style="min-height:500px;">
	<?php
						//Get variables From The Form
							
						$nameCategory	 = $_POST['name'];	
						$Description     = $_POST['Description'];
						$categoryType 	 = $_POST['categoryType'];
						$Visibility  	 = $_POST['Visibility'];
						$comment  		 = $_POST['comment'];	

						
						//Validate Form
								
						if (empty($nameCategory)) {
									
								echo "<div class='alert alert-danger'>Category Can't be <strong>empty</strong></div>";			
						}elseif (strlen($nameCategory) < 4) {
								
								$TheMsg = "<div class='alert alert-danger'> Category Can't be less than <strong> 4 charachter's</strong></div>";	
								redirectHome($TheMsg,'back');

						}elseif (strlen($nameCategory) > 20) {
								
								echo "<div class='alert alert-danger'>Category Can't be more than <strong>20 charachter's</strong></div>";	
							}else{		
								
								//check if Category Exist to Prevent Duplicate
								
								$check = checkItem("Name","categories",$nameCategory);

								if ($check == 1) {

											$TheMsg = "<div class='alert alert-danger'>sorry <b> $nameCategory  </b> already exist</div>";
											//function
											redirectHome($TheMsg,'back');
										}else{

											//Insert New Category Info in Database
								
											$stmt = $con->prepare("INSERT INTO 
																	categories(Name,Description,parent,Visibility,Allow_Comment)
																	VALUES(:zname, :zdesc,:zparent, :zvisible,:zcomment)
																	");
											$stmt->execute(array(
																	'zname'  	=> $nameCategory,
																	'zdesc' 	=> $Description,
																	'zparent'	=> $categoryType,
																	'zvisible'  => $Visibility,
																	'zcomment'  => $comment

																));

											//echo Success Message
											
											$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 
											//function
											redirectHome($TheMsg,'back');
										}
								}	
							}else{
									//Function redirectHome()
									echo "<div class='container'  style='min-height:500px;'";
									
									$TheMsg = '<div class="alert alert-danger">Sorry you Can\'t Browse this page Directly</div>';
									//function
									redirectHome($TheMsg,'back');
									
									echo "</div>";
							}
								echo "</div>";
/*================
 Edit  Category
================*/
		}elseif ($link == 'Edit') {
								
				//check if GET request Category ID is numeric and get the Interger Value
					
				$category_id = isset($_GET['cate_id'])  && is_numeric($_GET['cate_id'])? 
													intval($_GET['cate_id']) : 0;
					
				// select all data depend of this id 
					
				$stmt =  $con->prepare("SELECT * FROM categories WHERE category_id = ? LIMIT 1");

				//Execute Query 	
					
				$stmt->execute(array($category_id));

				//fetch The data
					
				$categroy = $stmt->fetch();

				//The category Count
					
				$count = $stmt->rowCount();		

				//If there's Such Id Show the form
												
				if ($count > 0) {      ?>
										
						<h1 class="text-center">Edit Category</h1>
							<div class="container">
								<form class="form-horizontal" action="?get=Update" method="POST">
									<!-- hidden id -->
									<input type="hidden" name="cate_id" value="<?php echo $categroy['category_id'];?>">
								<!-- Name Field -->			
										<div class="form-group form-group-lg">
											<label class="col-sm-2 control-label">Name</label>
											<div class="col-sm-10 col-md-6">
												<input type="text" name="name" class="form-control"  required="required" placeholder="Name of the Category" value="<?php echo $categroy['Name'];?>">
											</div>	
										</div>

										<!-- Description -->			
										<div class="form-group form-group-lg">
											<label class="col-sm-2 control-label">Description</label>
											<div class="col-sm-10 col-md-6">
												<textarea name="Description"  rows="5" class="form-control" placeholder="Describe the Category" ><?php echo $categroy['Description'];?></textarea>
											</div>	
										</div>
												
										<!-- Category Type -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Category Type</label>
									<div class="col-sm-10 col-md-6">
										<select name="categoryType">
											<option value="0">Independent</option>
				<?php
					//get all category 
					$allCats = getAllFrom("*","categories","WHERE parent = 0 AND category_id != {$category_id}","category_id");
					foreach ($allCats as $cat) { ?>
					 	<option value='<?php echo $cat['category_id']; ?>'
					 	<?php echo ($cat['category_id'] == $categroy['parent']? 'SELECTED':'' ); ?> >
					 	<?php echo $cat['Name']; ?></option>
					 <?php } ?>
										</select>	
									</div>	
								</div>
										<!-- Visibility -->			
										<div class="form-group form-group-lg">
											<label class="col-sm-2 control-label">Visible</label>
											<div class="col-sm-10 col-md-6">
													<div>
														<input id="vis_yes" type="radio" name="Visibility" value="0" <?php echo ($categroy['Visibility'] == 0? 'checked': '');?> >
														<label for="vis_yes">YES</label>
													</div>
													<div>
														<input id="vis_No" type="radio" name="Visibility" value="1" <?php echo ($categroy['Visibility'] == 1? 'checked': '');?>>
														<label for="vis_No">NO</label>
													</div>
											</div>	
										</div>
											<!-- Allow_Comment -->			
										<div class="form-group form-group-lg">
											<label class="col-sm-2 control-label">Allow Comment</label>
											<div class="col-sm-10 col-md-6">
													<div>
														<input id="com_yes" type="radio" name="comment" value="0" <?php echo ($categroy['Allow_Comment'] == 0? 'checked': '');?>>
														<label for="com_yes">YES</label>
													</div>
													<div>
														<input id="com_No" type="radio" name="comment" value="1" <?php echo ($categroy['Allow_Comment'] == 1? 'checked': '');?>>
														<label for="com_No">NO</label>
													</div>
											</div>	
										</div>

										<!-- Submit -->			
										<div class="form-group form-group-lg">
											<div class="col-sm-offset-2 col-sm-10">
												<input type="submit" value="Update" class="btn btn-primary btn-lg">
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
================
Update  Category
================		
*/
		}elseif($link == 'Update'){  

				if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

						<h1 class="text-center">Update Category</h1>
						<div class="container"  style='min-height:500px;'>
			<?php						
							
						//Get variables From The Form Edit
						$category_id     = $_POST['cate_id']; 	
						$nameCategory	 = $_POST['name'];	
						$Description     = $_POST['Description'];
						$Visibility  	 = $_POST['Visibility'];
						$comment  		 = $_POST['comment'];	
						$categoryType  	 = $_POST['categoryType'];	
						
						//Validate Form
								
						if (empty($nameCategory)) {
									
								$TheMsg = "<div class='alert alert-danger'>Category Can't be <strong>empty</strong></div>";	
									redirectHome($TheMsg,'back');		
						}elseif (strlen($nameCategory) < 4) {
								
								$TheMsg = "<div class='alert alert-danger'> Category Can't be less than <strong> 4 charachter's</strong></div>";	
								redirectHome($TheMsg,'back');

						}elseif (strlen($nameCategory) > 20) {
								
								$TheMsg = "<div class='alert alert-danger'>Category Can't be more than <strong>20 charachter's</strong></div>";
								redirectHome($TheMsg,'back');	
							}else{		

									//Update Database with these Info
							
									$stmt = $con->prepare("UPDATE categories 
																		SET
																		 Name = ?,
																		 Description    = ?,
																		 Visibility = ?,
																		 Allow_Comment = ?,
																		 parent    = ?
																		WHERE
																		 category_id   = ?	 	
																		 ");
									$stmt->execute(array($nameCategory,$Description,$Visibility, $comment,
										$categoryType,$category_id)); 

									//echo Success Message
									
									$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

									redirectHome($TheMsg,'back');


						
							}

					}else{
						echo "<div class='container'>";
							
							$TheMsg = '<div class="alert alert-danger">You can\'t browse this Page Directly</div>';
							
							redirectHome($TheMsg);
							
						echo "</div>";
					}
					echo "</div>";

/*================
Delete  Category
================*/
		}elseif ($link == 'Delete') { ?>

					<h1 class="text-center">Delete Category</h1>
					<div class="container" style="min-height:500px;">
		<?php					
					//check if GET request category is numeric and get the Interger Value
					
				$caregory_id = isset($_GET['cate_id'])  && is_numeric($_GET['cate_id'])? 
													intval($_GET['cate_id']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("category_id","categories",$caregory_id);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :userid
						$stmt = $con->prepare("DELETE FROM categories WHERE category_id = :cat_id");

					//binding the userid		

						$stmt->bindParam(':cat_id',$caregory_id);

						$stmt->execute();
					
					//echo Success Message
						echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Delete </div>';
						
							redirectHome($TheMsg,'back');
						
						echo "</div>";
					
				}else{
					echo "<div class='container' style='min-height=700px;'>";
						
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