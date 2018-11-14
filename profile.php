<?php 
ob_start();
session_start();
//title PAGE
$pageTitle = 'Profile';
//initialize
include ("init.php");  

if (isset($_SESSION['user'])) {

	$getUsers = $con->prepare("SELECT * FROM users WHERE Username = ?");
	//sessionUser Variable found in init.php
	$getUsers->execute(array($sessionUser));

	$info = $getUsers->fetch(); //fetch because each column has single value, unlike fetchAll that will get all row
	$userID = $info['UserID'];
?>
	

	<h1 class="text-center">Welcome </h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-warning">
			<div class="panel-heading"><i class="fas fa-id-card fa-lg"></i> My Information</div>
			<div class="panel-body">
				<div class="col-xs-4 col-sm-3">
					<img class="img-responsive img-thumbnail center-block" src="admins/uploads/avatars/<?php echo ($info['avatar'] !=''?$info['avatar'] : 'default.jpg');?>">
				</div>
				<div class="col-xs-8 col-sm-9">
				<ul class="list-unstyled">
			<!-- username -->
					<li class="profile_list">
						<i class="fas fa-unlock-alt"></i>
						<span>Username:</span>
						 <?php echo $info['Username'];?>
					</li>
			<!-- Email  -->
					<li class="profile_list">
						<i class="fas fa-envelope"></i>
						<span>Email:</span>
						<?php echo $info['Email'];?>
					</li>
			<!-- full Name  -->
					<li class="profile_list">
						<i class="fas fa-user-alt"></i>
						<span>FullName:</span>
						<?php echo (!empty($info['FullName'])? $info['FullName']:'' );?>
					</li>
			<!-- RegStatus  -->
					<li class="profile_list">
						<i class="fas fa-registered"></i>
						<span>RegStatus:</span>
						 <?php echo ($info['RegStatus'] == 0?'Pending <i style="color:red;" class="fas fa-exclamation-triangle"></i>': 'Confirmed <i style="color:#00fd04;" class="fas fa-check-square"></i>');?>
					</li>
			<!-- Register Date  -->
					<li class="profile_list">
						<i class="fas fa-calendar-alt"></i>
						<span>Register Date:</span> 
						<?php echo $info['RegDate'];?>
					</li>
				</ul>	
				<a style="margin-top: 10px;" href = "editProfile.php?editUser=<?php echo $_SESSION['user_id'];?>"class="btn btn-default edit_button">Edit Info</a>

				</div>
				

			</div>
		</div>
	</div>
</div>

<div id="my-advertise" class="my-advertise block">
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">My Items</div>
			<div class="panel-body">
								
								<?php
					//get Items 
					//$items = getItems('Member_ID',$info['UserID'],'Add_All');
					$items = getAllFrom("*","items","WHERE Member_ID = $userID","itemID");

					
					// Items Not Empty
					if (!empty($items)) { ?>
						
						<div class="row">
					<?php foreach ($items as $item) { ?>
								<div class="col-sm-4 col-md-3">
									<div class="thumbnail item-box">
										<?php echo ($item['Approve'] == 0? '<span class="approve-status">Waiting Approval</span>':'' );?>
										<span class="price-tag">LE <?php echo $item['Price'];?></span>
										<img class="img-responsive" style=" max-width: 218px; max-height: 147px;" src="admins/uploads/item_photo/<?php echo ($item['itmPhoto'] == ''?'default.png':$item['itmPhoto']);?>">
										<div class="caption">
											<h3><a href="items.php?itemID=<?php echo $item['itemID'];?>"><?php echo $item['itemName']; ?></a></h3>
											<p class="date" style="margin-bottom: 0;"><?php echo $item['item_date']; ?></p>
											<a class="btn btn-default btn-sm" 
											   href="editItem.php?itemID=<?php echo $item['itemID'] . "&UserID=" . $userID;  ?>">
												Edit
											</a>	
										</div>
									</div>
								</div>				 
					
					<?php  } ?>	

						</div>				
		<?php

						}else{
							echo '<h3 class="text-center alert alert-info">There is No Items to Show</h3>';
							echo "<a class='btn btn-info' href='newAds.php'>New Ad</a>";
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
ob_end_flush();