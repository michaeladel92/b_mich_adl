<?php 
ob_start();
session_start();
//title PAGE
$pageTitle = 'Show Items';
//initialize
include ("init.php");  

				//check if GET request itemID is numeric and get the Interger Value
			
				$itemID = isset($_GET['itemID'])  && is_numeric($_GET['itemID'])? 
													intval($_GET['itemID']) : 0;
					
					// select all data depend of this id 
					
				$stmt =  $con->prepare("SELECT 
												items.*,
												categories.Name AS cat_name,
												users.Username AS user_name 
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
												 WHERE itemID = ?
												 AND   Approve = 1 
													
												 ");

					//Execute Query 	
					
				$stmt->execute(array($itemID));

					//Count Row
				$count = $stmt->rowCount();

					//check if exist 
					if ($count > 0) {

						//fetch The data
						
					   $item = $stmt->fetch();
?>
	

				<h1 class="text-center"><?php echo $item['itemName'];?> </h1>

				<div class="container" style="min-height: 500px;">
					<div class="row">
						<div class="col-md-3">
								<img class="img-responsive img-thumbnail center-block" src="admins/uploads/item_photo/<?php echo ($item['itmPhoto'] !=''?$item['itmPhoto'] : 'default.png');?>">
						</div>
						<div class="col-md-9 item-info">
							<h2><?php echo $item['itemName'];?></h2>
							<p><?php echo $item['descItem'];?></p>
						
						<ul class="list-unstyled">
							<li class="item_list"><span>Added Date:</span><?php echo $item['item_date'];?></li>
							<li class="item_list"><span>Price:</span> LE <?php echo $item['Price'];?></li>
							<li class="item_list"><span>Category:</span> <a href="categories.php?pageID=<?php echo $item['Cat_ID'];?>"><?php echo $item['cat_name'];?></a></li>
							<li class="item_list"><span>Added By:</span> <?php echo $item['user_name'];?></li>
							<li class="item_list tags-items"><span>Tags:</span> 
								<?php 
										$allTags = explode(",",$item['tags']);
										foreach ($allTags as $tag) {
											$tag = str_replace(' ','', $tag);
											$lowertag = strtolower($tag);
											if (!empty($tag)) {
											echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
											}
										}
								?>
						   </li>

						</ul>

						</div>
					</div>
			
					<hr class="custom_hr">
					
			<!-- Add Comment section -->
					<?php if (isset($_SESSION['user'])) {?>
							<div class="row">
								<div class="col-md-offset-3">
							    	<div class="add-comment">
									    	<h3>Add Your Comment</h3>
									    	<form 
									    	    action="<?php echo $_SERVER['PHP_SELF'] .'?itemID=' . $item['itemID'];?>" method="POST">
									    		<textarea class="form-control" name="comment" required></textarea>
									    		<input class="btn btn-primary" type="submit" value="Add Comment">
									    	</form>
		    	<?php
			    		if ($_SERVER['REQUEST_METHOD'] =='POST') {
			    				
			    				$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING); 
			    				$itemID  = $item['itemID'];
			    				$userID  = $_SESSION['user_id'];

			    				if (empty($comment)) {
			    					echo "<div class='alert alert-info'>You need to insert Comment first</div>";

			    				}elseif (strlen($comment) < 5) {
			    					echo "<div class='alert alert-info'>Comment Must be more than 5 Char</div>";
			    					# code...
			    				}else{
			    						
							    					$stmt = $con->prepare("INSERT INTO comments
														(comment,status,Com_date,item_id,user_id)
													VALUES
														(:zcomment, 0, now(),:zitem,:zuser)");
			    				
							    					$stmt->execute(array(
																	'zcomment' => $comment,
																	'zitem'    => $itemID,
																	'zuser'    => $userID
																));	
			    					if ($stmt) {
			    						echo "<div class='alert alert-success col-md-6'>Pending Comment, wait for the Moderator to Review</div>";
			    					}

			    				}
			    		}
		    	?>
							    	</div>
							    </div>
							</div>
						<?php }else{
							echo "<a href='login.php'>login</a> to add Comment";
						} ?>

                    <hr class="custom_hr">
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
												  	AND 
												  	status = 1
												  		ORDER BY 			 
												  		com_ID DESC
										 ");

					//Execute statment

					$stmt->execute(array($item['itemID']));

					//Assign to Variable
					
					$rows = $stmt->fetchAll();

					if (!empty($rows)) {
							foreach ($rows as $comment) { ?>
							<div class="comment-box">
								<div class='row'>
									<div class='col-sm-2 text-center'>
									<img class="img-responsive img-thumbnail img-circle center-block" src="default.png">
											 <?php echo $comment['user_name'];?> 
									</div>	
									<div class='col-sm-10'>	
											<p class="lead"><?php echo $comment['comment'];?></p>
												 <!-- $comment['Com_date'] <br> -->
									</div>
							 <!-- $comment['user_id']  -->
								</div>
							</div>
							<hr class="custom_hr">
						<?php }
					}else{
							echo "<div style='text-align:center' class='col-md-offset-3'>";
							echo "<h4 class='alert alert-info col-md-5'>There is no Comments Added Yet</h4>";
							echo "</div>";	
					}

		?>
					</div>
				</div>




 <?php
	
	}else{
		echo "<div class='container' style='min-height:300px; text-align:center; margin-top:100px;' >";
		echo "<h4 class='alert alert-info col-md-6 col-md-offset-3'>This Item Is Waiting Approval from Moderator</h4>";
		echo "</div>";

	}

include ($tpl . "footer.php");
ob_end_flush();