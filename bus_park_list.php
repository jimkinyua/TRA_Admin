<!-- <?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$ParkID=$_REQUEST['ParkID'];
	$sql="Delete from BusParks where ParkID=$ParkID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Bus Park Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$ParkID=$_REQUEST['ParkID'];
	$ParkName=$_REQUEST['ParkName'];	
	
	//print_r($_REQUEST); exit;
	if ($ParkID=='0')
	{
		$sql="Insert into BusParks (ParkName,CreatedBY)
		Values('$ParkName',$CreatedUserID)";

	} else
	{
		$sql="Update BusParks set ParkName='$ParkName' where ParkID=$ParkID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Bus Park Saved Successfully";			
	} else
	{
		DisplayErrors();
		
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
		<legend>Bus Perks</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('bus_park.php?1=1','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th width="14%" class="text-left">Park ID</th>
				<th width="12%" class="text-left">Park Name</th>
				<th width="12%" class="text-left">&nbsp;</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


 -->