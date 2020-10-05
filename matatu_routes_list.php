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
	$RouteID=$_REQUEST['RouteID'];
	$sql="Delete from MatatuRoutes where RouteID=$RouteID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "MatatuRoute Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	$RouteID=$_REQUEST['RouteID'];
	$RouteName=$_REQUEST['RouteName'];	
	
	if ($RouteID=='0')
	{
		$sql="Insert into MatatuRoutes (RouteName,CreatedBY)
		Values('$RouteName',$CreatedUserID)";

	} else
	{
		$sql="Update MatatuRoutes set RouteName='$RouteName' where RouteID=$RouteID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Matatu Route Saved Successfully";			
	} else
	{
		DisplayErrors(); 
		echo "<br>".$sql;
		
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
        <legend>Matatu Routes</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('matatu_route.php?1=1','content')">Add</a></th>
                    <th colspan="2" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="10%" class="text-left">RouteID</th>
                    <th width="50%" class="text-left">Route Name</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>