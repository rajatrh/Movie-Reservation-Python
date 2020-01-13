<?php
  function processDrpdown($query) {
	  $severname="localhost";
	  $username="root";
	  $password="";
	  $dbname="FeesPay";
	  $tbl_name="feesconfig";
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query=$query;
	  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
	  $count=mysqli_num_rows($result);
	  if($count==false)
		{
			echo 0;
		} else {
			while($row=mysqli_fetch_array($result))
			{
			  echo $row['fees'];
			}				
		}
	}	        
	
	if ($_POST['requestType']){
		if ($_POST['requestType'] === 'feesconfig') {
			processDrpdown($_POST['query']);
		}
		if ($_POST['requestType'] === 'fetch') {
			$usn='';
			fetchPaymentHistory($_POST['query']);
		}
		if($_POST['requestType'] === 'processFees') {
			processFees($_POST['query']);
		}
	}
	
	function fetchPaymentHistory($query) {
	  $severname="localhost";
	  $username="root";
	  $password="";
	  $dbname="FeesPay";
	  $tbl_name="payment";
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query=$query;
	  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
	  $result_set = [];
	  while($row = mysqli_fetch_array($result)) {
		$result_set[] = $row;
	  }
	  echo json_encode($result_set);
	}
	
	function processFees($query) {
	  $severname="localhost";
	  $username="root";
	  $password="";
	  $dbname="FeesPay";
	  $tbl_name="feesconfig";
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $query=$query;
	  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
	  echo $result;
	}
?>