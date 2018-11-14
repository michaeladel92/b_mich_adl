<?php
session_start();
//title PAGE
$pageTitle = "Category";
//initialize
include ("init.php");  
?>

<div class="container" style="min-height: 400px;">
		<?php
			//check if category ID is a Number
			
			$catID = (isset($_GET['pageID']) && is_numeric($_GET['pageID'])? 
				intval($_GET['pageID']) : 0 );
			
			//CatName FROM URL
			

				//check if id & name Exist in Database	
					$checkID = $con->prepare("SELECT category_id,Name 
														FROM 
															categories 
														WHERE
															 category_id = ?
												
											");

					$checkID->execute(array($catID));
					//count rows
					$count = $checkID->rowCount();
					
					//if exist then execute
					if ($count > 0) {?>
						
		<?php
					//get Items 
					$items = getAllFrom("*","items","WHERE Approve = 1 AND CAT_ID = $catID","itemID");
					//$items = getAllFrom("*","items","WHERE Approve = 1 AND CAT_ID = {$_GET['pageID']} ","itemID");

					
					// Items Not Empty
					if (!empty($items)) { ?>
						
						<div class="row" style="margin: 10px;">
					<?php foreach ($items as $item) { ?>
								<div class="col-sm-4 col-md-3">
									<div class="thumbnail item-box">
										<span class="price-tag">LE <?php echo $item['Price'];?></span>
										<img class="img-responsive" style=" width: 218px; max-height: 147px; min-height: 140px;" src="admins/uploads/item_photo/<?php echo ($item['itmPhoto'] == ''?'default.png':$item['itmPhoto']);?>">
										<div class="caption">
											<h3><a href="items.php?itemID=<?php echo $item['itemID']; ?>"><?php echo $item['itemName']; ?></a></h3>
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
