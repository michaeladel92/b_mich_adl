<?php
session_start();
//title PAGE
$pageTitle = "Category";
//initialize
include ("init.php");  
?>

<div class="container">
		<?php
			//check if tag is set
			
			$tagName = (isset($_GET['name'])? $_GET['name'] : 0 );
			
			//CatName FROM URL
			

				//check if id & name Exist in Database	
					$checkID = $con->prepare("SELECT tags 
														FROM 
															items 
														WHERE
															 tags 
														LIKE
															'%$tagName%'
														AND 
														Approve = 1	
											");

					$checkID->execute();
					//count rows
					$count = $checkID->rowCount();
					
					//if exist then execute
					if ($count > 0) {?>
					<h1 class="text-center">tag that has <?php echo $tagName;?></h1>
		<?php
					//get Items 
					$tagItems = getAllFrom("*","items","WHERE tags LIKE '%$tagName%' AND Approve = 1","itemID");
					//$items = getAllFrom("*","items","WHERE Approve = 1 AND CAT_ID = {$_GET['pageID']} ","itemID");

					
					// Items Not Empty
					if (!empty($tagItems)) { ?>
						
						<div class="row">
					<?php foreach ($tagItems as $item) { ?>
								<div class="col-sm-4 col-md-3">
									<div class="thumbnail item-box">
										<span class="price-tag"><?php echo $item['Price'];?></span>
										<img class="img-responsive" src="admins/uploads/item_photo/<?php echo $item['itmPhoto']?>">
										<div class="caption">
											<h4><a href="items.php?itemID=<?php echo $item['itemID']; ?>"><?php echo $item['itemName']; ?></a></h4>
											<p class="date"><?php echo $item['item_date']; ?></p>
										</div>
									</div>
								</div>				 
					
					<?php  } ?>	

						</div>				
		<?php

						}else{
							echo '<h3 class="text-center alert alert-info">There is No Items Added Yet</h3>';
						}
										

					}else{ // if id & name not found in database
							echo "<div class='container text-center'>";
							echo "<div class='row'>";	
							$TheMsg = '<h2 class="alert alert-danger">Page Not Found</h2>';
						
							redirectHome($TheMsg);
							echo "</div>";
							echo "</div>";
					}
		

		?>

</div>




<?php
include ($tpl . "footer.php");
