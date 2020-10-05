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
		
<body class="metro">
	<div class="example">
		<legend>Timed Parking</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" ></a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th  class="text-left">Reg No</th>
				<th  class="text-left">Vehicle Type</th>
				<th  class="text-left">Check-In Time</th>
				<th  class="text-left">Check-out Time</th>
				<th  class="text-left">Status</th>
				<th  class="text-left">Cost</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


