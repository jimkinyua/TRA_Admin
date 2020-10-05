<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['bill']))
{	

	$CustomerID=$_REQUEST['CustomerID'];
	$EstateID=$_REQUEST['EstateID'];	
	$HouseNumber=$_REQUEST['HouseNumber'];
	$uhn=$_REQUEST['uhn'];
	$Amount=$_REQUEST['Amount'];
	$BillNumber=$_REQUEST['BillNumber'];
	$ApplicationID=$_REQUEST['ApplicationID'];	
	
	// print_r($_REQUEST);
	// exit;
	
	$msg=BillHouse($db,$ApplicationID,$CustomerID,$EstateID,$HouseNumber,$BillNumber,$uhn,$Amount,$UserID,$cosmasRow);	
}
?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
		<script>
		alert('hre');
	</script>
<body class="metro">
	<div class="example">
		<legend>Wards</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('ward.php?i=1','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th width="15%" class="text-left">House Number</th>
				<th width="25%" class="text-left">Estate Name</th>
				<th width="25%" class="text-left">Tenant Name</th>
				<th width="15%" class="text-left">Bill No</th>
				<th width="10%" class="text-left">Amount</th>
				<th width="10%" class="text-left">&nbsp;</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


