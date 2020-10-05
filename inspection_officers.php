<?php

require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');

if (!isset($_SESSION))
{
	session_start();
}

if (isset($_POST['submit'])){
	$User_ID = $_POST['User_ID'];
	$ServiceHeaderID = $_POST['ServiceHeaderID'];
}
	$sql = "insert into InspectionOfficers (UserID, ServiceHeaderID) values('$User_ID', '$ServiceHeaderID')";
	$result = sqlsrv_query($db, $sql);
// echo $sql;exit;
	if(!$result){
		 // header('Location: '.$_SERVER['REQUEST_URI']);
		echo 'not successful';

	}else{
		 // header('Location: '.$_SERVER['REQUEST_URI']);
		echo 'successful';
		echo $UserID;

	}

//}

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div class="input-control select" data-role="input-control">
		1st Inspection Officer: <select name="User_ID" >
		<option value="">Select An Inspection Officer</option>                      
                    <?php 
                         	
						$s_sql="SELECT u.Email, u.UserID, ag.FirstName, ag.Middlename, ag.LastName FROM Users u join agents ag on u.agentid=ag.agentID";						

						
						$s_result = sqlsrv_query($db, $s_sql);
						if ($s_result) 
						{ //connection succesful 
						  while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						  {
							  $fname = $row["FirstName"];
							  $lname = $row["LastName"];							  
							  $User_ID = $row["UserID"];
                                    
						   ?>
						  <option value="<?php echo $User_ID; ?>" ><?php echo $fname; ?> <?php echo $User_ID; ?> <?php echo $lname; ?></option>
						<?php 
						  }
						}
                          ?>
                  </select> 
	</div>		


<?php echo $UserID; ?>
		<input type="text" name="ServiceHeaderID" value="<?php echo $ServiceID;?>">

                  <!-- <?php  //echo $s_sql;  ?>
                </div></td> -->
<input type="submit" name="submit" value="Add User" />
<form>

