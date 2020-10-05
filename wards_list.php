<?php
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
	$WardID=$_REQUEST['WardID'];
	$sql="Delete from Wards where WardID=$WardID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Ward Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$WardID=$_REQUEST['WardID'];
	$WardName=$_REQUEST['WardName'];	
	$SubCountyID=$_REQUEST['SubCountyID'];
	
	if ($WardID=='0')
	{
		$sql="Insert into Wards (WardName,SubCountyID,CreatedBY)
		Values('$WardName',$SubCountyID,$CreatedUserID)";

	} else
	{
		$sql="Update Wards set WardName='$WardName',SubCountyID=$SubCountyID where WardID=$WardID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Ward Saved Successfully";			
	} else
	{
		//DisplayErrors();
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
		<legend>Wards</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('ward.php?i=1','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th width="14%" class="text-left">WardID</th>
				<th width="12%" class="text-left">Ward Name</th>
				<th width="20%" class="text-left">Sub County</th>
				<th width="12%" class="text-left">&nbsp;</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


