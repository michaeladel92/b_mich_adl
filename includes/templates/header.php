<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php getTitle(); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>all.css"> <!--fontawsome-->
	<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>jquery-ui.css"> <!--jquery-ui-->
	<!--jquery.selectBoxIt-->
	<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>jquery.selectBoxIt.css"> 
	<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>style.css">

</head>
<body>
<!-- upper bar -->
<div class="upper-bar ">
  <div class="container text-right">
    <?php
        if (isset($_SESSION['user'])) {

          $id = $_SESSION['user_id'];
            
            $getPhoto = $con->prepare("SELECT avatar FROM users WHERE UserID = ?");

            $getPhoto->execute(array($id));

            $photo = $getPhoto->fetch();
            // echo "<pre>";
            // print_r($photo['avatar']);die;
            //  echo "</pre>"; 
          ?>
                
               <img class="img-circle" 
               src="admins/uploads/avatars/<?php echo ($photo['avatar'] == ''?'default.jpg':$photo['avatar']);?>">
              <div class="btn-group my-info">
                <span class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                  <?php echo $sessionUser;?> <span class="caret"></span>
                </span>
                      <ul class="dropdown-menu">
                          <li><a href="profile.php">MyProfile</a></li>
                          <li><a href="newAds.php">New Item</a></li>
                          <li><a href="profile.php#my-advertise">My items</a></li> <!--# its an id in profile -->
                          <li><a href="logout.php">logout</a></li>

                      </ul>

               </div> 
          <?php

               //function check userStatus
               // $status = checkUserStatus($sessionUser);
               //  if ($status == 1) {
               //      //user not Active
               //  }
        }else{ ?>
      <a href="login.php">
        <span class="pull-right">Login/Signup</span>
      </a>
    <?php } ?>
</div>
</div>
	<!--Navbar -->
<nav class="navbar navbar-inverse">
  <img alt="michael Adel logo" src="admins/layout/images/logo3.png" >
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">ALL Categories</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
    <?php
          $categories = getAllFrom("*","categories","WHERE parent = 0","category_id");


          foreach ($categories as $cat) { ?>
                <li><a href="categories.php?pageID=<?php echo $cat['category_id']; ?>" > 
                        <?php echo $cat['Name']; ?></a>
                </li>
    <?php 

         }



    ?>
      </ul>
   		
    
    </div>
  </div>
</nav>
