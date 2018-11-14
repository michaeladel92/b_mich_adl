<?php
/*
	=======================================================
	== Manage  Comments page
	== You can Edit | Delete | Approve Comments  From here
	=======================================================
 */
ob_start(); // output Buffering Start
session_start();

$pageTitle = 'Comments';
if (isset($_SESSION['Username'])) {		
		
		include ("init.php");  

		$link = isset($_GET['get']) ? $_GET['get'] : 'comment';

/*
====================
Manage Comments Page
====================		
*/
		if ($link == 'comment') { 
			
					//Select all Comments 

					$stmt = $con->prepare("SELECT comments.*,
												  items.itemName AS item_name,
												  users.Username AS user_name
												  FROM 
												  		comments
												  INNER JOIN 
												  		items
												  	ON		
												  		items.itemID = comments.item_id
												  INNER JOIN
												  		users
												  	ON
												  		users.UserID = comments.user_id 
												  		ORDER BY 			 
												  		com_ID DESC
										 ");

					//Execute statment

					$stmt->execute();

					//Assign to Variable
					
					$rows = $stmt->fetchAll();

					if (!empty($rows)) {
		?>		
					
				<h1 class="text-center">Manage Comments </h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table  table table-bordered  " >
								<tr>
									<th>#ID</th>
									<th>Comments</th>
									<th>Item Name</th>
									<th>User Name</th>
									<th>Added date</th>
									<th>Control</th>
								</tr>
						<?php 
								$num = 1;
								foreach ($rows as $row) { ?>
								<tr>
									<td><?php echo $row['com_ID'];?></td>
									<td style="overflow: hidden; word-wrap: break-word; min-width: 400px; max-width: 400px;">
										<?php echo $row['comment'];?></td>
									<td><?php echo $row['item_name'];?></td>
									<td><?php echo $row['user_name'];?></td>
									<td><?php echo $row['Com_date'];?></td>
									<td>
										<a href="comments.php?get=Edit&comid=<?php echo $row['com_ID'];?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
										<a href="comments.php?get=Delete&comid=<?php echo $row['com_ID'];?>" class="btn btn-danger  confirm"><i class="fa fa-trash"></i> Delete</a>
							<?php if ($row['status'] == 0) { ?>
										<a href="comments.php?get=Approve&comid=<?php echo $row['com_ID'];?>" class="btn btn-info">
											<i class="fas fa-check-circle"></i> Approve </a>
							<?php } ?>

									</td>
								</tr>
						<?php $num++;} ?>
						</table>
					</div>
				</div>

		<?php		
			}else{ ?>
					<div class="container" style="min-height: 400px;">
					<h2 class="alert alert-info text-center">There's No Record Found Yet</h2>
				</div>
		<?php		}
/*
================
Edit Comment Page
================		
 */
		}elseif ($link == 'Edit') {

					//check if GET request userid is numeric and get the Interger Value
					
				$comid = isset($_GET['comid'])  && is_numeric($_GET['comid'])? 
													intval($_GET['comid']) : 0;
					
					// select all data depend of this id 
					
				$stmt =  $con->prepare("SELECT * FROM comments WHERE com_ID = ? LIMIT 1");

					//Execute Query 	
					
				$stmt->execute(array($comid));

					//fetch The data
					
				$row = $stmt->fetch();

					//The row Count
					
				$count = $stmt->rowCount();		

					//If there's Such Id Show the form
												
				if ($count > 0) {      ?>
				
					<h1 class="text-center">Edit Comment</h1>
					<div class="container" style="min-height: 300px;">
						<form class="form-horizontal" action="?get=Update" method="POST">
							<input type="hidden" name="comid" value="<?php echo $comid; ?>">
								<!-- Comments -->			
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Comment</label>
									<div class="col-sm-10 col-md-6">
										<textarea name="comment"  rows="5" class="form-control" placeholder="Write a Comment" required="required"><?php echo $row['comment'];?></textarea>
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
Update   Comments 
===================		
 */

		}elseif($link == 'Update'){  

								if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

					<h1 class="text-center">Update Comment</h1>
					<div class="container" style="min-height: 500px;">
		<?php
						//Get variables From The Form
							
						$comid   	= $_POST['comid'];	
						$comment 	= $_POST['comment'];	
						
								
								//Update Database with these Info
						
								$stmt = $con->prepare("UPDATE comments 
																	SET
																	 comment = ?
																	WHERE
																	 com_ID   = ?	 	
																	 ");
								$stmt->execute(array($comment,$comid)); 

								//echo Success Message
								
								$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Update </div>'; 

								redirectHome($TheMsg,'back');


						
					

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

					<h1 class="text-center">Delete Comment</h1>
					<div class="container" style="min-height: 500px;">
		<?php					
					//check if GET request userid is numeric and get the Interger Value
					
				$comid = isset($_GET['comid'])  && is_numeric($_GET['comid'])? 
													intval($_GET['comid']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("com_ID","comments",$comid);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :userid
						$stmt = $con->prepare("DELETE FROM comments WHERE com_ID = :comid");

					//binding the userid		

						$stmt->bindParam(':comid',$comid);

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
Approve Comments
================		
 */
		}elseif ($link = 'Approve') { ?>

					<h1 class="text-center">Approve Comment</h1>
					<div class="container">
		<?php					
					//check if GET request userid is numeric and get the Interger Value
					
				$comid = isset($_GET['comid'])  && is_numeric($_GET['comid'])? 
													intval($_GET['comid']) : 0;
					
					// select UserID in data depend of this id 
					
				$check = checkItem("com_ID","comments",$comid);

					//If there's Such Id means there is an record match
												
				if ($check > 0) {     
														//u can use ? or :userid
						$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE com_ID = :comid");

					//binding the userid		

						$stmt->bindParam(':comid',$comid);

						$stmt->execute();
					
					//echo Success Message
						echo "<div class='container'>";
						
							$TheMsg = '<div class="alert alert-success">'.$stmt->rowCount() . 'Record Approved </div>';
						
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