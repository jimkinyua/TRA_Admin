<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$uhn='';
$CreatedUserID = $_SESSION['UserID'];
$HouseID=0;
$EstateID=$_REQUEST['EstateID'];
$EstateName=$_REQUEST['EstateName'];

//echo $uhn;

if (isset($_REQUEST['save']))
{	
	//print_r($_REQUEST);
	$EstateID=$_REQUEST['EstateID'];
	$HouseID=$_REQUEST['HouseID'];
	$HouseNumber=$_REQUEST['HouseNumber'];
	$MonthlyRent=$_REQUEST['MonthlyRent'];
	$FromDate=$_REQUEST['FromDate'];	
	
	if ($HouseID=='0')
	{
		$sql="select count(*)+1 Houses from houses where EstateID='$EstateID'";
		
		$rst=sqlsrv_query($db,$sql);
		while($rw=sqlsrv_fetch_array($rst,SQLSRV_FETCH_ASSOC)){
			$uhn=sprintf("%04d", $EstateID).'-'.sprintf("%05d", $rw['Houses']);
			//$HouseNumber=sprintf("%03d", $EstateID).'-'.sprintf("%03d", $rw['Houses']);
		}

		$sql="set dateformat dmy if not exists (select 1 from Houses where HouseNumber='$HouseNumber')Insert into Houses (uhn,HouseNumber,EstateID,CreatedBY)
		Values('$uhn','$HouseNumber','$EstateID',$CreatedUserID)";

		$sql2="set dateformat dmy if not exists (select 1 from Tenancy where HouseNumber='$HouseNumber') Insert into Tenancy (uhn,HouseNumber,EstateID,MonthlyRent,Balance,FromDate,CreatedBY)
		Values('$uhn','$HouseNumber','$EstateID','$MonthlyRent',0,'$FromDate',$CreatedUserID)";


	} else
	{
		$sql="Update Houses set HouseNumber='$HouseNumber',EstateID='$EstateID' where HouseID=$HouseID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{
		$result = sqlsrv_query($db, $sql2);	
		if($result){

			$msg = "House Created/Modified Successfully";
		}else{
			//echo $sql2;
			DisplayErrors();
		}

	} else
	{
		DisplayErrors();
		ECHO $sql;
		$msg = "Details Failed to save";
				
	}	
}




?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
		
<body class="metro">
	<div class="example">
		<legend><?php echo $EstateName. ' '; ?>Houses</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('house.php?EstateID=<?php echo $EstateID; ?>&EstateName=<?php echo $EstateName; ?>','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th width="14%" class="text-left">HouseID</th>
				<th width="12%" class="text-left">House Number</th>
				<th width="12%" class="text-left">Monthly Rent</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


