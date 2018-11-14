<?php 
ob_start();
session_start();
//title PAGE
$pageTitle = 'Home Page';
//initialize
include ("init.php");  
?>


<div class="container" style="min-height: 400px;">
		<div class="row">
			<!-- search -->
			<div class="container" >
				<form role="form" id="form-buscar" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
					<div class="form-group ">
					<div class="input-group col-xs-6 col-md-4">
							<input id="1" class="form-control" type="text" name="search" placeholder="Search..." required/>
							<span class="input-group-btn">
							<button class="btn btn-success" type="submit">
							<i class="glyphicon glyphicon-search" aria-hidden="true"></i> Search
					</button>
					</span>
					</div>
					</div>
				</form>	
			</div>
			


		<?php
			// search 
		$search = (isset($_POST['search'])? $_POST['search'] : '' );

		if (!empty($search)) {
			
			$query = "AND itemName LIKE '%$search%'";	
		
		}else{
			$query = '';
		}

		$items =getAllFrom("*","items","WHERE Approve = 1 $query ","itemID");
					//get Items 
					//$items =getAllFrom("*","items","WHERE Approve = 1","itemID");


			if ( !empty($items)) {
							
							foreach ($items as $item) { ?>
							
								<div class="col-xs-6 col-sm-4 col-lg-3">
									<div class="thumbnail item-box">
										<span class="price-tag">LE <?php echo $item['Price'];?></span>
										<img class="img-responsive" style=" width: 218px;height: 147px; max-height: 147px;" src="admins/uploads/item_photo/<?php echo ($item['itmPhoto'] == ''?'default.png':$item['itmPhoto']);?> ">
										<div class="caption">
											<h3><a  href="items.php?itemID=<?php echo $item['itemID']; ?>"><?php echo $item['itemName']; ?></a></h3>
											<p class="date"><?php echo $item['item_date']; ?></p>
										</div>
									</div>
								</div>				 

					<?php  } 
			}else{
				
				echo '<h3 class="text-center alert alert-info">There are no items Found</h3>';

			}
				?>	

						</div>	
					</div>

<?php 
include ($tpl . "footer.php");
ob_end_flush();