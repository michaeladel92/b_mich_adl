<nav class="navbar navbar-inverse">
    <img alt="michael Adel logo" src="layout/images/logo3.png" >
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN');?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?php echo lang('CATEGORIES');?></a></li>
        <li><a href="items.php"><?php echo lang('ITEMS');?></a></li>
        <li><a href="members.php"><?php echo lang('MEMBERS');?></a></li>
        <li><a href="comments.php"><?php echo lang('COMMENTS');?></a></li>


  
      </ul>
   		
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Michael <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php">Visit Shop</a></li>
            <li><a href="logout.php"><?php echo lang('LOGOUT');?></a></li>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>