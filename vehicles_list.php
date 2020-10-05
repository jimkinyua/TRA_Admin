<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];


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
		<legend>Company (Sacco) Vehicles</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>				
				<th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th width="25%" class="text-left">Reg Number</th>
				<th width="25%" class="text-left">BusPark ID</th>
				<th width="25%" class="text-left">Sitting Capacity</th>
				<th width="25%" class="text-left">Route</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


