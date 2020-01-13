
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
   <script>
     var sessionId = "<?php echo $_SESSION['username']; ?>";

   function resetAll() {
	 document.getElementById('extraDropdownDiv').style.display = "none";
	 document.getElementById('feesAmountDiv').style.display = "none";
	 document.getElementById('commentsDiv').style.display = "none";
	 document.getElementById('paymentModeDiv').style.display = "none";
	 document.getElementById('registerPayment').style.display = "none";
	 document.getElementById('comments').innerHTML = "";
	 removeOptions(document.getElementById('extraDropdown'));
   }
   function onFeesTypeChange() {
	 resetAll();
	 var inputValue = document.getElementById('collegefeesDropdown').value;
	 var currentSemester = document.getElementById('semLabel').innerHTML.split(' ')[0];
	 
	 if (inputValue === 'college' || inputValue === 'hostel') {
		document.getElementById('extraDropdownDiv').style.display = "block";
		document.getElementById('labelforextraDropdown').innerHTML = 'Semester';
		var select = document.getElementById('extraDropdown'),
		option,
		i = 0;
		for (; i < currentSemester ; i += 1) {
			option = document.createElement('option');
			option.value = i+1;
			option.innerHTML = (i+1) + ' Sem';
			select.appendChild(option);
		}
		formQueryAndSendAjax(inputValue, 1);
	 }
	 if (inputValue === 'others' ) {
		 formQueryAndSendAjax(inputValue, '')
	 }
	 if (inputValue === 'mess') {
		document.getElementById('extraDropdownDiv').style.display = "block";
		document.getElementById('labelforextraDropdown').innerHTML = 'Food Type';
		var select = document.getElementById('extraDropdown'),
		option;
		option = document.createElement('option');
		option.value = 'veg';
		option.innerHTML = 'Vegetarian';
		select.appendChild(option);
		option = document.createElement('option');
		option.value = 'nonveg';
		option.innerHTML = 'Non Vegetarian';
		select.appendChild(option);
		formQueryAndSendAjax(inputValue, 'veg');
	 }
   }
   
   function onExtraChange() {
	   var type = document.getElementById('collegefeesDropdown').value;
	   var extra = document.getElementById('extraDropdown').value;
	   formQueryAndSendAjax(type, extra)
   }
   
   function formQueryAndSendAjax(type, extra) {
	   var query = 'select fees from feesconfig where type=\'' + type + '\'';
	   if (extra !== '') {
		   query += ' and extra=\'' + extra + '\'';
	   }
	   $.post('fees.php', { requestType: 'feesconfig', query: query }, function(data){
		    document.getElementById('feesAmount').readOnly = false;
		    document.getElementById('feesAmountDiv').style.display = "block";
			document.getElementById('paymentModeDiv').style.display = "block";
			document.getElementById('feesAmount').value = '';
		   if (data !== '0') {
			   document.getElementById('feesAmount').value =  data;
			   document.getElementById('feesAmount').readOnly = true;
		   }
		   document.getElementById('commentsDiv').style.display = "block";
		   document.getElementById('registerPayment').style.display = "block";
        });
   }
   
   function removeOptions(selectbox) {
		var i;
		for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
		{
			selectbox.remove(i);
		}
	}
	
	function fetchPaymentHistory() {
		var usn = document.getElementById('usnLabel').innerHTML;
		var query = 'select * from payment where usn=\'' + usn + '\' order by timestamp desc';
		
		$.post('fees.php', { requestType: 'fetch', query: query }, function(data){
			data = JSON.parse(data);
			var tbody = document.getElementById('paymentHistoryTableBody');
			tbody.innerHTML = "";
			for (var i =0; data[i]; i++) {
				var row = document.createElement('tr');
				for (var j =0; j < 8 ; j++) {
					var cell = document.createElement('td');
					cell.innerHTML = data[i][j];
					row.appendChild(cell);				
				}
				tbody.appendChild(row);				
			}
        });
	}
	
	function registerPaymentFunc() {
		var usn = document.getElementById('usnLabel').innerHTML;
		var feesAmount =  document.getElementById('feesAmount').value;
		var feesType = document.getElementById('collegefeesDropdown').value;
		var extra = document.getElementById('extraDropdown').value;
		if (extra) {
			feesType += ' (' + extra + ')';
		}
		var paymentMode = document.getElementById('paymentMode').value;
		var comment = document.getElementById('comments').value;
		var query = 'insert into payment values(';
		query += '\'' + (new Date).getTime() + '_' + usn + '\',';
		query += '\'' + usn + '\',';
		query += feesAmount + ',';
		query += '\'' + (new Date).getTime() + '\',';
		query += '\'' + feesType + '\',';
		query += '\'' + paymentMode + '\',';
		query += '\'' + comment + '\',';
		query += '\'INITIATED\',';
		query += '\'' + sessionId + '\'';
		query += ')';
		console.log(query);
		   $.post('fees.php', { requestType: 'processFees', query: query }, function(data){
			   if (data === '1') {
				   alert('Record Added Successfully');
				   resetAll();
				   document.getElementById('collegefeesDropdown').value = '----';
			   } else {
				   alert('Record Insertion Failed. Please try again later.')
			   }
        });
	}
 </script>
  <div class="container">
	  <div class="row">
		  <h1>Payment Info</h1>
		  <form method="post" action="payment.php">
			  <div class="row">
			  <div class="col-sm-4">
			   <input name="searchUSN" id="searchUSN" type="text" class="form-control" placeholder="Enter USN">
			  </div>
			   <button name="searchSubmit" type="submit" class="btn btn-primary col-sm-2">Search</button>
			  </div>
		   </form>
	  </div>
  
  <?php
  $officername='';
  $officername=$_SESSION['username'];
  $severname="localhost";
  $username="root";
  $password="";
  $dbname="FeesPay";
  $usn='';
  $name='';
  $age=0;
  $sem=0;
  $readonly='';
  $showPaymentsDivClasses = 'hide';
  $showErrorDivClasses = 'hide';
  $showRecordPaymentsDivClasses = 'hide';
  $headingValue='';
  $showErrorDivMsg='';
  if(isset($_POST['searchSubmit']))
  {
	  $searchusn=$_POST['searchUSN'];
	  $conn=mysqli_connect($severname,$username,$password,$dbname)or die(mysqli_error($conn));
	  $select_db=mysqli_select_db($conn,$dbname)or die(mysqli_error($conn));
	  $tbl_name="student";
	  $query="SELECT *FROM $tbl_name where usn='$searchusn'";
	  $result=mysqli_query($conn,$query)or die(mysqli_error($conn));
	  $count=mysqli_num_rows($result);
	  if($count==false)
		{
		  $showPaymentsDivClasses='hide';
		  $showErrorDivClasses='show well';
		  $showErrorDivMsg='Invalid USN!';
		}
	  else
		{
		  $showPaymentsDivClasses='show well';
		  while($row=mysqli_fetch_array($result))
			{
				$usn=$row['usn'];
				$name=$row['name'];
				$sem=$row['sem'];
			}
		$showRecordPaymentsDivClasses = 'show';
		}
  }
?>
	<div class="row">
	<div class="<?php echo $showPaymentsDivClasses?>" style="margin-top: 15px; height: 70vh;">
			<h4 style="margin-top: 15px;">Student Information</h4>
			<label id="usnLabel" style="width: 300px; margin-top: 5px;"  class="form-control"><?php echo $usn;?></label>
			<label style="width: 300px; margin-top: 5px;"  class="form-control"><?php echo $name;?></label>
			<label id="semLabel" style="width: 100px; margin-top: 5px;"  class="form-control"><?php echo $sem . ' Sem';?></label>
			<div style="margin-top: 20px;" class="<?php echo $showRecordPaymentsDivClasses?>">
			<!-- Nav tabs -->
			  <ul class="nav nav-tabs">
				<li class="nav-item active">
				  <a class="nav-link" data-toggle="tab" href="#home">Make Payment</a>
				</li>
				<li class="nav-item" onclick="fetchPaymentHistory()">
				  <a class="nav-link" data-toggle="tab" href="#menu1">Payment History</a>
				</li>
			  </ul>
			    <!-- Tab panes -->
		  <div class="tab-content">
			<div id="home" class="container tab-pane active" style="height: 43vh;overflow-y: scroll;">
			 <! -- fees payment -->
			  <form method="post" action="payment.php">
					<h4>Enter Fees Information</h4>
						<div class="row">
							<div class="form-group col-sm-6" style="width: 300px;">
							  <label for="sel1">Payment Type</label>
							  <select class="form-control" id="collegefeesDropdown" onchange="onFeesTypeChange()">
							  <option value="----"> ---- </option>
								<option value="college">College Fees</option>
								<option value="hostel">Hostel Fees</option>
								<option value="mess">Mess Fees</option>
								<option value="others">Others</option>
							  </select>
							</div>
							<div class="col-sm-3">
							<div class="form-group" id="extraDropdownDiv" style="display: none;">
							 <label id="labelforextraDropdown" for="extraDropdown">Semester</label>
							  <select class="form-control" onchange="onExtraChange()" id="extraDropdown">
							  </select>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-6" id="feesAmountDiv" style="display: none; width: 300px;">
							 <label for="feesAmount">Fees (in INR)</label>
							 <input id="feesAmount" type="number" class="form-control" name="feesAmount" placeholder="Fees">
							</div>
							<div id="paymentModeDiv" class="form-group col-sm-3" style="display: none;">
							  <label for="paymentMode">Payment Mode</label>
							  <select class="form-control" id="paymentMode">
								<option value="cash">Cash</option>
								<option value="dd">Demand Draft</option>
								<option value="cheque">Cheque</option>
							  </select>
							</div>
						</div>
						<div class="form-group" id="commentsDiv" style="display: none;">
						 <label for="extraDropdown">Any Comments</label>
						 <textarea style="width: 600px; margin-top: 5px; max-height: 90px; max-width: 60vw;" class="form-control" rows="2" id="comments" name="comments" placeholder="Comments"></textarea>
						</div>
						<button style="display: none;" class="btn btn-success" type="button" onclick="registerPaymentFunc()" id="registerPayment">Register Payment</button>
				</form>
			</div>
			<! -- Payment Table -->
			<div id="menu1" class="container tab-pane fade" style="height: 40vh;overflow-y: scroll;">
			  <table class="table table-hover">
				<thead>
				  <tr>
					<th style="width: 15%;">Reciept No</th>
					<th style="width: 10%;">USN</th>
					<th style="width: 10%;">Amount</th>
					<th style="width: 10%;">TimeStamp</th>
					<th style="width: 10%;">Type</th>
					<th style="width: 8%;">Mode</th>
					<th style="width: 15%;">Comment</th>
					<th style="width: 10%;">Status</th>
				  </tr>
				</thead>
				<tbody style="font-size: 12px;" id="paymentHistoryTableBody">
				</tbody>
			  </table>
			</div>
		  </div>
		</div>
	</div>
	<div class="<?php echo $showErrorDivClasses?>" style="margin-top: 15px;">
		<h5 style="margin-top: 15px;"><?php echo $showErrorDivMsg?></h5>
	</div>
	</div>
  </div>
</div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>