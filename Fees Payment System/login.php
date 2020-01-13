  <?php
  $severname="localhost";
  $username="root";
  $password="";
  $dbname="FeesPay";
  $tbl_name="login";
  
  $name='';
  $loginpassword='';
  $showErrorDivClasses = 'hide';
  $showErrorDivMsg='';
  if(isset($_POST['loginSubmit']))
  {
	  $name=$_POST['userName'];
	  $loginpassword=$_POST['password'];
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query="call checklogin('$name','$loginpassword')";
	  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
	  $count=mysqli_num_rows($result);
	  if($count==false)
		{
		  $showResultsDivClasses='hide';
		  $showErrorDivClasses='show';
		  $showErrorDivMsg='Invalid Username or Password!';
		}
	  else
		{
			while($row=mysqli_fetch_array($result))
			{
				session_start();
				$name = $row['name'];
				$role = $row['role'];
				$username = $row['username'];
				$_SESSION['username'] = $username;
				$_SESSION['name'] = $name;
				$_SESSION['role'] = $role;
				header('Location: student.php'); 
			}
		}
  }
  ?>
  
  <style>
  .hide {
	  display:none;
  }
  .show {
	  display:block;
  }
  .visible {
	  visibility:visible;
  }
  .invisible {
	  visibility:hidden;
  }
  </style>
  <head>
    <title>Fees Pay</title>
  </head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <div class="container">
	  <div class="row col-sm-3">
	  </div>
	  <div class="row col-sm-6 row well" style="margin-top: 25vh;">
		  <img src="images/left.png" class="img-thumbnail" alt="Fees Pay" style="margin-bottom: 20px;">
		  <form method="post" action="login.php">
			  <div class="col-sm-2"></div>
			  <div class="col-sm-8">
			  <div>
			   <h5 class="<?php echo $showErrorDivClasses?>" style="margin-bottom: 5px; color: red;"><?php echo $showErrorDivMsg?></h5>
			   <input style="margin-top: 10px;" name="userName" id="userName" type="text" class="form-control" placeholder="Username">
			   <input style="margin-top: 10px;" name="password" id="password" type="text" class="form-control" placeholder="Password">
			  </div>
			   <button style="margin-top: 10px;" name="loginSubmit" type="submit" class="btn btn-primary col-sm-6">Login</button>	
			  </div>
			 
		   </form>
	  </div>
     <!-- JS Libraries -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>