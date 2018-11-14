<?php 
ob_start(); //OutPut Buffering Start

session_start();
//if session already exist redirect it to dashbord
if (isset($_SESSION['Username'])) {

		$pageTitle = 'Dashboard';
		
		include ("init.php");  
/*
=========================
==Start Dashboard Page===
=========================			
*/

//lastest Members 
$numUsers = 5; //Number of latest users

$latestUsers = getLatest("*","users","UserID",$numUsers); // latest users Function

//Latest Items
$numItems = 5;

$latestItems = getLatest("*","items","itemID",$numItems); //Latest Items Function

$numComments = 5; //Number of Comments
?>
	<div class="container home-stats text-center">
		<h1>Dashboard</h1>
		<div class="row">
	<!-- Stats  -->
			<div class="col-md-3">
				<div class="stat st-members">
					<i class="fa fa-users"></i>
					<div class="info">
						Total Members
					<span><a href="members.php"><?php echo countItems("UserID","users" );?></a></span>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="stat st-pending">
					<i class="fa fa-user-plus"></i>
					<div class="info">
						Pending Members
					<span><a href="members.php?do=Manage&page=Pending">
						<?php
							echo checkItem("RegStatus", "users", 0 );
						?>
					</a></span>	
					</div>	
				</div>
			</div>

			<div class="col-md-3">
				<div class="stat st-items">
					<i class="fa fa-tag"></i>
					<div class="info">
						Total Items
					<span><a href="items.php"><?php echo countItems("itemID","items" );?></a></span>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="stat st-comments">
				<i class="fa fa-comments"></i>
				<div class="info">
					Total Comments
				<span><a href="comments.php"><?php echo countItems("com_ID","comments" );?></a></span>
				</div>
				</div>
			</div>

		</div>
	</div>


	<div class="container latest">
		<div class="row">
	<!--Latest users  -->
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-users"></i> Latest <?php echo $numUsers;?> Registerd Users
						<span class="toggle_info pull-right">
							<i class="fa fa-plus fa-lg"></i>
						</span>
					</div> 
					<div class="panel-body">
						<ul class="list-unstyled latest-users">	
						<?php
						if (!empty($latestUsers)) {
							foreach($latestUsers as $user){
										echo '<li>' . $user['Username'] . 
													'<span>
														<a class="btn btn-success btn-xs pull-right" href="members.php?do=Edit&userid='.$user['UserID'].'">
															<i class="fa fa-edit"></i> Edit
														</a>';
										if ($user['RegStatus'] == 0) { 
										echo '<a href="members.php?do=Activate&userid='.$user['UserID'].'" class="btn btn-info btn-xs pull-right"><i class="fas fa-check-circle"></i> Activate </a>';
										} 
										echo '</span>
										</li>';
							}
						}else{

							echo "There's No Record Found Yet";
						}		
						?>
						</ul>
					</div>
					</div>
				</div>
		
		<!--Latest Items  -->
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-tag"></i> Latest <?php echo $numItems;?> Items 
						<span class="toggle_info pull-right">
							<i class="fa fa-plus fa-lg"></i>
						</span>
					</div> 
					<div class="panel-body">
						<ul class="list-unstyled latest-users">	
						<?php
						if(!empty($latestItems)){
							foreach($latestItems as $item){
										echo '<li>' . $item['itemName'] . 
													'<span>
														<a class="btn btn-success btn-xs pull-right" href="items.php?do=Edit&itemID='.$item['itemID'].'">
															<i class="fa fa-edit"></i> Edit
														</a>';
										if ($item['Approve'] == 0) { 
										echo '<a href="items.php?do=Approve&itemID='.$item['itemID'].'" class="btn btn-info btn-xs pull-right"><i class="fas fa-check-circle"></i> Approve </a>';
										} 
										echo '</span>
										</li>';
							}
						}else{
							echo "There's No Record Found Yet";
						}		
						?>
						</ul>
					</div>
				</div>
			</div>

	<!-- latest Comments -->
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fas fa-comment"></i> Latest <?php echo $numComments; ?>  Comments
						<span class="toggle_info pull-right">
							<i class="fa fa-plus fa-lg"></i>
						</span>
					</div> 
					<div class="panel-body">
						<ul class="list-unstyled latest-users">	
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
															  		ORDER BY
															  		com_ID DESC
															  	LIMIT
															  	$numComments	 		 
													 ");

								//Execute statment
								$stmt->execute();
								//Assign to Variable
								$rows = $stmt->fetchAll();
							if (!empty($rows)) {
									
								foreach ($rows as $comment) { ?>
											
											<div class="comment-box">
												<span class="member_name">
													<?php echo $comment['user_name'];?>
												</span>
												<p class="member_com">
													<?php echo $comment['comment'];?>
												</p>
											</div>



								<?php }
							}else{
								echo "There's No Record Found Yet";
							}		
				?>
						</ul>
					</div>
					</div>
				</div>	

		</div>
	</div>
</div>



<?php
/*
=========================
==End  Dashboard  Page===
=========================			
*/
		include ($tpl . "footer.php");
}else{
		//if No Session found
	header("location:index.php"); //redirect to login From
	exit();
}

ob_end_flush(); //Relase the OutPut