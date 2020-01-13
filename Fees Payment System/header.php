<?php
session_start();
$username='';
$name='';
$role='';
$username=$_SESSION['username'];
$name=$_SESSION['name'];
$role=$_SESSION['role'];
if(!$name) {
	session_destroy();
    header('location:login.php');
}

if(isset($_POST['logout'])) {
    session_destroy();
	unset($_SESSION['username']);
    unset($_SESSION['name']);
	unset($_SESSION['role']);
    header('location:login.php');
}
?>
  <head>
    <meta charset="utf-8">

    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Use title if it's in the page YAML frontmatter -->
    <title>Fees Pay</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/dashboard/images/favicon.png" rel="icon" type="image/png" />

  </head>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">FeesPay</a>
    </div>
    <ul class="nav navbar-nav">
      <li id="op1"><a href="student.php">Students Info</a></li>
      <li id="op2"><a href="payment.php">Register Payment</a></li>
	  <li><a href="#">Reports</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
	  <form action="" method="post">
		 <button style="margin-top: 5px; margin-right: 5px;" name="logout"  type="sumbit" class="btn btn-warning">Logout <?php echo ' '.$name.' ('.$role.')'?></button>	
	  </form>
     
    </ul>
  </div>
</nav>
<script>
if(window.location.href.includes('payment')) {
	document.getElementById("op2").classList.add('active');
} else if (window.location.href.includes('student')) {
	document.getElementById("op1").classList.add('active');
}
</script>
   <!-- JS Libraries -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
 