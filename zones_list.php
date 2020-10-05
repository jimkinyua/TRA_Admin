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
	$ZoneID=$_REQUEST['ZoneID'];
	$sql="Delete from Zones where ZoneID=$ZoneID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Zone Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{	
	$ZoneID=$_REQUEST['ZoneID'];
	$ZoneName=$_REQUEST['ZoneName'];	
	$WardID=$_REQUEST['WardID'];	
	
	if ($ZoneID=='0')
	{
		$sql="Insert into BusinessZones (ZoneName,WardID,CreatedBY)
		Values('$ZoneName',$WardID,'$CreatedUserID')";

	} else
	{
		$sql="Update BusinessZones set ZoneName='$ZoneName',WardID=$WardID where ZoneID=$ZoneID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Zone Saved Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Details Failed to save";
		$page="<script type='text/javascript'>
		loadmypage('zones.php?ZoneID='.$ZoneID.'','content')
		</script>";				
		echo ($page);
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
        <legend>Users</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('zones.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="14%" class="text-left">Zone</th>
                    <th width="12%" class="text-left">Ward</th>
                    <th width="20%" class="text-left">Sub County</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>