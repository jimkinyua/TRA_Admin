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
		<legend>Current Owner(s)</legend> 		
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<th width="7%" class="text-left">LRN</th>
					<th width="7%" class="text-left">Plot Number</th>
					<th width="14%" class="text-left">Balance</th>
					<th width="14%" class="text-left">OwnerName</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 
		
	</div>
</body>


