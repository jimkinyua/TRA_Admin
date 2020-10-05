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
		<legend>Active Users (Currently Logged In)</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>				
				<th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th  class="text-left">User Names</th>
				<th  class="text-left">Role Center</th>	
				<th  class="text-left">Log in Time</th>				
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


