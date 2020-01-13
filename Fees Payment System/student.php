<?php include "header.php"; ?>
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
  <div class="container">
	  <div class="row">
		  <h1>Students Info</h1>
		  <form method="post" action="student.php">
			  <div class="row">
			  <div class="col-sm-4">
			   <input name="searchUSN" id="searchUSN" type="text" class="form-control" placeholder="Search for USN">
			  </div>
			   <button name="searchSubmit" type="submit" class="btn btn-primary col-sm-1">Search</button>
			   <div class="col-sm-4"></div>
			   <button name="addInfo"  type="sumbit" class="btn btn-success col-sm-2">Add Student Record</button>	
			  </div>
		   </form>
	  </div>
  
  <?php
  $severname="localhost";
  $username="root";
  $password="";
  $dbname="FeesPay";
  $tbl_name="student";
  $usn='';
  $name='';
  $age=0;
  $sem=0;
  $gender='m';
  $address='';
  $readonly='';
  $showResultsDivClasses = 'hide';
  $showErrorDivClasses = 'hide';
  $headingValue='';
  $showErrorDivMsg='';
  if(isset($_POST['searchSubmit']))
  {
	  $searchusn=$_POST['searchUSN'];
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query="SELECT *FROM $tbl_name where usn='$searchusn'";
	  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
	  $count=mysqli_num_rows($result);
	  if($count==false)
		{
		  $showResultsDivClasses='hide';
		  $showErrorDivClasses='show well';
		  $showErrorDivMsg='USN Not Found!';
		}
	  else
		{
			while($row=mysqli_fetch_array($result))
			{
				$usn=$row['usn'];
				$name=$row['name'];
				$age=$row['age'];
				$sem=$row['sem'];
				$address=$row['address'];
				$gender=$row['gender'];
				$readonly='readonly';
				$showErrorDivClasses='hide';
				$showResultsDivClasses='show well';
				$headingValue='Update';
				$buttonValue='Update';
				}
		}
  }
    if(isset($_POST['addInfo']))
  {
	  $usn='';
	  $name='';
	  $age=0;
	  $gender='m';
	  $address='';
	  $headingValue='Enter';
	  $showErrorDivClasses='hide';
	  $showResultsDivClasses='show well';
	  $buttonValue='Save';
  }
  if(isset($_POST['resultSave']))
  {
	  $usn=$_POST['usn'];
	  $name=$_POST['name'];
	  $gender=$_POST['genderRadio'];
	  $age=$_POST['age'];
	  $sem=$_POST['sem'];
	  $address=$_POST['address'];
	  
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	    $query="SELECT *FROM $tbl_name where usn='$usn'";
		  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
		  $count=mysqli_num_rows($result);
		  if($count==false)
			{
				$query="INSERT INTO student VALUES('$name','$usn','$age',$sem,'$gender','$address')";
				mysqli_query($conn,$query) or die(mysqli_error($conn));
				$showErrorDivClasses='show well';
				$showErrorDivMsg='Record Added!';
			}
		  else
			{
				 $showErrorDivClasses='show well';
				 $showErrorDivMsg='USN Already exists';
			}
  }
  if(isset($_POST['resultUpdate']))
  {
	  $usn=$_POST['usn'];
	  $name=$_POST['name'];
	  $gender=$_POST['genderRadio'];
	  $age=$_POST['age'];
	  $sem=$_POST['sem'];
	  $address=$_POST['address'];
	  
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query="update student set name='$name', age=$age, sem=$sem, gender='$gender', address='$address' where usn='$usn'";
	  mysqli_query($conn,$query) or die(mysqli_error($conn));
	  $showErrorDivClasses='show well';
	  $showErrorDivMsg='Record Updated!';
  }
  
   if(isset($_POST['resultDelete']))
  {
	  $usn=$_POST['usn'];
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query="delete from student where usn='$usn'";
	  mysqli_query($conn,$query) or die(mysqli_error($conn));
	  $showErrorDivClasses='show well';
	  $showErrorDivMsg='Record Deleted!';
  }
?>
	<div class="row">
	<div class="<?php echo $showResultsDivClasses?>" style="margin-top: 15px;">
		  <form method="post" action="student.php">
			<h4 style="margin-top: 15px;"><?php echo $headingValue;?> Student Information here</h4>
			<input style="width: 500px; margin-top: 5px;" value="<?php echo $name;?>" id="name" type="text" class="form-control" name="name" placeholder="Name"> 
			<input style="width: 500px; margin-top: 5px;" value="<?php echo $usn;?>" <?php echo $readonly; ?> id="usn" type="text" class="form-control" name="usn" placeholder="USN">
			<input style="width: 100px; margin-top: 5px;" value="<?php echo ($age !== 0) ? $age : '';?>" id="age" type="text" class="form-control" name="age" placeholder="Age">
			<input style="width: 100px; margin-top: 5px;" value="<?php echo ($sem !== 0) ? $sem : '';?>" id="sem" type="text" class="form-control" name="sem" placeholder="Sem"> <br>
			  <div class="form-check-inline">
				  <label class="form-check-label">
					<input type="radio" <?php echo ($gender=='m')?'checked':'' ?> class="form-check-input" name="genderRadio" value="m">Male
				  </label>
				</div>
				<div class="form-check-inline">
				  <label class="form-check-label">
					<input type="radio" <?php echo ($gender=='f')?'checked':'' ?> class="form-check-input" name="genderRadio" value="f">Female
				  </label>
				</div>
			<textarea style="width: 500px; margin-top: 5px;" class="form-control" rows="5" id="address" name="address" placeholder="Address"><?php echo $address;?></textarea><br>
			
			<button class="<?php echo (($buttonValue == 'Save')?  'show btn btn-primary' : 'hide');?>" type="submit" name="resultSave">Save</button>
			<div class="<?php echo ($buttonValue == 'Update'?  'show' : 'hide');?>">
			<button class="btn btn-primary" type="submit" name="resultUpdate">Update</button> &nbsp; &nbsp;
			<button class="btn btn-danger" type="submit" name="resultDelete">Delete</button>
			</div>
			</form>
		  </div>
		  <div class="<?php echo $showErrorDivClasses?>" style="margin-top: 15px;">
			<h5 style="margin-top: 15px;"><?php echo $showErrorDivMsg?></h5>
		  </div>
	</div>
  </div>
</div>